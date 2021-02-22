<?php

use App\Models\Catalog\HL\DiamondAge;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\HighloadBlock;
use Arrilot\BitrixMigrations\Constructors\UserField;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Bitrix\Highloadblock\HighloadBlockTable;

/**
 * Добавляет справочник возрастов бриллиантов.
 *
 * Class AddAgeDictionary20190605032616589638
 */
class AddAgeDictionary20190605032616589638 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @throws \Exception
     */
    public function up(): void
    {
        $hblockId = (new HighloadBlock())
            ->constructDefault('DiamondAge', 'app_diamond_age')
            ->setLang('ru', 'Возрасты бриллиантов')
            ->setLang('en', 'Diamond\'s ages')
            ->add();

        $entityId = 'HLBLOCK_' . $hblockId;

        (new UserField())->constructDefault($entityId, 'XML_ID')
            ->setMandatory(true)
            ->setLangDefault('ru', 'Внешний идентификатор')
            ->setLangDefault('en', 'External ID')
            ->add();

        (new UserField())->constructDefault($entityId, 'NAME_EN')
            ->setMandatory(true)
            ->setLangDefault('ru', 'Наименование (англ)')
            ->setLangDefault('en', 'Name (en)')
            ->setLangDefault('cn', '英文名称')
            ->setSettings(['SIZE' => 100, 'ROWS' => 1])
            ->add();

        (new UserField())->constructDefault($entityId, 'NAME_RU')
            ->setLangDefault('ru', 'Наименование (рус)')
            ->setLangDefault('en', 'Name (rus)')
            ->setLangDefault('cn', '俄文名字')
            ->setSettings(['SIZE' => 100, 'ROWS' => 1])
            ->add();

        (new UserField())->constructDefault($entityId, 'NAME_CN')
            ->setLangDefault('ru', 'Наименование (кит)')
            ->setLangDefault('en', 'Name (cn)')
            ->setLangDefault('cn', '中文名')
            ->setSettings(['SIZE' => 100, 'ROWS' => 1])
            ->add();

        (new UserField())->constructDefault($entityId, 'SORT')
            ->setLangDefault('ru', 'Приоритет в списке')
            ->setLangDefault('en', 'Sort priority')
            ->setUserType('integer')
            ->setSettings(['DEFAULT_VALUE' => 500])
            ->add();
    }

    /**
     * Reverse the migration.
     *
     * @throws \Exception
     */
    public function down(): void
    {
        $hblockId = highloadblock(DiamondAge::TABLE_CODE)['ID'];

        /** @var \Bitrix\Main\ORM\Data\AddResult $result */
        $result = HighloadBlockTable::delete($hblockId);

        if (!$result->isSuccess()) {
            $details = implode('; ', $result->getErrorMessages());
            throw new MigrationException("Error deleting highloadblock DiamondAge: " . $details);
        }
    }
}
