<?php

use App\Models\Catalog\HL\CostDollarFilter;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class FillDictCostDollarFilter20201102165901840090 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @throws \Exception
     * @return mixed
     */
    public function up()
    {
        $data = [
            [
                'UF_ACTIVE'           => 1,
                'UF_SORT'             => 100,
                'UF_DISPLAY_VALUE_RU' => 'до 500 $',
                'UF_DISPLAY_VALUE_EN' => 'to 500 $',
                'UF_DISPLAY_VALUE_CN' => '至 500 $',
                'UF_VALUE_FROM'       => '0',
                'UF_VALUE_TO'         => '500',
            ],
            [
                'UF_ACTIVE'           => 1,
                'UF_SORT'             => 200,
                'UF_DISPLAY_VALUE_RU' => 'до 2 000 $',
                'UF_DISPLAY_VALUE_EN' => 'to 2 000 $',
                'UF_DISPLAY_VALUE_CN' => '至 2 000 $',
                'UF_VALUE_FROM'       => '0',
                'UF_VALUE_TO'         => '2000',
            ],
            [
                'UF_ACTIVE'           => 1,
                'UF_SORT'             => 300,
                'UF_DISPLAY_VALUE_RU' => 'до 5 000 $',
                'UF_DISPLAY_VALUE_EN' => 'to 5 000 $',
                'UF_DISPLAY_VALUE_CN' => '至 5 000 $',
                'UF_VALUE_FROM'       => '0',
                'UF_VALUE_TO'         => '5000',
            ],
            [
                'UF_ACTIVE'           => 1,
                'UF_SORT'             => 400,
                'UF_DISPLAY_VALUE_RU' => 'до 9 999 $',
                'UF_DISPLAY_VALUE_EN' => 'to 9 999 $',
                'UF_DISPLAY_VALUE_CN' => '至 9 999 $',
                'UF_VALUE_FROM'       => '0',
                'UF_VALUE_TO'         => '9999',
            ],
            [
                'UF_ACTIVE'           => 1,
                'UF_SORT'             => 500,
                'UF_DISPLAY_VALUE_RU' => 'от 10 000 $',
                'UF_DISPLAY_VALUE_EN' => 'from 10 000 $',
                'UF_DISPLAY_VALUE_CN' => '从10 000 $',
                'UF_VALUE_FROM'       => '10000',
                'UF_VALUE_TO'         => '0',
            ],
        ];

        foreach ($data as $i => $item) {
            CostDollarFilter::create(
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
     * @throws \Exception
     * @return mixed
     */
    public function down()
    {
        $conn = Application::getConnection();
        $conn->query('truncate `dict_cost_dollar_filter`');
    }
}
