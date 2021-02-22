<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\Constructor;
use Arrilot\BitrixMigrations\Constructors\HighloadBlock;
use Arrilot\BitrixMigrations\Constructors\UserField;

class AddSharedJewelryHl20200228152745702321 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $hblockId = (new HighloadBlock())
            ->constructDefault('SharedJewelry', 'app_shared_jewelry_person')
            ->setLang('ru', 'Информация о "поделившемся украшением"')
            ->setLang('en', 'Информация о "поделившемся украшением"')
            ->add();

        $entityId = Constructor::objHLBlock($hblockId);

        (new UserField())->constructDefault($entityId, 'SENDER_EMAIL')->add();

        (new UserField())->constructDefault($entityId, 'RECIPIENTS_EMAILS')->add();

        (new UserField())->constructDefault($entityId, 'MESSAGE')->add();
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        HighloadBlock::delete('app_shared_jewelry_person');
    }
}
