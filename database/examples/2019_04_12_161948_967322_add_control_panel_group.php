<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Bitrix\Main\IO\File;
use Bitrix\Main\IO\FileNotFoundException;

class AddControlPanelGroup20190412161948967322 extends BitrixMigration
{
    /**
     * @var string
     */
    private $groupCode = "control_panel_users";
    /**
     * Run the migration
     * @throws MigrationException
     * @throws FileNotFoundException
     */
    public function up()
    {
        //Создание группы пользователей
        $userGroup = new CGroup();
        $userGroupFields = [
            "ACTIVE"       => "Y",
            "C_SORT"       => 100,
            "NAME"         => "Пользователи панели управления",
            "DESCRIPTION"  => "",
            "USER_ID"      => [],
            "STRING_ID"    => $this->groupCode,
        ];
        $userGroupId = $userGroup->Add($userGroupFields);
        if(!$userGroupId) {
            throw new MigrationException("Ошибка добавления группы пользователей: {$userGroup->LAST_ERROR}");
        }
        
        //Добавляем права на доступ в админскую панель Битрикс
        $file = new File(public_path("bitrix/.access.php"));
        $contents = $file->getContents();
        $content = '$PERM["admin"]["' . $userGroupId . '"]="R";' . PHP_EOL;
        $contents = str_replace('<?' . PHP_EOL, '<?' . PHP_EOL . $content, $contents);
        $file->putContents($contents);
    }
    
    /**
     * Reverse the migration
     * @throws FileNotFoundException
     */
    public function down()
    {
        //Получаем группу пользователей
        $userGroup = CGroup::GetList($by, $order, ['STRING_ID' => $this->groupCode])->Fetch();
    
        //Удаляем права на доступ в админскую панель Битрикс
        $file = new File(public_path("bitrix/.access.php"));
        $contents = $file->getContents();
        $content = '$PERM["admin"]["' . $userGroup['ID'] . '"]="R";' . PHP_EOL;
        $contents = str_replace($content, '', $contents);
        $file->putContents($contents);
        
        //Удаляем группу пользователей
        \CGroup::Delete($userGroup['ID']);
    }
}
