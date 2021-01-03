<?php

use App\Helpers\LanguageHelper;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Bitrix\Main\Mail\Internal\EventMessageSiteTable;
use Bitrix\Main\Mail\Internal\EventMessageTable;
use Bitrix\Main\Mail\Internal\EventTypeTable;

/**
 * Класс, описывающий миграцию для создания почтового события и почтовых шаблонов
 * для рассылки пользователям результатов аукциона
 * Class AddMailEventAuctionResultsToUser20191028162455233439
 */
class AddMailEventAuctionResultsToUser20191028162455233439 extends BitrixMigration
{
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
            'en' => 'Alrosa: auction results',
            'ru' => 'Алроса: результаты аукциона',
            'cn' => 'Alrosa: auction results'
        ];

        foreach ($languages as $siteId => $language) {
            $resultType = EventTypeTable::add([
                'fields' => [
                    'LID' => $language,
                    'EVENT_NAME' => 'AUCTION_RESULTS_USER',
                    'NAME' => 'Результаты аукциона для пользователя',
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
                    'EVENT_NAME' => 'AUCTION_RESULTS_USER',
                    'LID' => $siteId,
                    'LANGUAGE_ID' => $language,
                    'SITE_TEMPLATE_ID' => '',
                    'ACTIVE' => 'Y',
                    'EMAIL_FROM' => '#DEFAULT_EMAIL_FROM#',
                    'EMAIL_TO' => '#EMAIL_TO#',
                    'CC' => '',
                    'SUBJECT' => $subjects[$language],
                    'BODY_TYPE' => 'html',
                    'MESSAGE' => "<?php EventMessageThemeCompiler::includeComponent('email.dispatch:auction.results.user', '', [
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
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        $res = EventTypeTable::getList(["filter" => ["EVENT_NAME" => 'AUCTION_RESULTS_USER']]);
        while ($type = $res->fetch()) {
            EventTypeTable::delete($type["ID"]);
        }

        $res = EventMessageTable::getList(["filter" => ["EVENT_NAME" => 'AUCTION_RESULTS_USER']]);
        while ($type = $res->fetch()) {
            $r = EventMessageSiteTable::getList(["filter" => ["EVENT_MESSAGE_ID" => $type["ID"]]]);
            db()->query(
                'DELETE FROM `' . EventMessageSiteTable::getTableName() . '` WHERE `EVENT_MESSAGE_ID` = ' . $type["ID"]
            );

            EventMessageTable::delete($type["ID"]);
        }
    }
}
