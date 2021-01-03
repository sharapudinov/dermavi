<?php

use App\Models\Catalog\Diamond;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Bitrix\Iblock\PropertyIndex\Manager;
use Bitrix\Iblock\SectionPropertyTable;

/**
 * Регистрация свойства "вариант возраста" в умном фильтре.
 * Class RegisterAgeDictInSmartFilter20190610105216888357
 */
class RegisterAgeDictInSmartFilter20190610105216888357 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @throws \Exception
     */
    public function up(): void
    {
        $iblockId = Diamond::iblockID();
        $propertyId = (int) $this->getIblockPropIdByCode('AGE_VARIANT', $iblockId);

        $this->setSmartFilter($iblockId, $propertyId, true);
        $this->reindex($iblockId);
    }

    /**
     * Reverse the migration.
     *
     * @throws \Exception
     */
    public function down(): void
    {
        $iblockId = Diamond::iblockID();
        $propertyId = (int) $this->getIblockPropIdByCode('AGE_VARIANT', $iblockId);

        $this->setSmartFilter($iblockId, $propertyId, false);
        SectionPropertyTable::delete([
            'IBLOCK_ID' => $iblockId,
            'SECTION_ID' => 0,
            'PROPERTY_ID' => $propertyId
        ]);

        $this->reindex($iblockId);
    }

    /**
     * Переиндексация фасета.
     *
     * @param int $iblockId
     */
    private function reindex(int $iblockId): void
    {
        $indexer = Manager::createIndexer($iblockId);
        if ($indexer->isExists()) {
            Manager::deleteIndex($iblockId);
        }

        $indexer->startIndex();
        $indexer->continueIndex();
        $indexer->endIndex();
    }

    /**
     * Включает/убирает свойство из умного фаильтра.
     *
     * @param int $iblockId
     * @param int $propertyId
     * @param bool $show
     */
    private function setSmartFilter(int $iblockId, int $propertyId, bool $show): void
    {
        (new CIBlockProperty)->Update(
            $propertyId,
            [
                'SMART_FILTER' => $show ? 'Y' : 'N',
                'IBLOCK_ID' => $iblockId,
            ]
        );
    }
}
