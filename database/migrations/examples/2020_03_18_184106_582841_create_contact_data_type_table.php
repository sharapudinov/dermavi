<?php

use App\Models\Auxiliary\CRM\OtherContactType;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;

/**
 * Класс, описывающий миграцию для создания таблицы "Дополнительные средства связи"
 * Class CreateContactDataTypeTable20200318184106582841
 */
class CreateContactDataTypeTable20200318184106582841 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        db()->query('CREATE TABLE IF NOT EXISTS ' . (new OtherContactType())->getTable() . ' (
            id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            code VARCHAR(255) NOT NULL,
            value VARCHAR(255) NOT NULL
        )');
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        db()->query('DROP TABLE IF EXISTS ' . (new OtherContactType())->getTable());
    }
}
