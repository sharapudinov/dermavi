<?php

use App\Helpers\LanguageHelper;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Bitrix\Main\Mail\Internal\EventMessageSiteTable;
use Bitrix\Main\Mail\Internal\EventMessageTable;
use Bitrix\Main\Mail\Internal\EventTypeTable;

/**
 * Класс, описывающий миграцию для создания почтового шаблона о переторжке для участников аукциона
 * Class AddAuctionRebiddingUserMailEvent20191018191255261095
 */
class AddAuctionRebiddingUserMailEvent20191018191255261095 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        /** @var array|string[] $languages - Массив доступных языков */
        $languages = LanguageHelper::getAvailableLanguages();

        $subjects = [
            'ru' => 'Алроса: Аукцион "#AUCTION_NAME#" закончен с переторжками',
            'en' => 'Alrosa: Auction "#AUCTION_NAME#" concluded with rebiddings',
            'cn' => 'Alrosa: Auction "#AUCTION_NAME#" concluded with rebiddings'
        ];

        foreach ($languages as $siteId => $language) {
            $resultType = EventTypeTable::add([
                'fields' => [
                    'LID' => $language,
                    'EVENT_NAME' => 'AUCTION_RESULTS_USER_REBIDDING',
                    'NAME' => 'Результаты аукциона с переторжкой для участник',
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
                    'EVENT_NAME' => 'AUCTION_RESULTS_USER_REBIDDING',
                    'LID' => $siteId,
                    'LANGUAGE_ID' => $language,
                    'SITE_TEMPLATE_ID' => '',
                    'ACTIVE' => 'Y',
                    'EMAIL_FROM' => '#DEFAULT_EMAIL_FROM#',
                    'EMAIL_TO' => '#EMAIL_TO#',
                    'CC' => '',
                    'SUBJECT' => $subjects[$language],
                    'BODY_TYPE' => 'html',
                    'MESSAGE' => "<?php EventMessageThemeCompiler::includeComponent('email.dispatch:auction.rebidding.user', '', [
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
        $res = EventTypeTable::getList(["filter" => ["EVENT_NAME" => 'AUCTION_RESULTS_USER_REBIDDING']]);
        while ($type = $res->fetch()) {
            EventTypeTable::delete($type["ID"]);
        }

        $res = EventMessageTable::getList(["filter" => ["EVENT_NAME" => 'AUCTION_RESULTS_USER_REBIDDING']]);
        while ($type = $res->fetch()) {
            $r = EventMessageSiteTable::getList(["filter" => ["EVENT_MESSAGE_ID" => $type["ID"]]]);
            db()->query(
                'DELETE FROM `' . EventMessageSiteTable::getTableName() . '` WHERE `EVENT_MESSAGE_ID` = ' . $type["ID"]
            );

            EventMessageTable::delete($type["ID"]);
        }
    }
}
