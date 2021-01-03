<?php

use App\Models\Search\Tag;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\IBlock;
use Arrilot\BitrixMigrations\Constructors\IBlockProperty;
use Arrilot\BitrixMigrations\Constructors\IBlockType;

class SearchTags20200421153751474113 extends BitrixMigration
{
    const IBLOCK_TYPE_ID = "search";
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        (new CIBlockType)->Add([
            'ID' => self::IBLOCK_TYPE_ID,
            'SECTIONS' => 'N',
            'LANG' => [
                'en' => [
                    'NAME' => 'Search'
                ],
                'ru' => [
                    'NAME' => 'Поиск'
                ],
                'cn' => [
                    'NAME' => 'Search'
                ]
            ]
        ]);

        $iblock = (new IBlock())
            ->constructDefault('Теги',Tag::IBLOCK_CODE , self::IBLOCK_TYPE_ID)
            ->setVersion(2);

        $iblock->fields['LID'] = ['s1', 's2', 's3'];

        $iblockId = $iblock->add();

        (new IBlockProperty())->constructDefault('NAME_EN', 'Тег(англ)', $iblockId)
            ->setIsRequired(true)
            ->add();

        (new IBlockProperty())->constructDefault('NAME_RU', 'Тег(рус)', $iblockId)
            ->add();

        (new IBlockProperty())->constructDefault('NAME_CN', 'Тег(кит)', $iblockId)
            ->add();

        (new IBlockProperty())->constructDefault('URL', 'Ссылка', $iblockId)
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
        IBlock::delete(Tag::iblockID());
        IBlockType::delete(self::IBLOCK_TYPE_ID);
    }
}
