<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Bitrix\Main\Mail\Internal\EventMessageSiteTable;
use Bitrix\Main\Mail\Internal\EventMessageTable;
use Bitrix\Main\Mail\Internal\EventTypeTable;

/**
 * Класс, описывающий миграцию для создания почтового события для отправки письма
 * об индивидуальном заказе из конструктора ЮБИ
 * Class AddAuctionUserSignupEventType20200702112432022641
 */
class AddAuctionUserSignupEventType20200702112432022641 extends BitrixMigration
{
    /** @var string Символьный код события */
    private const EVENT_NAME = 'AUCTION_USER_SIGNUP';

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $resultType = EventTypeTable::add([
            'fields' => [
                'LID' => 'ru',
                'EVENT_NAME' => self::EVENT_NAME,
                'NAME' => 'Аукцион: регистрация',
                'DESCRIPTION' => "#EMAIL_TO# - Кому (email)\n
                                        #USER_ID# - Идетификатор пользователя
                                        #LOT_ID# - Идентификатор
                                        #YOUR_BID_ID# - Идентификатор
                                        #NEW_BID_ID# - Идентификатор
                                        "
            ]
        ]);
        if (!$resultType->isSuccess()) {
            throw new MigrationException(
                'Ошибка при добавлении типа шаблона: '
                . implode(', ', $resultType->getErrorMessages())
            );
        };

        $resultMessage = EventMessageTable::add([
            'fields' => [
                'EVENT_NAME' => self::EVENT_NAME,
                'LID' => 's2',
                'LANGUAGE_ID' => 'ru',
                'SITE_TEMPLATE_ID' => '',
                'ACTIVE' => 'Y',
                'EMAIL_FROM' => '#DEFAULT_EMAIL_FROM#',
                'EMAIL_TO' => '#EMAIL_TO#',
                'CC' => '',
                'SUBJECT' => 'Аукцион: регистрация',
                'BODY_TYPE' => 'html',
                'MESSAGE' => "<?php  
                    EventMessageThemeCompiler::includeComponent('email.dispatch:user.pb_signup', '', [
                    'user_id' => #USER_ID#,
                    ]); ?>"
            ],
        ]);

        EventMessageSiteTable::add([
            'fields' => [
                'EVENT_MESSAGE_ID' => $resultMessage->getId(),
                'SITE_ID' => 's2',
            ],
        ]);

        $resultType = EventTypeTable::add([
            'fields' => [
                'LID' => 'en',
                'EVENT_NAME' => self::EVENT_NAME,
                'NAME' => 'Auction: registration',
                'DESCRIPTION' => "#EMAIL_TO# - To (email)\n
                                        #USER_ID# - User id
                                        #LOT_ID# - Идентификатор
                                        #YOUR_BID_ID# - Идентификатор
                                        #NEW_BID_ID# - Идентификатор
                                        "
            ]
        ]);
        if (!$resultType->isSuccess()) {
            throw new MigrationException(
                'Ошибка при добавлении типа шаблона: '
                . implode(', ', $resultType->getErrorMessages())
            );
        };

        $resultMessage = EventMessageTable::add([
            'fields' => [
                'EVENT_NAME' => self::EVENT_NAME,
                'LID' => 's1',
                'LANGUAGE_ID' => 'en',
                'SITE_TEMPLATE_ID' => '',
                'ACTIVE' => 'Y',
                'EMAIL_FROM' => '#DEFAULT_EMAIL_FROM#',
                'EMAIL_TO' => '#EMAIL_TO#',
                'CC' => '',
                'SUBJECT' => 'Auction: registration',
                'BODY_TYPE' => 'html',
                'MESSAGE' => "<?php 
                    EventMessageThemeCompiler::includeComponent('email.dispatch:user.pb_signup', '', [
                    'user_id' => #USER_ID#,
                    ]); ?>"
            ],
        ]);

        EventMessageSiteTable::add([
            'fields' => [
                'EVENT_MESSAGE_ID' => $resultMessage->getId(),
                'SITE_ID' => 's1',
            ],
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
        $res = EventTypeTable::getList(["filter" => ["EVENT_NAME" => self::EVENT_NAME]]);
        while ($type = $res->fetch()) {
            EventTypeTable::delete($type["ID"]);
        }

        $res = EventMessageTable::getList(["filter" => ["EVENT_NAME" => self::EVENT_NAME]]);
        while ($type = $res->fetch()) {
            EventMessageTable::delete($type["ID"]);
        }
    }
}
