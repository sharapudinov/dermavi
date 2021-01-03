<?php

use App\Models\HL\ViewingRequestForm;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\UserField;

/**
 * Класс, описывающий миграцию для обновления свойство "Id лота аукциона" в таблице "Форма "Запросить показ"
 * Class UpdateRequestViewingAuctionLotIdField20200109140813022360
 */
class UpdateRequestViewingAuctionLotIdField20200109140813022360 extends BitrixMigration
{
    /** @var string $entity Символьный код сущности таблицы */
    private $entity;

    /**
     * UpdateRequestViewingAuctionLotIdField20200109140813022360 constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->entity = 'HLBLOCK_' . highloadblock(ViewingRequestForm::TABLE_CODE)['ID'];
    }

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $property = CUserTypeEntity::GetList([], ['ENTITY_ID' => $this->entity,'FIELD_NAME' => 'UF_AUCTION_LOT_ID'])
            ->Fetch();

        UserField::delete($property['ID']);

        (new UserField())->constructDefault($this->entity, 'UF_AUCTION_LOT_IDS')
            ->setUserType('integer')
            ->setMultiple(true)
            ->setLangDefault('ru', 'Id лотов аукциона')
            ->setLangDefault('en', 'Auction lots ids')
            ->setLangDefault('cn', 'Auction lots ids')
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
        $property = CUserTypeEntity::GetList([], ['ENTITY_ID' => $this->entity,'FIELD_NAME' => 'UF_AUCTION_LOT_IDS'])
            ->Fetch();

        UserField::delete($property['ID']);

        (new UserField())->constructDefault($this->entity, 'UF_AUCTION_LOT_ID')
            ->setUserType('integer')
            ->setLangDefault('ru', 'Id Лота Аукциона')
            ->setLangDefault('en', 'Auction Lot Id')
            ->setLangDefault('cn', 'Auction Lot Id')
            ->add();
    }
}
