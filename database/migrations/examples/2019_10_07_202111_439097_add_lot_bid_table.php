<?php

use App\Models\Auctions\LotBid;
use App\Models\Auctions\LotBidStatus;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\HighloadBlock;
use Arrilot\BitrixMigrations\Constructors\UserField;
use Bitrix\Highloadblock\HighloadBlockTable;

/**
 * Класс, описывающий миграцию на создание таблиц "Статус ставки на лот" и "Ставка на лот"
 * Class AddLotBidTable20191007202111439097
 */
class AddLotBidTable20191007202111439097 extends BitrixMigration
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
            ->constructDefault('LotBidStatus', LotBidStatus::TABLE_CODE)
            ->setLang('ru', 'Статус ставки на лот')
            ->setLang('en', 'Lot bid status')
            ->setLang('cn', 'Lot bid status')
            ->add();
        $entityId = 'HLBLOCK_' . $hlBlockId;

        (new UserField())->constructDefault($entityId, 'UF_NAME')
            ->setXmlId('UF_NAME')
            ->setMandatory(true)
            ->setLangDefault('ru', 'Наименование')
            ->setLangDefault('en', 'Name')
            ->setLangDefault('cn', 'Name')
            ->add();

        /** @var string $lotBidStatuses - Массив, описывающий статусы в таблице "Статус ставки на лот" */
        $lotBidStatuses = [
            'active' => 'Активна',
            'changed' => 'Изменена',
            'deleted' => 'Удалена'
        ];
        foreach ($lotBidStatuses as $code => $name) {
            LotBidStatus::create([
                'UF_NAME' => $name
            ]);
        }

        $hlBlockId = (new HighloadBlock())
            ->constructDefault('LotBid', LotBid::TABLE_CODE)
            ->setLang('ru', 'Ставка на лот')
            ->setLang('en', 'Lot bid')
            ->setLang('cn', 'Lot bid')
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
        $hlBlockId = highloadblock(LotBidStatus::TABLE_CODE)['ID'];
        HighloadBlockTable::delete($hlBlockId);

        $hlBlockId = highloadblock(LotBid::TABLE_CODE)['ID'];
        HighloadBlockTable::delete($hlBlockId);
    }
}
