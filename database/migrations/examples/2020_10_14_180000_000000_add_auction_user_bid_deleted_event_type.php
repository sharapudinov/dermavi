<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Bitrix\Main\Mail\Internal\EventMessageSiteTable;
use Bitrix\Main\Mail\Internal\EventMessageTable;
use Bitrix\Main\Mail\Internal\EventTypeTable;

class AddAuctionUserBidDeletedEventType20201014180000000000 extends BitrixMigration
{
    /** @var string Символьный код события */
    private const EVENT_NAME = 'AUCTION_USER_BID_DELETED';

    /**
     * Run the migration.
     *
     * @throws \Exception
     */
    public function up()
    {
        $resultType = EventTypeTable::add([
            'fields' => [
                'LID' => 'ru',
                'EVENT_NAME' => self::EVENT_NAME,
                'NAME' => 'Аукцион: ставка удалена',
                'DESCRIPTION' => "#EMAIL_TO# - Кому (email)\n
                                        #USER_ID# - Идетификатор пользователя
                                        #LOT_ID# - Идентификатор лота удаленной ставки
                                        #YOUR_BID_ID# - Идентификатор удаленной ставки
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
                'SUBJECT' => 'Аукцион: ставка удалена',
                'BODY_TYPE' => 'html',
                'MESSAGE' => "<?php  
                    EventMessageThemeCompiler::includeComponent('email.dispatch:auction.user.bid_delete', '', [
                    'user_id' => #USER_ID#,
                    'lot_id' => #LOT_ID#,
                    'your_bid_id' => #YOUR_BID_ID#,
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
                'NAME' => 'Auction: bid deleted',
                'DESCRIPTION' => "#EMAIL_TO# - To (email)\n
                                        #USER_ID# - Идетификатор пользователя
                                        #LOT_ID# - Идентификатор лота удаленной ставки
                                        #YOUR_BID_ID# - Идентификатор удаленной ставки
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
                'SUBJECT' => 'Auction: bid deleted',
                'BODY_TYPE' => 'html',
                'MESSAGE' => "<?php 
                    EventMessageThemeCompiler::includeComponent('email.dispatch:auction.user.bid_delete', '', [
                    'user_id' => #USER_ID#,
                    'lot_id' => #LOT_ID#,
                    'your_bid_id' => #YOUR_BID_ID#,
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
     * @throws \Exception
     */
    public function down()
    {
        $res = EventTypeTable::getList(['filter' => ['EVENT_NAME' => self::EVENT_NAME]]);
        while ($type = $res->fetch()) {
            EventTypeTable::delete($type['ID']);
        }

        $res = EventMessageTable::getList(['filter' => ['EVENT_NAME' => self::EVENT_NAME]]);
        while ($type = $res->fetch()) {
            EventMessageTable::delete($type['ID']);
        }
    }
}
