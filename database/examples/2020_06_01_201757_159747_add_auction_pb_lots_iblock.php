<?php

use App\Models\Catalog\Catalog;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;

/**
 * Миграция для создания инфоблока "Лот аукциона PB"
 * Class AddAuctionPbLotsIblock20200601201757159747
 */
class AddAuctionPbLotsIblock20200601201757159747 extends BitrixMigration
{
    /** @var string $iblockCode - Символьный код ИБ */
    private $iblockCode = 'auction_pb_lot';

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        /** @var int $auctionLotIblockId - Идентификатор инфоблока "Лот аукциона" */
        $auctionLotIblockId = (new CIBlock)->Add([
            'NAME' => 'Лот аукциона',
            'CODE' => $this->iblockCode,
            'VERSION' => 2,
            'IBLOCK_TYPE_ID' => 'auctions_pb',
            'DETAIL_PAGE_URL' => '#SITE_DIR#/auctions_pb/lots/#CODE#/',
            'SITE_ID' => ['s1', 's2', 's3']
        ]);

        /** Устанвливаем настройки полей инфоблока */
        $fields = CIBlock::getFields($auctionLotIblockId);
        $fields['NAME']['DEFAULT_VALUE'] = 'Название по-умолчанию (изменится при создании)';
        CIBlock::setFields($auctionLotIblockId, $fields);

        (new CIBlockProperty)->Add([
            'NAME' => 'Название лота (рус)',
            'CODE' => 'NAME_RU',
            'PROPERTY_TYPE' => 'S',
            'SORT' => '496',
            'IBLOCK_ID' => $auctionLotIblockId
        ]);

        (new CIBlockProperty)->Add([
            'NAME' => 'Название лота (англ)',
            'CODE' => 'NAME_EN',
            'PROPERTY_TYPE' => 'S',
            'SORT' => '497',
            'IBLOCK_ID' => $auctionLotIblockId
        ]);

        (new CIBlockProperty)->Add([
            'NAME' => 'Название лота (кит)',
            'CODE' => 'NAME_CN',
            'PROPERTY_TYPE' => 'S',
            'SORT' => '498',
            'IBLOCK_ID' => $auctionLotIblockId
        ]);

        (new CIBlockProperty)->Add([
            'NAME' => 'Интерфейс для прикрепления товаров к лоту',
            'CODE' => 'DIAMONDS_ATTACHING_INTERFACE',
            'PROPERTY_TYPE' => 'S',
            'USER_TYPE' => 'Link',
            'SORT' => '499',
            'HINT' => 'Доступен только после создания лота. Являет собой расширенный и более удобный инструмент для выбора бриллиантов данного лота',
            'IBLOCK_ID' => $auctionLotIblockId
        ]);

        (new CIBlockProperty)->Add([
            'NAME' => 'Товары',
            'CODE' => 'DIAMONDS',
            'PROPERTY_TYPE' => 'E',
            'MULTIPLE' => 'Y',
            'SORT' => '500',
            'IBLOCK_ID' => $auctionLotIblockId,
            'LINK_IBLOCK_ID' => Diamond::iblockId()
        ]);

        //todo СПРОСИТЬ У САШИ НУЖНО ЛИ ЭТО СВОЙСТВО
        /*(new CIBlockProperty)->Add([
            'NAME' => 'Обновить аукционные камни',
            'CODE' => 'DIAMOND_UPDATE_AUCTION_LINK',
            'PROPERTY_TYPE' => 'S',
            'USER_TYPE' => 'Link',
            'SORT' => '500',
            'HINT' => 'Обновляет аукционное камни',
            'IBLOCK_ID' => $auctionLotIblockId
        ]);*/

        (new CIBlockProperty)->Add([
            'NAME' => 'Способ проведения',
            'CODE' => 'WAY_OF_CONDUCTING',
            'PROPERTY_TYPE' => 'L',
            'IS_REQUIRED' => 'Y',
            'SORT' => '501',
            'IBLOCK_ID' => $auctionLotIblockId,
            'DEFAULT_VALUE' => 'Аукцион',
            'VALUES' => [
                ['VALUE' => 'Аукцион', 'DEFAULT' => 'Y'],
                //['VALUE' => 'Сбор КП']
            ],
        ]);

        (new CIBlockProperty)->Add([
            'NAME' => 'Начальная цена (Доллары США)',
            'CODE' => 'STARTING_PRICE',
            'PROPERTY_TYPE' => 'S',
            'SORT' => '502',
            'HINT' => 'Если способ проведения - Аукцион',
            'IBLOCK_ID' => $auctionLotIblockId
        ]);

        (new CIBlockProperty)->Add([
            'NAME' => 'Резервная цена (Доллары США)',
            'CODE' => 'RESERVE_PRICE',
            'PROPERTY_TYPE' => 'S',
            'SORT' => '503',
            'HINT' => 'Если способ проведения - Сбор КП',
            'IBLOCK_ID' => $auctionLotIblockId
        ]);

        (new CIBlockProperty)->Add([
            'NAME' => 'Параметры ставки',
            'CODE' => 'BETTING_PARAMS',
            'PROPERTY_TYPE' => 'L',
            'IS_REQUIRED' => 'Y',
            'SORT' => '504',
            'IBLOCK_ID' => $auctionLotIblockId,
            'DEFAULT_VALUE' => 'Открытая',
            'VALUES' => [
                ['VALUE' => 'Открытая', 'DEFAULT' => 'Y'],
                //['VALUE' => 'Закрытая']
            ],
        ]);

        (new CIBlockProperty)->Add([
            'NAME' => 'Максимальная ставка на текущий момент',
            'CODE' => 'MAXIMUM_BET_AT_THIS_MOMENT',
            'PROPERTY_TYPE' => 'S',
            'SORT' => '505',
            'IBLOCK_ID' => $auctionLotIblockId
        ]);

        (new CIBlockProperty)->Add([
            'NAME' => 'Пользователи, сделавшие максимальную ставку',
            'CODE' => 'USERS_MADE_MAXIMUM_BET',
            'PROPERTY_TYPE' => 'S',
            'USER_TYPE' => 'UserID',
            'MULTIPLE' => 'Y',
            'SORT' => '506',
            'HINT' => 'Более одного пользователя только в случае, если два или более пользователей сделали одинаково высокие ставки',
            'IBLOCK_ID' => $auctionLotIblockId
        ]);

        (new CIBlockProperty)->Add([
            'NAME' => 'Запросы показа бриллиантов',
            'CODE' => 'VIEWING_REQUESTS',
            'PROPERTY_TYPE' => 'S',
            'USER_TYPE' => 'Link',
            'MULTIPLE' => 'Y',
            'SORT' => '507',
            'IBLOCK_ID' => $auctionLotIblockId
        ]);

        (new CIBlockProperty)->Add([
            'NAME' => 'Переторжка',
            'CODE' => 'IS_REBIDDING',
            'PROPERTY_TYPE' => 'L',
            'LIST_TYPE' => 'C',
            'SORT' => '508',
            'IBLOCK_ID' => $auctionLotIblockId,
            'VALUES' => [
                ['VALUE' => 'Y']
            ]
        ]);

        (new CIBlockProperty)->Add([
            'NAME' => 'Трек ставок лота',
            'CODE' => 'LOT_BETS_TRACK',
            'PROPERTY_TYPE' => 'S',
            'USER_TYPE' => 'LotsBetsTrack',
            'MULTIPLE' => 'Y',
            'SORT' => '509',
            'IBLOCK_ID' => $auctionLotIblockId
        ]);

        (new CIBlockProperty)->Add([
            'NAME' => 'Принадлежит аукциону',
            'CODE' => 'BELONGS_TO_AUCTION',
            'PROPERTY_TYPE' => 'S',
            'SORT' => '510',
            'IBLOCK_ID' => $auctionLotIblockId
        ]);

        (new CIBlockProperty)->Add([
            'NAME' => 'Сообщения о результатах аукциона разосланы',
            'CODE' => 'MESSAGES_ABOUT_RESULT_SENT',
            'PROPERTY_TYPE' => 'S',
            'SORT' => '511',
            'IBLOCK_ID' => $auctionLotIblockId
        ]);

        (new CIBlockProperty)->Add([
            'NAME' => 'Характеристики привязанных бриллиантов',
            'CODE' => 'ATTACHED_DIAMONDS_CHARACTERISTICS',
            'PROPERTY_TYPE' => 'S',
            'USER_TYPE' => 'Link',
            'SORT' => '512',
            'IBLOCK_ID' => $auctionLotIblockId
        ]);

        (new CIBlockProperty)->Add([
            'NAME' => 'Номер договора',
            'CODE' => 'CONTRACT_NUMBER',
            'PROPERTY_TYPE' => 'S',
            'SORT' => '513',
            'IBLOCK_ID' => $auctionLotIblockId
        ]);

        (new CIBlockProperty)->Add([
            'NAME' => 'Спецификация отгрузки',
            'CODE' => 'SHIPMENT_SPECIFICATION',
            'PROPERTY_TYPE' => 'S',
            'MULTIPLE' => 'Y',
            'SORT' => '514',
            'IBLOCK_ID' => $auctionLotIblockId
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
        $this->deleteIblockByCode($this->iblockCode);
    }
}
