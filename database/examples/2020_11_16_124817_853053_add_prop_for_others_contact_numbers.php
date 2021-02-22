<?php

use App\Models\About\Office;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;

class AddPropForOthersContactNumbers20201116124817853053 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        // Добавляю свойство для номеров телефона, которые будут использоваться для английской версии сайта
        (new CIBlockProperty())->Add([
            'ACTIVE' => 'Y',
            'CODE' => 'OTHER_NUMBERS',
            'NAME' => 'Номера телефона (для английской версии сайта)',
            'MULTIPLE' => 'Y',
            'SORT' => '520',
            'ROW_COUNT' => '1',
            'COL_COUNT' => '60',
            'MULTIPLE_CNT' => '1',
            'IBLOCK_ID' => Office::IblockID(),
        ]);

        // заполняю новое свойство номерами, для офиса в Москве
        $office_moskow = Office::filter(['NAME' => 'Магазин в Москве'])->first();
        $office_moskow->update(
            [
                'PROPERTY_OTHER_NUMBERS_VALUE' => [
                    '+7 (495) 777-29-69',
                    '+7 (495) 777-09-46'
                ]
            ]
        );
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
