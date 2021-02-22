<?php

use App\Models\Jewelry\Dicts\FillFilterDataInterface;
use App\Models\Jewelry\Dicts\JewelryCollection;
use App\Models\Jewelry\Dicts\JewelryMetal;
use App\Models\Jewelry\Dicts\JewelryMetalColor;
use App\Models\Jewelry\Dicts\JewelrySex;
use App\Models\Jewelry\Dicts\JewelryStyle;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixIblockHelper\HLblock;

/**
 * Добовляет параметры в справочники
 * Class UfAddCodeToDictionaryFilter20200908102918224992
 */
class UfAddCodeToDictionaryFilter20200908102918224992 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @throws \Exception
     * @return mixed
     */
    public function up()
    {
        foreach ($this->getArTableName() as $tableName) {
            $this->addUfToDictionary($tableName);
        }

        foreach ($this->getArModel() as $model) {
            $this->fillData($model);
        }

        return true;
    }

    protected function getArTableName(): array
    {
        return [
            JewelryMetalColor::getTableName(),
            JewelryMetal::getTableName(),
            JewelrySex::getTableName(),
            JewelryCollection::getTableName(),
            JewelryStyle::getTableName(),
        ];
    }

    protected function getArModel(): array
    {
        return [
            JewelryMetalColor::class,
            JewelryMetal::class,
            JewelrySex::class,
            JewelryCollection::class,
            JewelryStyle::class,
        ];
    }

    /**
     * @param string $model
     */
    protected function fillData(string $model): void
    {
        /** @var FillFilterDataInterface $obj */
        $obj = new $model;
        $obj->fillFilterData();
    }

    protected function addUfToDictionary(string $tableName): void
    {
        $highloadBlockId       = HLblock::getByTableName($tableName)["ID"];
        $highloadBlockEntityId = 'HLBLOCK_' . $highloadBlockId;

        $fields = [
            [
                'ENTITY_ID'         => $highloadBlockEntityId,
                'FIELD_NAME'        => 'UF_FILTER_CODE',
                'USER_TYPE_ID'      => 'string',
                'MANDATORY'         => 'N',
                'EDIT_FORM_LABEL'   =>
                    [
                        'ru' => 'Алиас для фильтра',
                        'en' => 'filerCode',
                    ],
                'LIST_COLUMN_LABEL' =>
                    [
                        'ru' => 'Алиас для фильтра',
                        'en' => 'filerCode',
                    ],
                'LIST_FILTER_LABEL' =>
                    [
                        'ru' => 'Алиас для фильтра',
                        'en' => 'filerCode',
                    ],
                'ERROR_MESSAGE'     =>
                    [
                        'ru' => 'Алиас для фильтра',
                        'en' => 'filerCode',
                    ],
                'HELP_MESSAGE'      =>
                    [
                        'ru' => 'Алиас для фильтра',
                        'en' => 'filerCode',
                    ],
            ],
            [
                'ENTITY_ID'         => $highloadBlockEntityId,
                'FIELD_NAME'        => 'UF_FILTER_TITLE',
                'USER_TYPE_ID'      => 'string',
                'MANDATORY'         => 'N',
                'EDIT_FORM_LABEL'   =>
                    [
                        'ru' => 'Заголовок для фильтра',
                        'en' => 'filerTitle',
                    ],
                'LIST_COLUMN_LABEL' =>
                    [
                        'ru' => 'Заголовок для фильтра',
                        'en' => 'filerTitle',
                    ],
                'LIST_FILTER_LABEL' =>
                    [
                        'ru' => 'Заголовок для фильтра',
                        'en' => 'filerTitle',
                    ],
                'ERROR_MESSAGE'     =>
                    [
                        'ru' => 'Заголовок для фильтра',
                        'en' => 'filerTitle',
                    ],
                'HELP_MESSAGE'      =>
                    [
                        'ru' => 'Заголовок для фильтра',
                        'en' => 'filerTitle',
                    ],
            ],
            [
                'ENTITY_ID'         => $highloadBlockEntityId,
                'FIELD_NAME'        => 'UF_FILTER_RULE',
                'USER_TYPE_ID'      => 'string',
                'MANDATORY'         => 'N',
                'EDIT_FORM_LABEL'   =>
                    [
                        'ru' => 'Правило для фильтра',
                        'en' => 'filerDescription',
                    ],
                'LIST_COLUMN_LABEL' =>
                    [
                        'ru' => 'Правило для фильтра',
                        'en' => 'filerDescription',
                    ],
                'LIST_FILTER_LABEL' =>
                    [
                        'ru' => 'Правило для фильтра',
                        'en' => 'filerDescription',
                    ],
                'ERROR_MESSAGE'     =>
                    [
                        'ru' => 'Правило для фильтра',
                        'en' => 'filerDescription',
                    ],
                'HELP_MESSAGE'      =>
                    [
                        'ru' => 'Правило для фильтра',
                        'en' => 'filerDescription',
                    ],
            ],
        ];

        //todo дописать проверку на существование полей

        foreach ($fields as $field) {
            $this->addUF($field);
        }
    }

    /**
     * Reverse the migration.
     *
     * @throws \Exception
     * @return mixed
     */
    public function down()
    {
        foreach ($this->getArTableName() as $tableName) {
            $fields                = ['UF_FILTER_CODE', 'UF_FILTER_TITLE', 'UF_FILTER_RULE'];
            $highloadBlockId       = HLblock::getByTableName($tableName)["ID"];
            $highloadBlockEntityId = 'HLBLOCK_' . $highloadBlockId;

            $res = CUserTypeEntity::GetList(
                [],
                [
                    'ENTITY_ID' => $highloadBlockEntityId,
                ]
            );
            while ($field = $res->Fetch()) {
                if (!in_array($field['FIELD_NAME'], $fields)) {
                    continue;
                }
                (new CUserTypeEntity)->Delete($field['ID']);
            }
        }

        return true;
    }
}
