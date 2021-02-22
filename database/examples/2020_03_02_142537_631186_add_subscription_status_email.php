<?php

use App\Helpers\LanguageHelper;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Bitrix\Main\Mail\Internal\EventMessageSiteTable;
use Bitrix\Main\Mail\Internal\EventMessageTable;
use Bitrix\Main\Mail\Internal\EventTypeTable;

class AddSubscriptionStatusEmail20200302142537631186 extends BitrixMigration
{
    /** @var string $eventName Символьный код почтового события */
    private $eventName = 'SUBSCRIPTION_STATUS';

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        /** @var array|string[] $languages - Массив идентификаторов языковых версий сайта */
        $languages = LanguageHelper::getAvailableLanguages();

        /** @var array|string[] $subjects - Темы писем под разные языки */
        $subjects = [
            'en' => 'Alrosa: Subscription status',
            'ru' => 'Алроса: Статус подписки',
            'cn' => 'Alrosa: Subscription status'
        ];

        foreach ($languages as $siteId => $language) {
            $resultType = EventTypeTable::add([
                'fields' => [
                    'LID' => $language,
                    'EVENT_NAME' => $this->eventName,
                    'NAME' => 'Изменение статуса подписок',
                    'DESCRIPTION' => "#EMAIL_TO# - Email пользователя\n"
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
                    'EVENT_NAME' => $this->eventName,
                    'LID' => $siteId,
                    'LANGUAGE_ID' => $language,
                    'SITE_TEMPLATE_ID' => '',
                    'ACTIVE' => 'Y',
                    'EMAIL_FROM' => '#DEFAULT_EMAIL_FROM#',
                    'EMAIL_TO' => '#EMAIL_TO#',
                    'CC' => '',
                    'SUBJECT' => $subjects[$language],
                    'BODY_TYPE' => 'html',
                    'MESSAGE' => "<?php EventMessageThemeCompiler::includeComponent('email.dispatch:user.subscription.status', '', [
'email' => #EMAIL_TO#
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
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        $res = EventTypeTable::getList(["filter" => ["EVENT_NAME" => $this->eventName]]);
        while ($type = $res->fetch()) {
            EventTypeTable::delete($type["ID"]);
        }

        $res = EventMessageTable::getList(["filter" => ["EVENT_NAME" => $this->eventName]]);
        while ($type = $res->fetch()) {
            $r = EventMessageSiteTable::getList(["filter" => ["EVENT_MESSAGE_ID" => $type["ID"]]]);
            db()->query(
                'DELETE FROM `' . EventMessageSiteTable::getTableName() . '` WHERE `EVENT_MESSAGE_ID` = ' . $type["ID"]
            );

            EventMessageTable::delete($type["ID"]);
        }
    }
}
