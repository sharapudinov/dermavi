<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\IBlockType;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class ArticlesAddTypeIb20190627160616070872 extends BitrixMigration
{
    const IBLOCK_TYPE_ID = 'articles';

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        (new IBlockType())
            ->setId(self::IBLOCK_TYPE_ID)
            ->setSections(true)
            ->setLang('ru', 'Статьи')
            ->setLang('en', 'Articles')
            ->setLang('cn', 'Articles')
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
        (new IBlockType())
            ->delete(self::IBLOCK_TYPE_ID);
    }
}
