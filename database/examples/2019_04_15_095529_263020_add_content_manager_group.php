<?php

use App\Models\Catalog\Catalog;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use App\Models\Auxiliary\Country;

class AddContentManagerGroup20190415095529263020 extends BitrixMigration
{
    /**
     * @var string
     */
    private $groupCode = "content_manager";
    /**
     * Run the migration
     * @throws MigrationException
     */
    public function up()
    {
        //Создание группы пользователей
        $userGroup = new CGroup();
        $userGroupFields = [
            "ACTIVE"       => "Y",
            "C_SORT"       => 100,
            "NAME"         => "Контент-менеджеры",
            "DESCRIPTION"  => "",
            "USER_ID"      => [],
            "STRING_ID"    => $this->groupCode,
        ];
        $userGroupId = $userGroup->Add($userGroupFields);
        if(!$userGroupId) {
            throw new MigrationException("Ошибка добавления группы пользователей: {$userGroup->LAST_ERROR}");
        }

        //Добавляем права на редактирование инфоблока "Бриллианты"
        $iblock = new \CIBlock();
        $iblockId = Diamond::iblockID();
        $iblockPermissions = $iblock->GetGroupPermissions($iblockId);
        $iblockPermissions[$userGroupId] = 'W';
        $iblock->SetPermission($iblockId, $iblockPermissions);
    }

    /**
     * Reverse the migration
     */
    public function down()
    {
        //Получаем группу пользователей
        $userGroup = CGroup::GetList($by, $order, ['STRING_ID' => $this->groupCode])->Fetch();

        //Удаляем права на редактирование инфоблока "Бриллианты"
        $iblock = new \CIBlock();
        $iblockId = Diamond::iblockID();
        $iblockPermissions = $iblock->GetGroupPermissions($iblockId);
        unset($iblockPermissions[$userGroup['ID']]);
        $iblock->SetPermission($iblockId, $iblockPermissions);

        //Удаляем группу пользователей
        \CGroup::Delete($userGroup['ID']);
    }
}
