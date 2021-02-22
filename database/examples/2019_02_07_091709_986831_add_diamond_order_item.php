<?php

use App\Models\Catalog\HL\CatalogClarity;
use App\Models\Catalog\HL\CatalogColor;
use App\Models\Catalog\HL\CatalogShape;
use App\Models\Catalog\HL\Quality;
use App\Models\Catalog\HL\Size;
use App\Models\HL\DiamondOrder;
use App\Models\HL\DiamondOrderItem;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\HighloadBlock;
use Arrilot\BitrixMigrations\Constructors\UserField;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Bitrix\Highloadblock\HighloadBlockTable;

/**
 * Миграция для добавления таблицы позиций заказов на бриллианты.
 * Class AddDiamondOrderItem20190207091709986831
 */
class AddDiamondOrderItem20190207091709986831 extends BitrixMigration
{
    /** @var bool Выполняется в транзакции */
    public $use_transaction = true;

    /**
     * Run the migration.
     * @throws \Exception
     */
    public function up(): void
    {
        $hlBlockId = (new HighloadBlock())
            ->constructDefault('DiamondOrderItem', DiamondOrderItem::tableName())
            ->setLang('ru', 'Позиции заказов на бриллианты')
            ->setLang('en', 'Diamond order items')
            ->setLang('cn', '钻石订单项目')
            ->add();

        $entityId = 'HLBLOCK_' . $hlBlockId;

        (new UserField())->constructDefault($entityId, 'ORDER_ID')
            ->setXmlId('UF_ORDER_ID')
            ->setMandatory(true)
            ->setLangDefault('ru', 'Заказ')
            ->setLangDefault('en', 'Order')
            ->setLangDefault('cn', '顺序')
            ->add();

        (new UserField())->constructDefault($entityId, 'SHAPE')
            ->setXmlId('UF_SHAPE')
            ->setLangDefault('ru', 'Форма')
            ->setLangDefault('en', 'Shape')
            ->setLangDefault('cn', '形状')
            ->add();

        (new UserField())->constructDefault($entityId, 'CUT')
            ->setXmlId('UF_CUT')
            ->setLangDefault('ru', 'Огранка')
            ->setLangDefault('en', 'Cut')
            ->setLangDefault('cn', '切')
            ->add();

        (new UserField())->constructDefault($entityId, 'COLOR')
            ->setXmlId('UF_COLOR')
            ->setLangDefault('ru', 'Цвет')
            ->setLangDefault('en', 'Color')
            ->setLangDefault('cn', '颜色')
            ->add();

        (new UserField())->constructDefault($entityId, 'CLARITY')
            ->setXmlId('UF_CLARITY')
            ->setLangDefault('ru', 'Прозрачность')
            ->setLangDefault('en', 'Clarity')
            ->setLangDefault('cn', '透明度')
            ->add();

        (new UserField())->constructDefault($entityId, 'SIZE')
            ->setXmlId('UF_SIZE')
            ->setLangDefault('ru', 'Размер')
            ->setLangDefault('en', 'Size')
            ->setLangDefault('cn', '大小')
            ->add();
    }

    /**
     * Reverse the migration.
     * @throws \Exception
     */
    public function down(): void
    {
        $hlBlockId = highloadblock(DiamondOrderItem::tableName())['ID'];

        /** @var \Bitrix\Main\ORM\Data\AddResult $result */
        $result = HighloadBlockTable::delete($hlBlockId);

        if (!$result->isSuccess()) {
            $details = implode('; ', $result->getErrorMessages());
            throw new MigrationException("Error deleting highloadblock DiamondOrderItem: " . $details);
        }
    }
}
