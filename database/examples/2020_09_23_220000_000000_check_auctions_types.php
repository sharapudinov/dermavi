<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Bitrix\Main\Loader;

/**
 * Class CheckAuctionsTypes20200923220000000000
 * ALRSUP-526: требуется проверить наличие в среде некорректно созданных лотов и аукционов.
 * В случае обнаружения таковых на тестовых стендах - исправить Параметры ставки на Закрытую.
 * В случае обнаружения таковых на проде - сообщить ответственному лицу со стороны Заказчика.
 */
class CheckAuctionsTypes20200923220000000000 extends BitrixMigration
{
    protected const IBLOCK_TYPE_AUCTION = 'auctions';

    protected const IBLOCK_CODE_AUCTION = 'auction';

    protected const IBLOCK_CODE_AUCTION_LOT = 'auction_lot';

    protected const IBLOCK_TYPE_AUCTION_PB = 'auctions_pb';

    protected const IBLOCK_CODE_AUCTION_PB = 'auction_pb';

    protected const IBLOCK_CODE_AUCTION_PB_LOT = 'auction_pb_lot';

    /** @var bool */
    private $autofix;

    /** @var array */
    private $enumProps;

    /**
     * Run the migration.
     *
     * @return void
     * @throws \Exception
     */
    public function up()
    {
        $this->checkAuctions();
    }

    /**
     * Reverse the migration.
     *
     * @return void
     */
    public function down()
    {
        // Ничего не делаем
    }

    /**
     * @throws \Arrilot\BitrixMigrations\Exceptions\MigrationException
     * @throws \Bitrix\Main\LoaderException
     */
    private function checkAuctions(): void
    {
        Loader::includeModule('iblock');
        $filePrefix = date('Y-m-d_H-i-s') . '_';

        $auctionIBlockId = $this->getIblockIdByCode(static::IBLOCK_CODE_AUCTION, static::IBLOCK_TYPE_AUCTION);
        $auctionLotIBlockId = $this->getIblockIdByCode(static::IBLOCK_CODE_AUCTION_LOT, static::IBLOCK_TYPE_AUCTION);
        $auctionPbIBlockId = $this->getIblockIdByCode(static::IBLOCK_CODE_AUCTION_PB, static::IBLOCK_TYPE_AUCTION_PB);
        $auctionPbLotIBlockId = $this->getIblockIdByCode(static::IBLOCK_CODE_AUCTION_PB_LOT, static::IBLOCK_TYPE_AUCTION_PB);

        // Аукционы
        $invalidAuctionLotIdList = [];

        $invalidTypeAuctionLots = $this->getInvalidTypeAuctionLots($auctionLotIBlockId);
        if ($invalidTypeAuctionLots) {
            if ($this->isAutofixAllowed()) {
                foreach ($invalidTypeAuctionLots as &$data) {
                    if ($this->fixInvalidTypeAuctionLot($data)) {
                        $data['CSV_FIELDS']['FIXED'] = 'Y';
                    }
                }
                unset($data);
            }

            $this->saveScvFile($invalidTypeAuctionLots, $filePrefix . 'invalid_type_auction_lot.csv');

            foreach ($invalidTypeAuctionLots as $data) {
                if ($data['CSV_FIELDS']['FIXED'] === 'Y') {
                    continue;
                }
                $invalidAuctionLotIdList[$data['ID']] = $data['ID'];
            }
        }

        $invalidPriceAuctionLots = $this->getInvalidPriceAuctionLots($auctionLotIBlockId);
        if ($invalidPriceAuctionLots) {
            if ($this->isAutofixAllowed()) {
                foreach ($invalidPriceAuctionLots as &$data) {
                    if ($this->fixInvalidPriceAuctionLot($data)) {
                        $data['CSV_FIELDS']['FIXED'] = 'Y';
                    }
                }
                unset($data);
            }

            $this->saveScvFile($invalidPriceAuctionLots, $filePrefix . 'invalid_price_auction_lot.csv');

            foreach ($invalidPriceAuctionLots as $data) {
                if ($data['CSV_FIELDS']['FIXED'] === 'Y') {
                    continue;
                }
                $invalidAuctionLotIdList[$data['ID']] = $data['ID'];
            }
        }

        if ($invalidAuctionLotIdList) {
            $invalidAuctions = $this->getInvalidAuctions($auctionIBlockId, array_keys($invalidAuctionLotIdList));
            if ($invalidAuctions) {
                $this->saveScvFile($invalidAuctions, $filePrefix . 'invalid_auction.csv');
            }
        }

        // Аукционы PB
        $invalidAuctionPbLotIdList = [];

        $invalidTypeAuctionPbLots = $this->getInvalidTypeAuctionLots($auctionPbLotIBlockId);
        if ($invalidTypeAuctionPbLots) {
            if ($this->isAutofixAllowed()) {
                foreach ($invalidTypeAuctionPbLots as &$data) {
                    if ($this->fixInvalidTypeAuctionLot($data)) {
                        $data['CSV_FIELDS']['FIXED'] = 'Y';
                    }
                }
                unset($data);
            }

            $this->saveScvFile($invalidTypeAuctionPbLots, $filePrefix . 'invalid_type_auction_pb_lot.csv');

            foreach ($invalidTypeAuctionPbLots as $data) {
                if ($data['CSV_FIELDS']['FIXED'] === 'Y') {
                    continue;
                }
                $invalidAuctionPbLotIdList[$data['ID']] = $data['ID'];
            }
        }

        $invalidPriceAuctionPbLots = $this->getInvalidPriceAuctionLots($auctionPbLotIBlockId);
        if ($invalidPriceAuctionPbLots) {
            if ($this->isAutofixAllowed()) {
                foreach ($invalidPriceAuctionPbLots as &$data) {
                    if ($this->fixInvalidPriceAuctionLot($data)) {
                        $data['CSV_FIELDS']['FIXED'] = 'Y';
                    }
                }
                unset($data);
            }

            $this->saveScvFile($invalidPriceAuctionPbLots, $filePrefix . 'invalid_price_auction_pb_lot.csv');

            foreach ($invalidPriceAuctionPbLots as $data) {
                if ($data['CSV_FIELDS']['FIXED'] === 'Y') {
                    continue;
                }
                $invalidAuctionPbLotIdList[$data['ID']] = $data['ID'];
            }
        }

        if ($invalidAuctionPbLotIdList) {
            $invalidAuctions = $this->getInvalidAuctions($auctionPbIBlockId, array_keys($invalidAuctionPbLotIdList));
            if ($invalidAuctions) {
                $this->saveScvFile($invalidAuctions, $filePrefix . 'invalid_auction_pb.csv');
            }
        }
    }

    /**
     * @param int $iblockId
     * @return array
     */
    private function getInvalidTypeAuctionLots(int $iblockId): array
    {
        $result = [];
        $iterator = CIBlockElement::GetList(
            [
                'ID' => 'ASC',
            ],
            [
                'IBLOCK_ID' => $iblockId,
                [
                    'LOGIC' => 'OR',
                    [
                        '=PROPERTY_WAY_OF_CONDUCTING_VALUE' => 'Сбор КП',
                        '=PROPERTY_BETTING_PARAMS_VALUE' => 'Открытая',
                    ],
                    [
                        '=PROPERTY_WAY_OF_CONDUCTING_VALUE' => false,
                    ],
                    [
                        '=PROPERTY_BETTING_PARAMS_VALUE' => false,
                    ],
                ]
            ],
            false,
            false,
            [
                'ID', 'NAME', 'CODE', 'IBLOCK_ID',
                'PROPERTY_BETTING_PARAMS',
                'PROPERTY_WAY_OF_CONDUCTING',
            ]
        );
        while ($item = $iterator->Fetch()) {
            $result[$item['ID']] = [
                'ID' => $item['ID'],
                'IBLOCK_ID' => $item['IBLOCK_ID'],
                'CSV_FIELDS' => [
                    'ELEMENT_ID' => $item['ID'],
                    'NAME' => $item['NAME'],
                    'CODE' => $item['CODE'],
                    'WAY_OF_CONDUCTING' => $item['PROPERTY_WAY_OF_CONDUCTING_VALUE'],
                    'BETTING_PARAMS' => $item['PROPERTY_BETTING_PARAMS_VALUE'],
                    'FIXED' => 'N',
                ]
            ];
        }

        return $result;
    }

    /**
     * @param int $iblockId
     * @return array
     */
    private function getInvalidPriceAuctionLots(int $iblockId): array
    {
        $result = [];
        $iterator = CIBlockElement::GetList(
            [
                'ID' => 'ASC',
            ],
            [
                'IBLOCK_ID' => $iblockId,
                [
                    'LOGIC' => 'OR',
                    [
                        '=PROPERTY_WAY_OF_CONDUCTING_VALUE' => 'Сбор КП',
                        '=PROPERTY_RESERVE_PRICE' => false,
                    ],
                    [
                        '=PROPERTY_WAY_OF_CONDUCTING_VALUE' => 'Сбор КП',
                        '!=PROPERTY_STARTING_PRICE' => false,
                    ],
                    [
                        '=PROPERTY_WAY_OF_CONDUCTING_VALUE' => 'Аукцион',
                        '=PROPERTY_STARTING_PRICE' => false,
                    ],
                    [
                        '=PROPERTY_WAY_OF_CONDUCTING_VALUE' => 'Аукцион',
                        '!=PROPERTY_RESERVE_PRICE' => false,
                    ],
                ]
            ],
            false,
            false,
            [
                'ID', 'NAME', 'CODE', 'IBLOCK_ID',
                'PROPERTY_WAY_OF_CONDUCTING',
                'PROPERTY_RESERVE_PRICE',
                'PROPERTY_STARTING_PRICE',
            ]
        );
        while ($item = $iterator->Fetch()) {
            $result[$item['ID']] = [
                'ID' => $item['ID'],
                'IBLOCK_ID' => $item['IBLOCK_ID'],
                'WAY_OF_CONDUCTING' => $item['PROPERTY_WAY_OF_CONDUCTING_VALUE'],
                'RESERVE_PRICE' => (int)$item['PROPERTY_RESERVE_PRICE_VALUE'],
                'STARTING_PRICE' => (int)$item['PROPERTY_STARTING_PRICE_VALUE'],
                'CSV_FIELDS' => [
                    'ELEMENT_ID' => $item['ID'],
                    'NAME' => $item['NAME'],
                    'CODE' => $item['CODE'],
                    'WAY_OF_CONDUCTING' => $item['PROPERTY_WAY_OF_CONDUCTING_VALUE'],
                    'RESERVE_PRICE' => $item['PROPERTY_RESERVE_PRICE_VALUE'],
                    'STARTING_PRICE' => $item['PROPERTY_STARTING_PRICE_VALUE'],
                    'FIXED' => 'N',
                ],
            ];
        }

        return $result;
    }

    /**
     * @param int $iblockId
     * @param array $lotIdList
     * @return array
     */
    private function getInvalidAuctions(int $iblockId, array $lotIdList): array
    {
        $result = [];
        if (!$lotIdList) {
            return $result;
        }

        $iterator = CIBlockElement::GetList(
            [
                'ID' => 'ASC',
            ],
            [
                'IBLOCK_ID' => $iblockId,
                '=PROPERTY_LOTS' => $lotIdList,
            ],
            false,
            false,
            [
                'ID', 'NAME', 'CODE', 'IBLOCK_ID',
                'PROPERTY_LOTS',
            ]
        );
        while ($item = $iterator->Fetch()) {
            $result[$item['ID']] = [
                'ID' => $item['ID'],
                'IBLOCK_ID' => $item['IBLOCK_ID'],
                'CSV_FIELDS' => [
                    'ELEMENT_ID' => $item['ID'],
                    'NAME' => $item['NAME'],
                    'CODE' => $item['CODE'],
                    'LOTS' => implode(', ', array_intersect($item['PROPERTY_LOTS_VALUE'], $lotIdList)),
                    'FIXED' => 'N',
                ]
            ];
        }

        return $result;
    }

    /**
     * @return bool
     */
    private function isAutofixAllowed(): bool
    {
        if ($this->autofix === null) {
            // Для подстраховки считаем, что все что не dev - это прод, и там автофикс не делается
            $this->autofix = env('APP_ENV') === 'dev';
        }

        return $this->autofix;
    }

    /**
     * @param int $iblockId
     * @param string $propCode
     * @param string $value
     * @return int
     */
    private function getEnumValueId(int $iblockId, string $propCode, string $value): int
    {
        if (!isset($this->enumProps[$iblockId][$propCode])) {
            $list = [];
            $iterator = CIBlockPropertyEnum::GetList(
                [],
                [
                    'IBLOCK_ID' => $iblockId,
                    'CODE' => $propCode
                ]
            );
            while ($item = $iterator->Fetch()) {
                $list[$item['VALUE']] = $item['ID'];
            }
            $this->enumProps[$iblockId][$propCode] = $list;
        }

        return (int)($this->enumProps[$iblockId][$propCode][$value] ?? 0);
    }

    /**
     * @param array $data
     * @return bool
     */
    private function fixInvalidTypeAuctionLot(array $data): bool
    {
        $item = CIBlockElement::GetList(
            [],
            [
                'IBLOCK_ID' => $data['IBLOCK_ID'],
                'ID' => $data['ID'],
            ],
            false,
            false,
            [
                'ID', 'IBLOCK_ID',
                'PROPERTY_BETTING_PARAMS',
                'PROPERTY_WAY_OF_CONDUCTING',
            ]
        )->Fetch();
        if (!$item) {
            return false;
        }

        $enumId = 0;
        if (
            $item['PROPERTY_BETTING_PARAMS_VALUE'] === 'Открытая'
            &&
            $item['PROPERTY_WAY_OF_CONDUCTING_VALUE'] === 'Сбор КП'
        ) {
            $enumId = $this->getEnumValueId((int)$data['IBLOCK_ID'], 'BETTING_PARAMS', 'Закрытая');
        }

        if ($enumId <= 0) {
            return false;
        }

        CIBlockElement::SetPropertyValuesEx(
            $data['ID'],
            $data['IBLOCK_ID'],
            [
                'BETTING_PARAMS' => $enumId,
            ]
        );

        return true;
    }

    /**
     * @param array $data
     * @return bool
     */
    private function fixInvalidPriceAuctionLot(array $data): bool
    {
        $item = CIBlockElement::GetList(
            [],
            [
                'IBLOCK_ID' => $data['IBLOCK_ID'],
                'ID' => $data['ID'],
            ],
            false,
            false,
            [
                'ID', 'IBLOCK_ID',
                'PROPERTY_WAY_OF_CONDUCTING',
                'PROPERTY_RESERVE_PRICE',
                'PROPERTY_STARTING_PRICE',
            ]
        )->Fetch();
        if (!$item) {
            return false;
        }

        $updateProps = [];

        $reservePrice = (int)$item['PROPERTY_RESERVE_PRICE_VALUE'];
        $startingPrice = (int)$item['PROPERTY_STARTING_PRICE_VALUE'];
        if ($item['PROPERTY_WAY_OF_CONDUCTING_VALUE'] === 'Сбор КП') {
            if ($startingPrice > 0) {
                if ($reservePrice <= 0) {
                    $updateProps['RESERVE_PRICE'] = $startingPrice;
                    $updateProps['STARTING_PRICE'] = '';
                } else {
                    // Если задана начальная цена и резервная, то удаляем последнюю
                    $updateProps['STARTING_PRICE'] = '';
                }
            }
        } elseif ($item['PROPERTY_WAY_OF_CONDUCTING_VALUE'] === 'Аукцион' && $reservePrice > 0) {
            if ($startingPrice <= 0) {
                $updateProps['RESERVE_PRICE'] = '';
                $updateProps['STARTING_PRICE'] = $reservePrice;
            } else {
                // Если задана резервная цена и начальная, то удаляем последнюю
                $updateProps['RESERVE_PRICE'] = '';
            }
        }

        if (!$updateProps) {
            return false;
        }

        CIBlockElement::SetPropertyValuesEx(
            $data['ID'],
            $data['IBLOCK_ID'],
            $updateProps
        );

        return true;
    }

    /**
     * @param array $list
     * @param string $fileName
     */
    private function saveScvFile(array $list, string $fileName): void
    {
        if (!$list) {
            return;
        }

        $dirAbs = $_SERVER['DOCUMENT_ROOT'] . '/upload/ALRSUP-526/';
        CheckDirPath($dirAbs);
        $rs = fopen($dirAbs . '/' . $fileName, 'wb');

        $delimiter = ';';
        $isFirst = true;
        foreach ($list as $item) {
            if ($isFirst) {
                fputcsv($rs, $this->convertCharset(array_keys($item['CSV_FIELDS'])), $delimiter);
                $isFirst = false;
            }

            fputcsv($rs, $this->convertCharset($item['CSV_FIELDS']), $delimiter);
        }
        fclose($rs);
    }

    /**
     * @param array $fields
     * @return array|bool|SplFixedArray|string
     */
    private function convertCharset(array $fields)
    {
        /** @global CMain $APPLICATION */
        global $APPLICATION;

        return $APPLICATION->ConvertCharsetArray($fields, 'UTF-8', 'Windows-1251');
    }
}
