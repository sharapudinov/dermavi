<?php

use App\Models\HL\Country;
use App\Models\User;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Illuminate\Support\Collection;

/**
 * Класс, описывающий миграцию для добавления страны по-умолчанию всем пользователям без страны в бд
 * Class AddCountriesToAllUsers20200429173522240824
 */
class AddCountriesToAllUsers20200429173522240824 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        /** @var Country $america Модель страны по-умолчанию */
        $america = Country::filter(['UF_XML_ID' => 'USA'])->first();

        /** @var Collection|User[] $users Коллекция моделей пользователей без установленной страны регистрации */
        $users = User::filter(['UF_COUNTRY' => false])->getList();
        foreach ($users as $user) {
            if (!$user['UF_COUNTRY']) {
                $user->update(['UF_COUNTRY' => $america->getId()]);
            }
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
        //
    }
}
