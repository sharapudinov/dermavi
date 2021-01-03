<?php

use App\Core\Catalog\FilterFields\JewelryDiamondsFilterFields;
use App\Models\Catalog\HL\Form;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixIblockHelper\HLblock;

/**
 * Добавляет UID формы в справочник форм брилиантов для украшений
 */
class FillDictFormShapesUID20200909162810639201 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @throws \Exception
     * @return mixed
     */
    public function up()
    {
        $highloadBlockId       = HLblock::getByTableName(Form::getTableName())["ID"];
        $highloadBlockEntityId = 'HLBLOCK_' . $highloadBlockId;
        $this->addUF(
            [
                'ENTITY_ID'         => $highloadBlockEntityId,
                'FIELD_NAME'        => 'UF_SHAPE_XML_ID',
                'USER_TYPE_ID'      => 'string',
                'MANDATORY'         => 'N',
                'EDIT_FORM_LABEL'   =>
                    [
                        'ru' => 'XML_ID формы брилианта',
                        'en' => 'shape XML_ID',
                    ],
                'LIST_COLUMN_LABEL' =>
                    [
                        'ru' => 'XML_ID формы брилианта',
                        'en' => 'shape XML_ID',
                    ],
                'LIST_FILTER_LABEL' =>
                    [
                        'ru' => 'XML_ID формы брилианта',
                        'en' => 'filerDescription',
                    ],
                'ERROR_MESSAGE'     =>
                    [
                        'ru' => 'XML_ID формы брилианта',
                        'en' => 'shape XML_ID',
                    ],
                'HELP_MESSAGE'      =>
                    [
                        'ru' => 'XML_ID формы брилианта',
                        'en' => 'shape XML_ID',
                    ],
            ]
        );

        $forms = Form::getList();

        $formXmlId        = JewelryDiamondsFilterFields::$shapesGiaRFMapping;
        $prepareFormXmlId = [];
        foreach ($formXmlId as $shapeKey => $ids) {
            foreach ($ids as $id) {
                $prepareFormXmlId[$id] = $shapeKey;
            }
        }

        /** @var Form $form */
        foreach ($forms as $form) {
            $fields = $form->getFields();
            if ($shapeXmlId = $prepareFormXmlId[$form->getExternalID()]) {
                $fields['UF_SHAPE_XML_ID'] = $shapeXmlId;
                $form->update($fields);
            }
        }

        return true;
    }

    /**
     * Reverse the migration.
     *
     * @throws \Exception
     * @return mixed
     */
    public function down()
    {
        //
    }
}
