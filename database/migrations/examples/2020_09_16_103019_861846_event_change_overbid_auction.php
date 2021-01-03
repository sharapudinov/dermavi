<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Bitrix\Main\Mail\Internal\EventMessageSiteTable;
use Bitrix\Main\Mail\Internal\EventMessageTable;
use Bitrix\Main\Mail\Internal\EventTypeTable;

class EventChangeOverbidAuction20200916103019861846 extends BitrixMigration
{
    /** @var string Символьный код события */
    private const EVENT_NAME = 'AUCTION_USER_OVERBID';

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
                    'NAME'        => 'Аукцион: ставка перебита',
                    'DESCRIPTION' => "#EMAIL_TO# - Кому (email)\n
                                        #USER_ID# - Идетификатор пользователя
                                        #SIMPLE_AUCTION# - Обычный аукцион
                                        #LOT_ID# - Идентификатор
                                        #YOUR_BID_ID# - Идентификатор
                                        #NEW_BID_ID# - Идентификатор
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
                    'SUBJECT'          => 'Аукцион: ставка перебита',
                    'BODY_TYPE'        => 'html',
                    'MESSAGE'          => "<?php  
                    EventMessageThemeCompiler::includeComponent('email.dispatch:auction.user.overbid', '', [
                    'user_id' => #USER_ID#,
                    'lot_id' => #LOT_ID#,
                    'simple_auction' =>#SIMPLE_AUCTION#,
                    'your_bid_id' => #YOUR_BID_ID#,
                    'new_bid_id' => #NEW_BID_ID#
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
                    'NAME'        => 'Auction: you have been overbid',
                    'DESCRIPTION' => "#EMAIL_TO# - To (email)\n
                                        #USER_ID# - User id
                                        #SIMPLE_AUCTION# - Обычный аукцион
                                        #LOT_ID# - Идентификатор
                                        #YOUR_BID_ID# - Идентификатор
                                        #NEW_BID_ID# - Идентификатор
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
                    'SUBJECT'          => 'Auction: you have been overbid',
                    'BODY_TYPE'        => 'html',
                    'MESSAGE'          => "<?php 
                    EventMessageThemeCompiler::includeComponent('email.dispatch:auction.user.overbid', '', [
                    'user_id' => #USER_ID#,
                    'lot_id' => #LOT_ID#,
                    'simple_auction' =>#SIMPLE_AUCTION#,
                    'your_bid_id' => #YOUR_BID_ID#,
                    'new_bid_id' => #NEW_BID_ID#
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
