<?php

use App\Models\Catalog\Catalog;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;

/**
 * Миграция для добавления разделов в тип ИБ "Каталог" и ИБ "Бриллианты"
 * Class AddSectionsInDiamondsIblock20190902090846592288
 */
class AddSectionsInDiamondsIblock20190902090846592288 extends BitrixMigration
{
    /** @var string $iblockTypeId - Идентификатор типа инфоблока */
    private $iblockTypeId = 'catalog';

    /** @var array|array[] - Разделы ИБ "Бриллианты" */
    private $sectionsInfo;

    /**
     * AddSectionsInDiamondsIblock20190902090846592288 constructor.
     */
    public function __construct()
    {
        $this->sectionsInfo = [
            [
                'ACTIVE' => 'Y',
                'IBLOCK_ID' => Diamond::iblockID(),
                'NAME' => 'Товары для юр. лиц',
                'CODE' => 'FOR_LEGAL_PERSONS',
                'SORT' => '1'
            ],
            [
                'ACTIVE' => 'Y',
                'IBLOCK_ID' => Diamond::iblockID(),
                'NAME' => 'Товары для физ. лиц',
                'CODE' => 'FOR_PHYSIC_PERSONS',
                'SORT' => '2'
            ],
            [
                'ACTIVE' => 'Y',
                'IBLOCK_ID' => Diamond::iblockID(),
                'NAME' => 'Аукционные товары',
                'CODE' => 'FOR_AUCTIONS',
                'SORT' => '3'
            ]
        ];

        parent::__construct();
    }

    /**
     * Добавляет разделы в тип ИБ "Каталог"
     *
     * @return void
     */
    private function addSectionsToIblockType(): void
    {
        (new CIBlockType)->Update($this->iblockTypeId, [
            'SECTIONS' => 'Y',
            'LANG' => [
                'ru' => [
                    'NAME' => 'Каталог',
                    'SECTION_NAME' => 'Раздел каталога',
                    'ELEMENT_NAME' => 'Элемент каталога'
                ],
                'en' => [
                    'NAME' => 'Catalog',
                    'SECTION_NAME' => 'Catalog section',
                    'ELEMENT_NAME' => 'Catalog element'
                ],
                'cn' => [
                    'NAME' => 'Catalog',
                    'SECTION_NAME' => 'Catalog section',
                    'ELEMENT_NAME' => 'Catalog element'
                ]
            ]
        ]);
    }

    /**
     * Добавляет разделы в ИБ "Бриллианты"
     *
     * @return void
     */
    private function addSectionsToDiamondsIblock(): void
    {
        foreach ($this->sectionsInfo as $sectionInfo) {
            (new CIBlockSection)->Add($sectionInfo);
        }
    }

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $this->addSectionsToIblockType();
        $this->addSectionsToDiamondsIblock();
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        /** Удаляет разделы из ИБ "Бриллианты" */
        $sectionsCodes = [];
        foreach ($this->sectionsInfo as $sectionInfo) {
            $sectionsCodes[] = $sectionInfo['CODE'];
        }

        $sectionsQuery = CIBlockSection::GetList([], ['CODE' => $sectionsCodes, 'IBLOCK_ID' => Diamond::iblockID()]);
        while ($sectionInfo = $sectionsQuery->GetNext()) {
            CIBlockSection::Delete($sectionInfo['ID']);
        }

        /** Удаляет разделы из типа ИБ "Каталог" */
        (new CIBlockType)->Update($this->iblockTypeId, ['SECTIONS' => 'N']);
    }
}
