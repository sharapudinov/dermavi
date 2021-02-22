<?php

use App\Models\Blog\Article;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\IBlockProperty;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class BlogChangeRequiredFields20200212132240661494 extends BitrixMigration
{
    /**
     * @var array
     */
    protected $props = [
        'TITLE_RU',
        'TITLE_EN',
        'PREVIEW_TEXT_RU',
        'PREVIEW_TEXT_EN',
        ];
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        foreach ($this->props as $prop) {
            $propId = $this->getIblockPropIdByCode($prop, Article::iblockID());
            (new IBlockProperty())
                ->setIsRequired(false)
                ->update($propId);
        }

    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        foreach ($this->props as $prop) {
            $propId = $this->getIblockPropIdByCode($prop, Article::iblockID());
            (new IBlockProperty())
                ->setIsRequired(true)
                ->update($propId);
        }
    }
}
