<?php

namespace App\EventHandlers;

use App\Models\Auctions\Auction;
use App\Models\Auctions\AuctionPB;
use App\Models\Auctions\BaseAuction;
use App\Models\HL\LoggingEmailModel;
use Bitrix\Main\Event;
use Bitrix\Main\Type\DateTime;
use \CUserTypeEntity;
use \CUserFieldEnum;

class LoggingEmailHandler
{
    /**
     * @param Event $event
     */
    public static function loggingMail(Event $event): void
    {
        $parameters = current($event->getParameters());
        $eventId    = $parameters['HEADER']['X-EVENT_NAME'];

        if (!LoggingEmailModel::isLoginEvent($eventId)) {
            return;
        }

        $isManager = self::isManager($parameters['TO']);

        LoggingEmailModel::create(
            [
                'UF_RECIPIENT'  => self::getIdByXmlId(
                    $isManager
                        ? LoggingEmailModel::XML_ID_MANAGER
                        : LoggingEmailModel::XML_ID_CLIENT
                ),
                'UF_EMAIL'      => $parameters['TO'],
                'UF_DATE_SEND'  => new DateTime(),
                'UF_EVENT_ID'   => $eventId,
                'UF_EVENT_NAME' => $parameters['SUBJECT'],
                'UF_BODY'       => $parameters['BODY'],
            ]
        );
    }

    /**
     * Возвращает id значения менеджер/клиент
     *
     * @param string $xmlId
     *
     * @return int
     */
    protected static function getIdByXmlId(string $xmlId): int
    {
        $field = CUserTypeEntity::GetList(
            [],
            [
                'FIELD_NAME' => 'UF_RECIPIENT',
            ]
        )->Fetch();

        $enum = CUserFieldEnum::GetList(
            ['SORT' => 'ASC'],
            [
                'USER_FIELD_ID' => $field['ID'],
                'XML_ID'        => $xmlId,
            ]
        )->Fetch();

        return $enum['ID'];
    }

    /**
     * Является ли получатель письма менеджером
     *
     * @param string $email
     *
     * @return bool
     */
    protected static function isManager(string $email): bool
    {
        $by    = ['EMAIL' => 'ASC'];
        $order = 'asc';
        $user  = \CUser::GetList(
            $by,
            $order,
            [
                'EMAIL' => $email,
            ]
        )->Fetch();

        if (!$user['ID'])
        {
            return false;
        }

        $action = Auction::filter(
            [
                'PROPERTY_AUCTION_MANAGER_RU' => $user['ID'],
            ]
        )->limit(1)->first();

        if ($action instanceof BaseAuction) {
            return true;
        }

        $action = AuctionPB::filter(
            [
                'PROPERTY_AUCTION_MANAGER_RU' => $user['ID'],

            ]
        )->limit(1)->first();

        if ($action instanceof BaseAuction) {
            return true;
        }

        return false;
    }
}
