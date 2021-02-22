<?php

use App\Models\Auctions\AuctionPBLot;
use App\Models\Auctions\AuctionPBRule;
use App\Models\Auctions\AuctionPBRuleFile;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;

/**
 * Миграция для создания инфоблока "Аукцион"
 * Class AddAuctionsPbIblock20200601204335138299
 */
class AddAuctionsPbIblock20200601204335138299 extends BitrixMigration
{
    /** @var string $iblockCode - Символьный код ИБ */
    private $iblockCode = 'auction_pb';

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        /** @var int $auctionIblockId - Идентификатор инфоблока "Лот аукциона" */
        $auctionIblockId = (new CIBlock)->Add([
            'NAME' => 'Аукцион',
            'CODE' => $this->iblockCode,
            'VERSION' => 2,
            'IBLOCK_TYPE_ID' => 'auctions_pb',
            'LIST_PAGE_URL' => '#SITE_DIR#/auctions_pb/',
            'DETAIL_PAGE_URL' => '#SITE_DIR#/auctions_pb/#CODE#/',
            'SITE_ID' => ['s1', 's2', 's3']
        ]);

        /** Устанвливаем настройки полей инфоблока */
        $fields = CIBlock::getFields($auctionIblockId);
        $fields['ACTIVE']['DEFAULT_VALUE'] = 'N';
        $fields['PREVIEW_PICTURE']['IS_REQUIRED'] = 'Y';
        $fields['CODE']['DEFAULT_VALUE']['UNIQUE'] = 'Y';
        $fields['NAME']['DEFAULT_VALUE'] = 'Название по-умолчанию (изменится при создании)';
        CIBlock::setFields($auctionIblockId, $fields);

        (new CIBlockProperty)->Add([
            'NAME' => 'Менеджер аукциона',
            'CODE' => 'AUCTION_MANAGER',
            'PROPERTY_TYPE' => 'S',
            'USER_TYPE' => 'UserID',
            'IS_REQUIRED' => 'Y',
            'SORT' => '497',
            'IBLOCK_ID' => $auctionIblockId
        ]);

        (new CIBlockProperty)->Add([
            'NAME' => 'Правило аукциона',
            'CODE' => 'AUCTION_RULE',
            'PROPERTY_TYPE' => 'E',
            'IS_REQUIRED' => 'Y',
            'SORT' => '498',
            'IBLOCK_ID' => $auctionIblockId,
            'LINK_IBLOCK_ID' => AuctionPBRule::iblockId()
        ]);

        (new CIBlockProperty)->Add([
            'NAME' => 'Файлы правил аукциона',
            'CODE' => 'AUCTION_RULES_FILES',
            'PROPERTY_TYPE' => 'E',
            'MULTIPLE' => 'Y',
            'SORT' => '499',
            'IBLOCK_ID' => $auctionIblockId,
            'LINK_IBLOCK_ID' => AuctionPBRuleFile::iblockId()
        ]);

        (new CIBlockProperty)->Add([
            'NAME' => 'Название (рус)',
            'CODE' => 'NAME_RU',
            'PROPERTY_TYPE' => 'S',
            'IS_REQUIRED' => 'Y',
            'SORT' => '500',
            'IBLOCK_ID' => $auctionIblockId
        ]);

        (new CIBlockProperty)->Add([
            'NAME' => 'Название (англ)',
            'CODE' => 'NAME_EN',
            'PROPERTY_TYPE' => 'S',
            'IS_REQUIRED' => 'Y',
            'SORT' => '501',
            'IBLOCK_ID' => $auctionIblockId
        ]);

        (new CIBlockProperty)->Add([
            'NAME' => 'Название (кит)',
            'CODE' => 'NAME_CN',
            'PROPERTY_TYPE' => 'S',
            'SORT' => '502',
            'IBLOCK_ID' => $auctionIblockId
        ]);

        (new CIBlockProperty)->Add([
            'NAME' => 'География (рус)',
            'CODE' => 'GEOGRAPHY_RU',
            'PROPERTY_TYPE' => 'S',
            'IS_REQUIRED' => 'Y',
            'SORT' => '505',
            'IBLOCK_ID' => $auctionIblockId
        ]);

        (new CIBlockProperty)->Add([
            'NAME' => 'География (англ)',
            'CODE' => 'GEOGRAPHY_EN',
            'PROPERTY_TYPE' => 'S',
            'IS_REQUIRED' => 'Y',
            'SORT' => '506',
            'IBLOCK_ID' => $auctionIblockId
        ]);

        (new CIBlockProperty)->Add([
            'NAME' => 'География (кит)',
            'CODE' => 'GEOGRAPHY_CN',
            'PROPERTY_TYPE' => 'S',
            'SORT' => '507',
            'IBLOCK_ID' => $auctionIblockId
        ]);

        (new CIBlockProperty)->Add([
            'NAME' => 'Лоты аукциона',
            'CODE' => 'LOTS',
            'PROPERTY_TYPE' => 'E',
            'MULTIPLE' => 'Y',
            'SORT' => '508',
            'IBLOCK_ID' => $auctionIblockId,
            'LINK_IBLOCK_ID' => AuctionPBLot::iblockId()
        ]);

        (new CIBlockProperty)->Add([
            'NAME' => 'Описание (рус)',
            'CODE' => 'DESCRIPTION_RU',
            'PROPERTY_TYPE' => 'S',
            'USER_TYPE' => 'HTML',
            'IS_REQUIRED' => 'Y',
            'SORT' => '509',
            'IBLOCK_ID' => $auctionIblockId
        ]);

        (new CIBlockProperty)->Add([
            'NAME' => 'Описание (англ)',
            'CODE' => 'DESCRIPTION_EN',
            'PROPERTY_TYPE' => 'S',
            'USER_TYPE' => 'HTML',
            'IS_REQUIRED' => 'Y',
            'SORT' => '510',
            'IBLOCK_ID' => $auctionIblockId
        ]);

        (new CIBlockProperty)->Add([
            'NAME' => 'Описание (кит)',
            'CODE' => 'DESCRIPTION_CN',
            'PROPERTY_TYPE' => 'S',
            'USER_TYPE' => 'HTML',
            'SORT' => '511',
            'IBLOCK_ID' => $auctionIblockId
        ]);

        (new CIBlockProperty)->Add([
            'NAME' => 'Пресс-релиз (рус)',
            'CODE' => 'PRESS_RELEASE_RU',
            'PROPERTY_TYPE' => 'S',
            'USER_TYPE' => 'HTML',
            'SORT' => '512',
            'IBLOCK_ID' => $auctionIblockId,
            'HINT' => 'После окончания аукциона выведется вместо описания'
        ]);

        (new CIBlockProperty)->Add([
            'NAME' => 'Пресс-релиз (англ)',
            'CODE' => 'PRESS_RELEASE_EN',
            'PROPERTY_TYPE' => 'S',
            'USER_TYPE' => 'HTML',
            'SORT' => '513',
            'IBLOCK_ID' => $auctionIblockId,
            'HINT' => 'После окончания аукциона выведется вместо описания'
        ]);

        (new CIBlockProperty)->Add([
            'NAME' => 'Пресс-релиз (кит)',
            'CODE' => 'PRESS_RELEASE_CN',
            'PROPERTY_TYPE' => 'S',
            'USER_TYPE' => 'HTML',
            'SORT' => '514',
            'IBLOCK_ID' => $auctionIblockId,
            'HINT' => 'После окончания аукциона выведется вместо описания'
        ]);

        (new CIBlockProperty)->Add([
            'NAME' => 'Пользователи, которым нужно отправить приглашение на email',
            'CODE' => 'USERS_TO_NOTIFICATE',
            'PROPERTY_TYPE' => 'S',
            'USER_TYPE' => 'UserID',
            'MULTIPLE' => 'Y',
            'SORT' => '515',
            'IBLOCK_ID' => $auctionIblockId
        ]);

        (new CIBlockProperty)->Add([
            'NAME' => 'Email\'ы пользователей, которым нужно отправить приглашение на email',
            'CODE' => 'USERS_EMAILS_TO_NOTIFICATE',
            'PROPERTY_TYPE' => 'S',
            'MULTIPLE' => 'Y',
            'SORT' => '516',
            'IBLOCK_ID' => $auctionIblockId
        ]);

        (new CIBlockProperty)->Add([
            'NAME' => 'Ссылка на интерфейс создания слотов для просмотра бриллиантов',
            'CODE' => 'VIEWING_TIME_SLOTS_INTERFACE_LINK',
            'PROPERTY_TYPE' => 'S',
            'USER_TYPE' => 'Link',
            'SORT' => '517',
            'IBLOCK_ID' => $auctionIblockId
        ]);

        (new CIBlockProperty)->Add([
            'NAME' => 'Слоты для просмотра бриллиантов',
            'CODE' => 'VIEWING_TIME_SLOTS',
            'PROPERTY_TYPE' => 'S',
            'MULTIPLE' => 'Y',
            'SORT' => '518',
            'IBLOCK_ID' => $auctionIblockId
        ]);

        (new CIBlockProperty)->Add([
            'NAME' => 'Ссылка на превью аукциона',
            'CODE' => 'AUCTION_PREVIEW_LINK',
            'PROPERTY_TYPE' => 'S',
            'USER_TYPE' => 'Link',
            'SORT' => '519',
            'IBLOCK_ID' => $auctionIblockId
        ]);

        (new CIBlockProperty)->Add([
            'NAME' => 'Ссылка на превью почтового уведомления аукциона',
            'CODE' => 'AUCTION_NOTIFICATION_MAIL_PREVIEW_LINK',
            'PROPERTY_TYPE' => 'S',
            'USER_TYPE' => 'Link',
            'SORT' => '520',
            'IBLOCK_ID' => $auctionIblockId
        ]);

        (new CIBlockProperty)->Add([
            'NAME' => 'Уведомления о начале аукциона отправлены',
            'CODE' => 'NOTIFICATIONS_ABOUT_AUCTION_START_SENT',
            'PROPERTY_TYPE' => 'L',
            'LIST_TYPE' => 'C',
            'SORT' => '521',
            'IBLOCK_ID' => $auctionIblockId,
            'VALUES' => [
                ['VALUE' => 'Y']
            ]
        ]);

        (new CIBlockProperty)->Add([
            'NAME' => 'Победители аукциона',
            'CODE' => 'AUCTION_WINNERS',
            'PROPERTY_TYPE' => 'S',
            'USER_TYPE' => 'AuctionWinners',
            'MULTIPLE' => 'Y',
            'SORT' => '522',
            'IBLOCK_ID' => $auctionIblockId
        ]);

        (new CIBlockProperty)->Add([
            'NAME' => 'Запросы показа бриллиантов',
            'CODE' => 'VIEWING_REQUESTS',
            'PROPERTY_TYPE' => 'S',
            'USER_TYPE' => 'Link',
            'MULTIPLE' => 'Y',
            'SORT' => '523',
            'IBLOCK_ID' => $auctionIblockId
        ]);

        (new CIBlockProperty)->Add([
            'NAME' => 'Переопределить победителей',
            'CODE' => 'REDEFINE_WINNERS',
            'PROPERTY_TYPE' => 'L',
            'LIST_TYPE' => 'C',
            'SORT' => '524',
            'IBLOCK_ID' => $auctionIblockId,
            'HINT' => 'Проставляется галочка и сохраняются изменения. Необходимо, если у определенных лотов назначались победители вручную',
            'VALUES' => [
                ['VALUE' => 'Y']
            ]
        ]);

        (new CIBlockProperty)->Add([
            'NAME' => 'Дата отправки сообщений победителям',
            'CODE' => 'AUCTION_WINNERS_NOTIFY_DATE',
            'PROPERTY_TYPE' => 'S',
            'USER_TYPE' => 'DateTime',
            'SORT' => '525',
            'IBLOCK_ID' => $auctionIblockId
        ]);

        (new CIBlockProperty)->Add([
            'NAME' => 'Аукцион с переторжками',
            'CODE' => 'AUCTION_WITH_REBIDDINGS',
            'PROPERTY_TYPE' => 'S',
            'SORT' => '526',
            'IBLOCK_ID' => $auctionIblockId
        ]);

        (new CIBlockProperty)->Add([
            'NAME' => 'Экспорт в Excel',
            'CODE' => 'EXCEL_EXPORT',
            'PROPERTY_TYPE' => 'S',
            'USER_TYPE' => 'Link',
            'SORT' => '527',
            'IBLOCK_ID' => $auctionIblockId
        ]);

        (new CIBlockProperty)->Add([
            'NAME' => 'Экспорт пользователей, подавших заявку на уведомление и просмотр',
            'CODE' => 'NOTIFY_AND_REQUEST_VIEWING_EXCEL_EXPORT',
            'PROPERTY_TYPE' => 'S',
            'USER_TYPE' => 'Link',
            'SORT' => '528',
            'IBLOCK_ID' => $auctionIblockId
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
