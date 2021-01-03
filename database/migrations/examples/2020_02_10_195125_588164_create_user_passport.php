<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

/**
 * Миграция создает паспортные данные для пользователя, если их нет
 * Class CreateUserPassport20200210195125588164
 */
class CreateUserPassport20200210195125588164 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        /** @var \App\Models\User[] $users */
        $users = \App\Models\User::getList();

        foreach($users as $user) {
            if (!$user->isLegalEntity() && !$user->passportData && $user->getCountry()) {
                $passportData = \App\Models\HL\PassportData::create([
                    'UF_REG_COUNTRY' => $user->getCountry()
                ]);

                $user->update(['UF_PASSPORT_ID'=>$passportData->getId()]);
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
