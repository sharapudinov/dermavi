<?php

use App\Models\HL\Country;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class AddContentManagerUser20190415101239842629 extends BitrixMigration
{
    /**
     * Run the migration
     * @throws MigrationException
     */
    public function up()
    {
        //Получаем группу "Пользователи панели управления"
        $groupControlPanel = \CGroup::GetList($by, $order, ['STRING_ID' => 'control_panel_users'])->Fetch();
        //Получаем группу "Контент-менеджеры"
        $groupContentManager = \CGroup::GetList($by, $order, ['STRING_ID' => 'content_manager'])->Fetch();
    
        //Создаем тестового контент-менеджера
        $user = new \CUser();
        $entity = CUserTypeEntity::GetList([], ['ENTITY_ID' => 'USER', 'FIELD_NAME' => 'UF_USER_ENTITY_TYPE'])->Fetch();
        $result = $user->Add([
            'LOGIN' => 'content_manager@alrosa.com',
            'EMAIL' => 'content_manager@alrosa.com',
            'PASSWORD' => 'gfkj56dlf',
            'CONFIRM_PASSWORD' => 'gfkj56dlf',
            'UF_COUNTRY' => Country::query()->filter(['UF_XML_ID' => 'Russia'])->first()['ID'],
            'UF_USER_ENTITY_TYPE' => CUserFieldEnum::GetList([], [
                'USER_FIELD_ID' => $entity['ID'], 'XML_ID' => 'PHYSICAL_ENTITY'
            ])->Fetch()['ID'],
            'GROUP_ID' => [$groupControlPanel['ID'], $groupContentManager['ID']]
        ]);
        if(!$result) {
            throw new MigrationException("Ошибка добавления тестового контент-менеджера: {$user->LAST_ERROR}");
        }
    }

    /**
     * Reverse the migration
     */
    public function down()
    {
        //Удаляем тестового контент-менеджера
        $user = \CUser::GetList($by, $order, ['LOGIN' => 'content_manager@alrosa.com'])->Fetch();
        \CUser::Delete($user['ID']);
    }
}
