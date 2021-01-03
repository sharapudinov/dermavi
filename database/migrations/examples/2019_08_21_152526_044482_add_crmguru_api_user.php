<?php

use App\Core\BitrixProperty\Property;
use App\Models\User;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;

/**
 * Миграция для создания пользователя для работы с api для crmguru
 * Class AddCrmguruApiUser20190821152526044482
 */
class AddCrmguruApiUser20190821152526044482 extends BitrixMigration
{
    /** @var string $login - Логин */
    private $login = 'crmguru_api';

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        User::create([
            'LOGIN' => $this->login,
            'EMAIL' => 'crmguru_api@crmguru.com',
            'PASSWORD' => \Illuminate\Support\Str::random(8),
            'UF_USER_ENTITY_TYPE' => Property::getUserTypeListPropertyValue(
                'UF_USER_ENTITY_TYPE',
                'PHYSICAL_ENTITY'
            )->getVariantId()
        ]);
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        /** @var User|null $user */
        $user = User::getByLogin($this->login);
        if ($user) {
            $user->delete();
        }
    }
}
