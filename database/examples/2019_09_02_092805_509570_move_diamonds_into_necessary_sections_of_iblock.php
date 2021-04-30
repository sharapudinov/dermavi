<?php

use App\Models\Catalog\Catalog;
use App\Models\Catalog\CatalogSection;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;

/**
 * Миграция для переноса бриллиантов в нужные разделы инфоблока
 * Class MoveDiamondsIntoNecessarySectionsOfIblock20190902092805509570
 */
class MoveDiamondsIntoNecessarySectionsOfIblock20190902092805509570 extends BitrixMigration
{
    /** @var \Illuminate\Support\Collection|Diamond[] $diamonds - Коллекция всех бриллиантов в БД */
    private $diamonds;

    /** @var array|int[] - Массив, описывающий соотношение символьного кода и идентификатора разделов иб "Бриллианты" */
    private $sections;

    /**
     * AddSectionsIntoDiamondsIblock20190902083156281082 constructor.
     */
    public function __construct()
    {
        $this->diamonds = Diamond::getList();

        $sections = CatalogSection::getList();
        foreach ($sections as $section) {
            $this->sections[$section['CODE']] = $section['ID'];
        }

        parent::__construct();
    }

    /**
     * Возвращает идентификатор нужного для бриллианта раздела ИБ "Бриллианты"
     *
     * @param Diamond $diamond - Модель бриллианта
     *
     * @return int
     */
    private function getNecessarySectionForDiamond(Diamond $diamond): int
    {
        if ($diamond['PROPERTY_IS_AUCTION_PRODUCT_VALUE'] == 'Y') {
            $sectionId = $this->sections['FOR_AUCTIONS'];
        } elseif ($diamond['PROPERTY_IS_FOR_PHYSICAL_VALUE'] == 'Y') {
            $sectionId = $this->sections['FOR_PHYSIC_PERSONS'];
        } else {
            $sectionId = $this->sections['FOR_LEGAL_PERSONS'];
        }

        return $sectionId;
    }

    /**
     * Run the migration.
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function up()
    {
        foreach ($this->diamonds as $diamond) {
            $diamond->update(['IBLOCK_SECTION' => $this->getNecessarySectionForDiamond($diamond)]);
        }
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function down()
    {
        foreach ($this->diamonds as $diamond) {
            $diamond->update(['IBLOCK_SECTION' => false]);
        }
    }
}
