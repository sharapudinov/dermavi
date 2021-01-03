<?php

namespace App\EventHandlers;

use App\Table\AuctionUtmLogsTable;
use Bitrix\Main\Context;
use Bitrix\Main\Type\DateTime;
use CUser;

/**
 * Хранение логов utm меток аукциона
 *
 * Class UtmAuctionLogHandlers
 * @package App\EventHandlers
 */
class UtmAuctionLogHandlers
{
    const UNIQUE_UTM_KEY  = 'utm_source';
    const UTM_SESSION_KEY = 'auction_utm_key';

    // необходимые utm метки, когда мы начинаем логировать
    protected static $needUtm = [
        'utm_source=',
        'utm_medium=',
    ];

    public function saveLogs(): void
    {
        if (Context::getCurrent()->getRequest()->isAdminSection()) {
            return;
        }

        $query     = Context::getCurrent()->getRequest()->getQueryList()->toArray();
        $uri       = Context::getCurrent()->getRequest()->getRequestUri();
        $uniqueUtm = $query[self::UNIQUE_UTM_KEY];

        if (self::isNeedUtm()) {
            //сохраним метки в сессиях, что бы не потярять если пользователь не авторизуется
            self::saveUtm(
                self::prepareUtm(
                    $uri,
                    $uniqueUtm
                )
            );
        }

        if (!CUser::IsAuthorized()) {
            return;
        }


        if (!$savedUtm = self::getSavedUtm()) {
            return;
        }

        $utmLog = array_merge(self::getUtmLog($savedUtm['UNIQUE_UTM']), $savedUtm);

        if ($id = $utmLog['ID']) {
            unset($utmLog['ID']);
            AuctionUtmLogsTable::update($id, $utmLog);

            return;
        }

        AuctionUtmLogsTable::add($utmLog);

        self::clearSavedUtm();
    }

    /**
     * Проверим, что все utm метки присутствуют
     * @return bool
     */
    protected static function isNeedUtm(): bool
    {
        $uri      = Context::getCurrent()->getRequest()->getRequestUri();
        $utmStack = self::$needUtm;

        foreach (self::$needUtm as $i => $utm) {
            if (strpos($uri, $utm) !== false) {
                unset($utmStack[$i]);
            }
        }

        return empty($utmStack);
    }

    /**
     * @param string $uri
     * @param string $utm
     *
     * @return string[]
     */
    protected static function prepareUtm(string $uri, string $utm): array
    {
        return [
            'URL'        => $uri,
            'UNIQUE_UTM' => $utm,
        ];
    }

    /**
     * @param array $utm
     */
    protected static function saveUtm(array $utm): void
    {
        $_SESSION[self::UTM_SESSION_KEY] = $utm;
    }

    /**
     *
     */
    protected static function clearSavedUtm(): void
    {
        unset($_SESSION[self::UTM_SESSION_KEY]);
    }

    /**
     * @return array|null
     */
    protected static function getSavedUtm(): ?array
    {
        return $_SESSION[self::UTM_SESSION_KEY];
    }

    /**
     * Возвращает существующий или массив для нового лога
     *
     * @param string $uniqueUtm
     *
     * @return array
     */
    protected static function getUtmLog(string $uniqueUtm): array
    {
        global $USER;

        $row = AuctionUtmLogsTable::getRow(
            [
                'filter' => [
                    '=UNIQUE_UTM' => $uniqueUtm,
                    '=USER_ID'    => $USER->GetID(),

                ],
            ]
        );

        if ($row['ID']) {
            $row['DATE_UPDATE'] = new DateTime();

            return $row;
        }

        return [
            'USER_ID'     => $USER->GetID(),
            'UNIQUE_UTM'  => $uniqueUtm,
            'DATE_CREATE' => new DateTime(),
            'DATE_UPDATE' => new DateTime(),
        ];
    }
}
