<?php

namespace App\Models\HL;

use App\Models\Auxiliary\HlD7Model;

/**
 *
 * Class LoggingEmailModel
 *
 * @package App\Models\HL
 */
class LoggingEmailModel extends HlD7Model
{
    const XML_ID_CLIENT  = 'client';
    const XML_ID_MANAGER = 'manager';

    /** @var string Название таблицы в БД */
    public const TABLE_CODE = 'adv_logging_email';

    /**
     * Возвращает класс таблицы
     *
     * @return string
     */
    public static function tableClass()
    {
        return highloadblock_class(self::TABLE_CODE);
    }

    /**
     * @return string
     */
    public function getRecipient(): string
    {
        return (string)$this['UF_RECIPIENT'];
    }

    /**
     * @param string $recipient
     *
     * @return LoggingEmailModel
     */
    public function setRecipient(string $recipient): LoggingEmailModel
    {
        $this['UF_RECIPIENT'] = $recipient;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return (string)$this['UF_EMAIL'];
    }

    /**
     * @param string $email
     *
     * @return LoggingEmailModel
     */
    public function setEmail(string $email): LoggingEmailModel
    {
        $this->fields['UF_EMAIL'] = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getDateSend(): string
    {
        return (string)$this['UF_DATE_SEND'];
    }

    /**
     * @param string $dateSend
     *
     * @return LoggingEmailModel
     */
    public function setDateSend(string $dateSend): LoggingEmailModel
    {
        $this['UF_DATE_SEND'] = $dateSend;

        return $this;
    }

    /**
     * @return string
     */
    public function getEventID(): string
    {
        return (string)$this['UF_EVENT_ID'];
    }

    /**
     * @param string $eventID
     *
     * @return LoggingEmailModel
     */
    public function setEventID(string $eventID): LoggingEmailModel
    {
        $this['UF_EVENT_ID'] = $eventID;

        return $this;
    }

    /**
     * @return string
     */
    public function getEventName(): string
    {
        return (string)$this['UF_EVENT_NAME'];
    }

    /**
     * @param string $eventName
     *
     * @return LoggingEmailModel
     */
    public function setEventName(string $eventName): LoggingEmailModel
    {
        $this['UF_EVENT_NAME'] = $eventName;

        return $this;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return (string)$this['UF_BODY'];
    }

    /**
     * @param string $body
     *
     * @return LoggingEmailModel
     */
    public function setBody(string $body): LoggingEmailModel
    {
        $this['UF_BODY'] = $body;

        return $this;
    }

    /**
     * @param string $eventId
     *
     * @return bool
     */
    public static function isLoginEvent(string $eventId): bool
    {
        return in_array(
            $eventId,
            [
                'VIEWING_REQUEST_PB',
                'VIEWING_REQUEST',
                'NOTIFY_USERS_ABOUT_NEW_AUCTION',
                'AUCTION_USER_SIGNUP',
                'SIGN_UP_USER',
                'AUCTION_USER_OVERBID',
                'AUCTION_USER_BID_CONFIRM',
                'AUCTION_RESULTS_USER_REBIDDING',
                'AUCTION_RESULTS_USER',
                'AUCTION_RESULTS_MANAGER_SUCCESS',
                'AUCTION_RESULTS_MANAGER_REBIDDING',
                'AUCTION_NEW_BIDS_WHILE_REBIDDING',
                'AUCTION_24_HOURS_LAST',
                'AUCTION_2_HOURS_LAST',
                'AUCTION_USER_BID_DELETED',
            ]
        );
    }

}
