<?php

use App\Models\Catalog\HL\CostRubFilter;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class FillDictCostRubFilter20201102164915333923 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $data = [
            [
                'UF_ACTIVE'           => 1,
                'UF_SORT'             => 100,
                'UF_DISPLAY_VALUE_RU' => 'до 50 000 ₽',
                'UF_DISPLAY_VALUE_EN' => 'to 50 000 ₽',
                'UF_DISPLAY_VALUE_CN' => '至 50 000 ₽',
                'UF_VALUE_FROM'       => '0',
                'UF_VALUE_TO'         => '50000',
            ],
            [
                'UF_ACTIVE'           => 1,
                'UF_SORT'             => 200,
                'UF_DISPLAY_VALUE_RU' => 'до 100 000 ₽',
                'UF_DISPLAY_VALUE_EN' => 'to 100 000 ₽',
                'UF_DISPLAY_VALUE_CN' => '至 100 000 ₽',
                'UF_VALUE_FROM'       => '0',
                'UF_VALUE_TO'         => '100000',
            ],
            [
                'UF_ACTIVE'           => 1,
                'UF_SORT'             => 300,
                'UF_DISPLAY_VALUE_RU' => 'до 500 000 ₽',
                'UF_DISPLAY_VALUE_EN' => 'to 500 000 ₽',
                'UF_DISPLAY_VALUE_CN' => '至 500 000 ₽',
                'UF_VALUE_FROM'       => '0',
                'UF_VALUE_TO'         => '500000',
            ],
            [
                'UF_ACTIVE'           => 1,
                'UF_SORT'             => 400,
                'UF_DISPLAY_VALUE_RU' => 'до 999 999 ₽',
                'UF_DISPLAY_VALUE_EN' => 'to 999 999 ₽',
                'UF_DISPLAY_VALUE_CN' => '至 999 999 ₽',
                'UF_VALUE_FROM'       => '0',
                'UF_VALUE_TO'         => '999999',
            ],
            [
                'UF_ACTIVE'           => 1,
                'UF_SORT'             => 500,
                'UF_DISPLAY_VALUE_RU' => 'от 1 000 000 ₽',
                'UF_DISPLAY_VALUE_EN' => 'from 1 000 000 ₽',
                'UF_DISPLAY_VALUE_CN' => '从 1 000 000 ₽',
                'UF_VALUE_FROM'       => '1000000',
                'UF_VALUE_TO'         => '0',
            ],
        ];

        foreach ($data as $i => $item) {
            CostRubFilter::create(
                [
                    'UF_ACTIVE'           => $item['UF_ACTIVE'],
                    'UF_SORT'             => $item['UF_SORT'],
                    'UF_DISPLAY_VALUE_RU' => $item['UF_DISPLAY_VALUE_RU'],
                    'UF_DISPLAY_VALUE_EN' => $item['UF_DISPLAY_VALUE_EN'],
                    'UF_DISPLAY_VALUE_CN' => $item['UF_DISPLAY_VALUE_CN'],
                    'UF_VALUE_FROM'       => $item['UF_VALUE_FROM'],
                    'UF_VALUE_TO'         => $item['UF_VALUE_TO'],
                ]
            );
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
        $conn = Application::getConnection();
        $conn->query('truncate `dict_cost_rub_filter`');
    }
}
