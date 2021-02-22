<?php

use App\Models\Auctions\PBLotBid;
use App\Models\Auctions\PBLotBidStatus;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\HighloadBlock;
use Arrilot\BitrixMigrations\Constructors\UserField;
use Bitrix\Highloadblock\HighloadBlockTable;

/**
 * Класс, описывающий миграцию на создание таблиц "Статус ставки на лот" и "Ставка на лот"
 * Class AddPbLotBidTable20200601202111439097
 */
class AddPbLotBidTable20200601202111439097 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $hlBlockId = (new HighloadBlock())
            ->constructDefault('PBLotBidStatus', PBLotBidStatus::TABLE_CODE)
            ->setLang('ru', 'Статус ставки на лот PB')
            ->setLang('en', 'PB Lot bid status')
            ->setLang('cn', 'PB Lot bid status')
            ->add();
        $entityId = 'HLBLOCK_' . $hlBlockId;

        (new UserField())->constructDefault($entityId, 'UF_NAME')
            ->setXmlId('UF_NAME')
            ->setMandatory(true)
            ->setLangDefault('ru', 'Наименование')
            ->setLangDefault('en', 'Name')
            ->setLangDefault('cn', 'Name')
            ->add();

        /** @var array $lotBidStatuses - Массив, описывающий статусы в таблице "Статус ставки на лот" */
        $lotBidStatuses = [
            'active' => 'Активна',
            'changed' => 'Изменена',
            'deleted' => 'Удалена'
        ];
        foreach ($lotBidStatuses as $code => $name) {
            PBLotBidStatus::create([
                'UF_NAME' => $name
            ]);
        }

        $hlBlockId = (new HighloadBlock())
            ->constructDefault('PBLotBid', PBLotBid::TABLE_CODE)
            ->setLang('ru', 'Ставка на лот PB')
            ->setLang('en', 'PB Lot bid')
            ->setLang('cn', 'PB Lot bid')
            ->add();
        $entityId = 'HLBLOCK_' . $hlBlockId;

        (new UserField())->constructDefault($entityId, 'UF_BID')
            ->setXmlId('UF_BID')
            ->setMandatory(true)
            ->setUserType('string')
            ->setLangDefault('ru', 'Ставка')
            ->setLangDefault('en', 'Bid')
            ->setLangDefault('cn', 'Bid')
            ->add();

        (new UserField())->constructDefault($entityId, 'UF_BID_STATUS_ID')
            ->setXmlId('UF_BID_STATUS_ID')
            ->setMandatory(true)
            ->setLangDefault('ru', 'Статус ставки')
            ->setLangDefault('en', 'Bid status')
            ->setLangDefault('cn', 'Bid status')
            ->add();

        (new UserField())->constructDefault($entityId, 'UF_LOT_ID')
            ->setXmlId('UF_LOT_ID')
            ->setMandatory(true)
            ->setLangDefault('ru', 'Лот')
            ->setLangDefault('en', 'Lot')
            ->setLangDefault('cn', 'Lot')
            ->add();

        (new UserField())->constructDefault($entityId, 'UF_USER_ID')
            ->setXmlId('UF_USER_ID')
            ->setMandatory(true)
            ->setLangDefault('ru', 'Пользователь')
            ->setLangDefault('en', 'User')
            ->setLangDefault('cn', 'User')
            ->add();

        (new UserField())->constructDefault($entityId, 'UF_DATE_TIME')
            ->setXmlId('UF_DATE_TIME')
            ->setMandatory(true)
            ->setUserType('datetime')
            ->setLangDefault('ru', 'Дата и время ставки')
            ->setLangDefault('en', 'Bid datetime')
            ->setLangDefault('cn', 'Bid datetime')
            ->add();
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        $hlBlockId = highloadblock(PBLotBidStatus::TABLE_CODE)['ID'];
        HighloadBlockTable::delete($hlBlockId);

        $hlBlockId = highloadblock(PBLotBid::TABLE_CODE)['ID'];
        HighloadBlockTable::delete($hlBlockId);
    }
}
