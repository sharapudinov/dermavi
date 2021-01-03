<?php

use App\Models\Catalog\HL\CatalogSize;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Bitrix\Main\Type\Datetime;
use Bitrix\Main\Application;

class FillHlCatalogSize20201119104158774913 extends BitrixMigration
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
                'UF_NAME' => '0.01 - 0.03',
                'UF_SORT' => 1,
                'UF_FROM' => '0.01',
                'UF_TO'   => '0.03',
            ],
            [
                'UF_NAME' => '0.04 - 0.07',
                'UF_SORT' => 20,
                'UF_FROM' => '0.04',
                'UF_TO'   => '0.07',
            ],
            [
                'UF_NAME' => '0.08 - 0.14',
                'UF_SORT' => 40,
                'UF_FROM' => '0.08',
                'UF_TO'   => '0.14',
            ],
            [
                'UF_NAME' => '0.15 - 0.17',
                'UF_SORT' => 60,
                'UF_FROM' => '0.15',
                'UF_TO'   => '0.17',
            ],
            [
                'UF_NAME' => '0.18 - 0.22',
                'UF_SORT' => 80,
                'UF_FROM' => '0.18',
                'UF_TO'   => '0.22',
            ],
            [
                'UF_NAME' => '0.23 - 0.29',
                'UF_SORT' => 100,
                'UF_FROM' => '0.23',
                'UF_TO'   => '0.29',
            ],
            [
                'UF_NAME' => '0.30 - 0.39',
                'UF_SORT' => 120,
                'UF_FROM' => '0.30',
                'UF_TO'   => '0.39',
            ],
            [
                'UF_NAME' => '0.40 - 0.49',
                'UF_SORT' => 140,
                'UF_FROM' => '0.40',
                'UF_TO'   => '0.49',
            ],
            [
                'UF_NAME' => '0.50 - 0.69',
                'UF_SORT' => 160,
                'UF_FROM' => '0.50',
                'UF_TO'   => '0.69',
            ],
            [
                'UF_NAME' => '0.70 - 0.89',
                'UF_SORT' => 180,
                'UF_FROM' => '0.70',
                'UF_TO'   => '0.89',
            ],
            [
                'UF_NAME' => '0.90 - 0.99',
                'UF_SORT' => 200,
                'UF_FROM' => '0.90',
                'UF_TO'   => '0.99',
            ],
            [
                'UF_NAME' => '1.00 - 1.49',
                'UF_SORT' => 220,
                'UF_FROM' => '1.00',
                'UF_TO'   => '1.49',
            ],
            [
                'UF_NAME' => '1.50 - 1.99',
                'UF_SORT' => 240,
                'UF_FROM' => '1.50',
                'UF_TO'   => '1.99',
            ],
            [
                'UF_NAME' => '2.00 - 2.99',
                'UF_SORT' => 260,
                'UF_FROM' => '2.00',
                'UF_TO'   => '2.99',
            ],
            [
                'UF_NAME' => '3.00 - 3.99',
                'UF_SORT' => 280,
                'UF_FROM' => '3.00',
                'UF_TO'   => '3.99',
            ],
            [
                'UF_NAME' => '4.00 - 4.99',
                'UF_SORT' => 300,
                'UF_FROM' => '4.00',
                'UF_TO'   => '4.99',
            ],
            [
                'UF_NAME' => '5.00 - 6.99',
                'UF_SORT' => 320,
                'UF_FROM' => '5.00',
                'UF_TO'   => '6.99',
            ],
            [
                'UF_NAME' => '7.00 - 9.99',
                'UF_SORT' => 340,
                'UF_FROM' => '7.00',
                'UF_TO'   => '9.99',
            ],
            [
                'UF_NAME' => '10.00 - 19.99',
                'UF_SORT' => 360,
                'UF_FROM' => '10.00',
                'UF_TO'   => '19.99',
            ],
            [
                'UF_NAME' => '20.00 - 200.00',
                'UF_SORT' => 380,
                'UF_FROM' => '20.00',
                'UF_TO'   => '200.00',
            ],
        ];

        foreach ($data as $i => $item) {
            CatalogSize::create(
                [
                    'UF_XML_ID'      => CatalogSize::generateXmlId($item['UF_NAME']),
                    'UF_NAME'        => $item['UF_NAME'],
                    'UF_SORT'        => $item['UF_SORT'],
                    'UF_FROM'        => $item['UF_FROM'],
                    'UF_TO'          => $item['UF_TO'],
                    'UF_DATE_CREATE' => (new Datetime()),
                    'UF_DATE_UPDATE' => (new Datetime()),
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
        $conn->query('truncate `catalog_size`');
    }
}
