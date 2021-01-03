<?php

use App\Models\Catalog\HL\WeightFilter;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Bitrix\Main\Application;

class FillDictWeightFilter20201102110406352214 extends BitrixMigration
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
                'UF_DISPLAY_VALUE_RU' => 'до 0.5 карат',
                'UF_DISPLAY_VALUE_EN' => 'to 0.5 carat',
                'UF_DISPLAY_VALUE_CN' => '至 0.5 克拉',
                'UF_VALUE_FROM'       => '0.00',
                'UF_VALUE_TO'         => '0.50',
            ],
            [
                'UF_ACTIVE'           => 1,
                'UF_SORT'             => 200,
                'UF_DISPLAY_VALUE_RU' => 'до 1 карат',
                'UF_DISPLAY_VALUE_EN' => 'to 1 carat',
                'UF_DISPLAY_VALUE_CN' => '至 1 克拉',
                'UF_VALUE_FROM'       => '0.00',
                'UF_VALUE_TO'         => '1.00',
            ],
            [
                'UF_ACTIVE'           => 1,
                'UF_SORT'             => 300,
                'UF_DISPLAY_VALUE_RU' => 'до 2 карат',
                'UF_DISPLAY_VALUE_EN' => 'to 2 carat',
                'UF_DISPLAY_VALUE_CN' => '至 2 克拉',
                'UF_VALUE_FROM'       => '0.00',
                'UF_VALUE_TO'         => '2.00',
            ],
            [
                'UF_ACTIVE'           => 1,
                'UF_SORT'             => 400,
                'UF_DISPLAY_VALUE_RU' => 'до 3 карат',
                'UF_DISPLAY_VALUE_EN' => 'to 3 carat',
                'UF_DISPLAY_VALUE_CN' => '至 3 克拉',
                'UF_VALUE_FROM'       => '0.00',
                'UF_VALUE_TO'         => '3.00',
            ],
            [
                'UF_ACTIVE'           => 1,
                'UF_SORT'             => 500,
                'UF_DISPLAY_VALUE_RU' => 'от 5 карат',
                'UF_DISPLAY_VALUE_EN' => 'from 5 carat',
                'UF_DISPLAY_VALUE_CN' => '从 5 克拉',
                'UF_VALUE_FROM'       => '5.00',
                'UF_VALUE_TO'         => '0',
            ],
        ];

        foreach ($data as $i => $item) {
            WeightFilter::create(
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
        $conn->query('truncate `dict_weight_filter`');
    }
}
