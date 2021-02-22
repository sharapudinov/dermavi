<?php

use App\Helpers\LanguageHelper;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Bitrix\Main\Mail\Internal\EventMessageSiteTable;
use Bitrix\Main\Mail\Internal\EventMessageTable;
use Bitrix\Main\Mail\Internal\EventTypeTable;

/**
 * Класс, описывающий миграцию для создания почтовых шаблонов для напоминания об окончании аукциона
 * Class AddAuctionReminderEmailTemplates20191013162353074692
 */
class AddAuctionReminderEmailTemplates20191013162353074692 extends BitrixMigration
{
    /** @var array|string[] $languages - Массив идентификаторов языковых версий сайта */
    private $languages;

    /**
     * AddAuctionReminderEmailTemplates20191013162353074692 constructor.
     */
    public function __construct()
    {
        $this->languages = LanguageHelper::getAvailableLanguages();
        parent::__construct();
    }

    /**
     * Создает почтовое событие с необходимыми шаблонами
     *
     * @param string $hoursLast - Количество часов, оставшихся до конца аукциона
     * @param array|string[] $subjects Массив, описывающий темы писем на разных языках
     *
     * @return void
     *
     * @throws MigrationException
     */
    private function addEmailEvent(string $hoursLast, array $subjects): void
    {
        foreach ($this->languages as $siteId => $language) {
            $resultType = EventTypeTable::add([
                'fields' => [
                    'LID' => $language,
                    'EVENT_NAME' => 'AUCTION_' . $hoursLast . '_HOURS_LAST',
                    'NAME' => 'Аукцион заканчивается через ' . $hoursLast . ' часа',
                    'DESCRIPTION' => "#EMAIL_TO# - Email пользователя\n" .
                        "#AUCTION_ID# - Идентификатор аукциона\n" .
                        "#USER_ID# - Идентификатор пользователя"
                ]
            ]);
            if (!$resultType->isSuccess()) {
                throw new MigrationException(
                    'Ошибка при добавлении типа шаблона: '
                    . implode(', ', $resultType->getErrorMessages())
                );
            }

            $resultMessage = EventMessageTable::add([
                'fields' => [
                    'EVENT_NAME' => 'AUCTION_' . $hoursLast . '_HOURS_LAST',
                    'LID' => $siteId,
                    'LANGUAGE_ID' => $language,
                    'SITE_TEMPLATE_ID' => '',
                    'ACTIVE' => 'Y',
                    'EMAIL_FROM' => '#DEFAULT_EMAIL_FROM#',
                    'EMAIL_TO' => '#EMAIL_TO#',
                    'CC' => '',
                    'SUBJECT' => $subjects[$language],
                    'BODY_TYPE' => 'html',
                    'MESSAGE' => "<?php EventMessageThemeCompiler::includeComponent('email.dispatch:auction.remind', '', [
   'auction_id' => #AUCTION_ID#,
   'user_id' => #USER_ID#,
   'hours_last' => " . $hoursLast . "
])?>"
                ],
            ]);

            EventMessageSiteTable::add([
                'fields' => [
                    'EVENT_MESSAGE_ID' => $resultMessage->getId(),
                    'SITE_ID' => $siteId,
                ],
            ]);
        }
    }

    /**
     * Удаляет почтовые события с необходимыми шаблонами
     *
     * @param string $hoursLast - Количество часов, оставшихся до конца аукциона
     *
     * @return void
     */
    private function removeMailEvent(string $hoursLast): void
    {
        $res = EventTypeTable::getList(["filter" => ["EVENT_NAME" => 'AUCTION_' . $hoursLast . '_HOURS_LAST']]);
        while ($type = $res->fetch()) {
            EventTypeTable::delete($type["ID"]);
        }

        $res = EventMessageTable::getList(["filter" => ["EVENT_NAME" => 'AUCTION_' . $hoursLast . '_HOURS_LAST']]);
        while ($type = $res->fetch()) {
            $r = EventMessageSiteTable::getList(["filter" => ["EVENT_MESSAGE_ID" => $type["ID"]]]);
            db()->query(
                'DELETE FROM `' . EventMessageSiteTable::getTableName() . '` WHERE `EVENT_MESSAGE_ID` = ' . $type["ID"]
            );

            EventMessageTable::delete($type["ID"]);
        }
    }

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $this->addEmailEvent('2', [
            'ru' => 'Аукцион закончится через 2 часа',
            'en' => 'Auction ends in 2 hours',
            'cn' => 'Auction ends in 2 hours'
        ]);
        $this->addEmailEvent('24', [
            'ru' => 'Аукцион закончится через 24 часа',
            'en' => 'Auction ends in 24 hours',
            'cn' => 'Auction ends in 24 hours'
        ]);
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        $this->removeMailEvent('2');
        $this->removeMailEvent('24');
    }
}
