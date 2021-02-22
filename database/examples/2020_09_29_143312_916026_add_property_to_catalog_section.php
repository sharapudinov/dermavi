<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use App\Models\Jewelry\Jewelry;
use Arrilot\BitrixMigrations\Constructors\UserField;

class AddPropertyToCatalogSection20200929143312916026 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @throws \Exception
     * @return mixed
     */
    public function up()
    {
        $entityId = 'IBLOCK_' . Jewelry::iblockID() . '_SECTION';

        foreach ([
                     'UF_BG_IMAGE'      =>
                         [
                             'ru' => 'Фон jpg',
                             'en' => 'Background jpg',
                             'cn' => 'Background jpg',
                         ],
                     'UF_BG_IMAGE_WEBP' =>
                         [
                             'ru' => 'Фон webp',
                             'en' => 'Background webp',
                             'cn' => 'Background webp',
                         ],
                 ] as $propName => $lang) {
            (new UserField())->constructDefault($entityId, $propName)
                             ->setXmlId($propName)
                             ->setMultiple(false)
                             ->setUserType('file')
                             ->setLangDefault('ru', $lang['ru'])
                             ->setLangDefault('en', $lang['en'])
                             ->setLangDefault('cn', $lang['cn'])
                             ->add();
        }

        $this->fillBg();
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

    protected function fillBg()
    {
        $iblockId = $this->getIblockIdByCode('app_jewelry');

        $rs = CIBlockSection::GetList(
            [],
            [
                'IBLOCK_ID' => $iblockId,
            ],
            false,
            [
                'ID',
            ]
        );
        $i  = 1;
        while ($tempAr = $rs->Fetch()) {
            $GLOBALS["USER_FIELD_MANAGER"]->Update(
                "IBLOCK_" . $iblockId . "_SECTION",
                $tempAr['ID'],
                [
                    'UF_BG_IMAGE'      => $this->getPicture('Banner_0' . $i . '.jpg', 'image/jpg'),
                    'UF_BG_IMAGE_WEBP' => $this->getPicture('Banner_0' . $i . '.webp', 'image/webp'),
                ]
            );
            $i++;
            if ($i > 9) {
                $i = 1;
            }
        }
    }

    /**
     * @param $path
     * @param $type
     *
     * @return array
     */
    protected function getPicture($path, $type): array
    {
        $fileName = __DIR__ . '/files/catalogBg/' . $path;

        if (!file_exists($fileName)) {
            throw new \RuntimeException(sprintf("Файл %s не существует", $fileName));
        }

        return [
            'name'      => $fileName,
            'tmp_name'  => $fileName,
            'type'      => $type,
            'MODULE_ID' => 'iblock',
        ];
    }
}
