<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Bitrix\Main\Mail\Internal\EventMessageSiteTable;
use Bitrix\Main\Mail\Internal\EventMessageTable;
use Bitrix\Main\Mail\Internal\EventTypeTable;

class ChangeUserAuctionSignup20201009173615228493 extends BitrixMigration
{
    /** @var string Символьный код события */
    private const EVENT_NAME = 'AUCTION_USER_SIGNUP';

    /**
     * Run the migration.
     *
     * @throws \Exception
     * @return mixed
     */
    public function up()
    {
        $res = EventTypeTable::getList(["filter" => ["EVENT_NAME" => self::EVENT_NAME]]);
        while ($type = $res->fetch()) {
            EventTypeTable::delete($type["ID"]);
        }

        $res = EventMessageTable::getList(["filter" => ["EVENT_NAME" => self::EVENT_NAME]]);
        while ($type = $res->fetch()) {
            EventMessageTable::delete($type["ID"]);
        }

        $resultType = EventTypeTable::add(
            [
                'fields' => [
                    'LID'         => 'ru',
                    'EVENT_NAME'  => self::EVENT_NAME,
                    'NAME'        => 'Аукцион: регистрация',
                    'DESCRIPTION' => "#EMAIL_TO# - Кому (email)\n
                                        #USER_ID# - Идетификатор пользователя
                                        #ACTION_ID# - Идентификатор
                                        ",
                ],
            ]
        );
        if (!$resultType->isSuccess()) {
            throw new MigrationException(
                'Ошибка при добавлении типа шаблона: '
                . implode(', ', $resultType->getErrorMessages())
            );
        };

        $resultMessage = EventMessageTable::add(
            [
                'fields' => [
                    'EVENT_NAME'       => self::EVENT_NAME,
                    'LID'              => 's2',
                    'LANGUAGE_ID'      => 'ru',
                    'SITE_TEMPLATE_ID' => '',
                    'ACTIVE'           => 'Y',
                    'EMAIL_FROM'       => '#DEFAULT_EMAIL_FROM#',
                    'EMAIL_TO'         => '#EMAIL_TO#',
                    'CC'               => '',
                    'SUBJECT'          => 'Аукцион: регистрация',
                    'BODY_TYPE'        => 'html',
                    'MESSAGE'          => "<?php  
                    EventMessageThemeCompiler::includeComponent('email.dispatch:user.pb_signup', '', [
                    'user_id' => #USER_ID#,
                    'auction_id' => #ACTION_ID#,
                    ]); ?>",
                ],
            ]
        );

        EventMessageSiteTable::add(
            [
                'fields' => [
                    'EVENT_MESSAGE_ID' => $resultMessage->getId(),
                    'SITE_ID'          => 's2',
                ],
            ]
        );

        $resultType = EventTypeTable::add(
            [
                'fields' => [
                    'LID'         => 'en',
                    'EVENT_NAME'  => self::EVENT_NAME,
                    'NAME'        => 'Auction: registration',
                    'DESCRIPTION' => "#EMAIL_TO# - To (email)\n
                                        #USER_ID# - User id
                                        #ACTION_ID# - Идентификатор
                                        ",
                ],
            ]
        );
        if (!$resultType->isSuccess()) {
            throw new MigrationException(
                'Ошибка при добавлении типа шаблона: '
                . implode(', ', $resultType->getErrorMessages())
            );
        };

        $resultMessage = EventMessageTable::add(
            [
                'fields' => [
                    'EVENT_NAME'       => self::EVENT_NAME,
                    'LID'              => 's1',
                    'LANGUAGE_ID'      => 'en',
                    'SITE_TEMPLATE_ID' => '',
                    'ACTIVE'           => 'Y',
                    'EMAIL_FROM'       => '#DEFAULT_EMAIL_FROM#',
                    'EMAIL_TO'         => '#EMAIL_TO#',
                    'CC'               => '',
                    'SUBJECT'          => 'Auction: registration',
                    'BODY_TYPE'        => 'html',
                    'MESSAGE'          => "<?php 
                    EventMessageThemeCompiler::includeComponent('email.dispatch:user.pb_signup', '', [
                    'user_id' => #USER_ID#,
                    'auction_id' => #ACTION_ID#,
                    ]); ?>",
                ],
            ]
        );

        EventMessageSiteTable::add(
            [
                'fields' => [
                    'EVENT_MESSAGE_ID' => $resultMessage->getId(),
                    'SITE_ID'          => 's1',
                ],
            ]
        );
    }

    /**
     * Reverse the migration.
     *
     * @throws \Exception
     * @return mixed
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
