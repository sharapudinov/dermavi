<?php

use App\Helpers\LanguageHelper;
use App\Helpers\SiteHelper;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Bitrix\Main\Mail\Internal\EventMessageSiteTable;
use Bitrix\Main\Mail\Internal\EventMessageTable;
use Bitrix\Main\Mail\Internal\EventTypeTable;

/**
 * Класс, описывающий миграцию для создания почтового события для отправки письма об итогах аукциона
 * Class AddAuctionResultsManagerMailEvent20191016173945940911
 */
class AddAuctionResultsManagerMailEvent20191016173945940911 extends BitrixMigration
{
    /** @var string - Символьный код почтового события при успешно пройденном аукционе */
    private const SUCCESS_EVENT_NAME = 'SUCCESS';

    /** @var string - Символьный код почтового события при аукционе с переторжкой */
    private const REBIDDING_EVENT_NAME = 'REBIDDING';

    /**
     * Добавляет почтовые события и шаблоны
     *
     * @param string $eventName - Символьный код почтового события
     * @param string $name - Название почтового события
     * @param array|string[] $subjects - Массив тем письма на разных языках
     *
     * @return void
     *
     * @throws MigrationException
     */
    private function addMailEvent(string $eventName, string $name, array $subjects): void
    {
        /** @var array|string[] $languages - Массив доступных языков */
        $languages = LanguageHelper::getAvailableLanguages();

        foreach ($languages as $siteId => $language) {
            $resultType = EventTypeTable::add([
                'fields' => [
                    'LID' => $language,
                    'EVENT_NAME' => 'AUCTION_RESULTS_MANAGER_' . $eventName,
                    'NAME' => $name,
                    'DESCRIPTION' => "#EMAIL_TO# - Email пользователя\n" .
                        "#AUCTION_ID# - Идентификатор аукциона\n" .
                        "#USER_ID# - Идентификатор пользователя\n"
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
                    'EVENT_NAME' => 'AUCTION_RESULTS_MANAGER_' . $eventName,
                    'LID' => $siteId,
                    'LANGUAGE_ID' => $language,
                    'SITE_TEMPLATE_ID' => '',
                    'ACTIVE' => 'Y',
                    'EMAIL_FROM' => '#DEFAULT_EMAIL_FROM#',
                    'EMAIL_TO' => '#EMAIL_TO#',
                    'CC' => '',
                    'SUBJECT' => $subjects[$language],
                    'BODY_TYPE' => 'html',
                    'MESSAGE' => "<?php EventMessageThemeCompiler::includeComponent('email.dispatch:auction.results.manager', '', [
'auction_id' => #AUCTION_ID#,
'user_id' => #USER_ID#
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
     * Удаляет почтовые события и шаблоны
     *
     * @param string $eventName - Название почтового события
     *
     * @return void
     */
    private function deleteMailEvent(string $eventName): void
    {
        $res = EventTypeTable::getList(["filter" => ["EVENT_NAME" => 'AUCTION_RESULTS_MANAGER_' . $eventName]]);
        while ($type = $res->fetch()) {
            EventTypeTable::delete($type["ID"]);
        }

        $res = EventMessageTable::getList(["filter" => ["EVENT_NAME" => 'AUCTION_RESULTS_MANAGER_' . $eventName]]);
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
        $this->addMailEvent(
            self::SUCCESS_EVENT_NAME,
            'Результаты аукциона для менеджера',
            [
                'ru' => 'Аукцион "#AUCTION_NAME#" окончен',
                'en' => 'Auction "#AUCTION_NAME#" is finished',
                'cn' => 'Auction "#AUCTION_NAME#" is finished'
            ]
        );
        $this->addMailEvent(
            self::REBIDDING_EVENT_NAME,
            'Результаты аукциона с переторжкой для менеджера',
            [
                'ru' => 'Аукцион "#AUCTION_NAME#" окончен с переторжкой',
                'en' => 'Auction "#AUCTION_NAME#" is finished with rebidding',
                'cn' => 'Auction "#AUCTION_NAME#" is finished with rebidding'
            ]
        );
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        $this->deleteMailEvent(self::SUCCESS_EVENT_NAME);
        $this->deleteMailEvent(self::REBIDDING_EVENT_NAME);
    }
}
