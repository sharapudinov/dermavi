<?php

use App\Models\Jewelry\Jewelry;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class TwothirdSnippets20200830210223732188 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     */
    public function up()
    {
        $iblockId = $this->getIblockIdByCode(Jewelry::IBLOCK_CODE);

        $this->addIblockElementProperty(
            [
                'NAME' => 'Тип сниппета',
                'SORT' => 550,
                'CODE' => 'SNIPPET_TYPE',
                'PROPERTY_TYPE' => 'L',
                'VALUES' => [
                    [
                        'XML_ID'=> 'DEFAULT',
                        'VALUE' => 'Обычный',
                        'DEF' => 'Y'
                    ],
                    [
                        'XML_ID'=> 'TWOTHIRD',
                        'VALUE' => 'Двойной'
                    ]
                ],
                'MULTIPLE' => 'N',
                'IS_REQUIRED' => 'N',
                'IBLOCK_ID' => $iblockId,
            ]
        );

        $this->addIblockElementProperty(
            [
                'NAME' => 'Тип контента для сниппета',
                'SORT' => 551,
                'CODE' => 'SNIPPET_CONTENT_TYPE',
                'PROPERTY_TYPE' => 'L',
                'VALUES' => [
                    [
                        'XML_ID'=> 'PHOTO',
                        'VALUE' => 'Фото',
                        'DEF' => 'Y'
                    ],
                    [
                        'XML_ID'=> 'VIDEO',
                        'VALUE' => 'Видео'
                    ]
                ],
                'MULTIPLE' => 'N',
                'IS_REQUIRED' => 'N',
                'IBLOCK_ID' => $iblockId,
            ]
        );

        $this->addIblockElementProperty(
            [
                'NAME' => 'Фото для двойного сниппета',
                'SORT' => 552,
                'CODE' => 'SNIPPET_TWOTHIRD_PHOTO',
                'PROPERTY_TYPE' => 'F',
                'MULTIPLE' => 'Y',
                'IS_REQUIRED' => 'N',
                'IBLOCK_ID' => $iblockId,
            ]
        );

        $this->addIblockElementProperty(
            [
                'NAME' => 'Видео для двойного сниппета',
                'SORT' => 553,
                'CODE' => 'SNIPPET_TWOTHIRD_VIDEO',
                'PROPERTY_TYPE' => 'F',
                'MULTIPLE' => 'N',
                'IS_REQUIRED' => 'N',
                'IBLOCK_ID' => $iblockId,
            ]
        );

        $jewelrySkus = Jewelry::getList();

        $propSnippetType = CIBlockProperty::GetList([], ['IBLOCK_ID' => $iblockId, 'CODE' => 'SNIPPET_TYPE'])->Fetch();
        $propSnippetContentType = CIBlockProperty::GetList([], ['IBLOCK_ID' => $iblockId, 'CODE' => 'SNIPPET_CONTENT_TYPE'])->Fetch();

        $propSnippetTypeEnumId = CIBlockProperty::GetPropertyEnum($propSnippetType['ID'], [], ['VALUE' => 'Обычный'])->Fetch();
        $propSnippetContentTypeEnumId = CIBlockProperty::GetPropertyEnum($propSnippetContentType['ID'], [], ['VALUE' => 'Фото'])->Fetch();

        foreach ($jewelrySkus as $jewelrySku) {
            $jewelrySku->update([
                'PROPERTY_SNIPPET_TYPE_VALUE'   => 'Обычный',
                'PROPERTY_SNIPPET_TYPE_ENUM_ID' =>  $propSnippetTypeEnumId,
                'PROPERTY_SNIPPET_CONTENT_TYPE_VALUE'   => 'Фото',
                'PROPERTY_SNIPPET_CONTENT_TYPE_ENUM_ID' => $propSnippetContentTypeEnumId,
            ]);
        }

        return true;
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        $iblockId = $this->getIblockIdByCode(Jewelry::IBLOCK_CODE);

        $this->deleteIblockElementPropertyByCode($iblockId, 'SNIPPET_TYPE');
        $this->deleteIblockElementPropertyByCode($iblockId, 'SNIPPET_CONTENT_TYPE');
        $this->deleteIblockElementPropertyByCode($iblockId, 'SNIPPET_TWOTHIRD_PHOTO');
        $this->deleteIblockElementPropertyByCode($iblockId, 'SNIPPET_TWOTHIRD_VIDEO');

        return true;
    }
}
