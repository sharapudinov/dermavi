<?php

use App\Local\Component\InfoForCustomers;
use App\Models\ForCustomers\Info;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class CustomerChinaTranslate20191209234714424418 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        if ($row = Info::getByCode('upakovka_2')) {
            $row->update([
               'PROPERTY_TITLE_CN_VALUE'=>'透明塑料泡沫'
            ]);
        } if ($row = Info::getByCode('russian_certificate')) {
            $row->update([
               'PROPERTY_TITLE_CN_VALUE'=>'俄罗斯证书'
            ]);
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
