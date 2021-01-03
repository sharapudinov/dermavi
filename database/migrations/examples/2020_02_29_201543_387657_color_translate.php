<?php

use App\Models\Catalog\HL\CatalogColor;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class ColorTranslate20200229201543387657 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $map = [
            'yellow' =>[
               'ru' => 'Желтый',
               'cn' => '黄色',
            ],
            'pink' =>[
               'ru' => 'Розовый',
               'cn' => '粉红色',
            ],
            'blue' =>[
               'ru' => 'Голубой',
               'cn' => '蓝色',
            ],
            'green' =>[
               'ru' => 'Зеленый',
               'cn' => '绿色',
            ],
            'orange' =>[
               'ru' => 'Оранжевый',
               'cn' => '橙',
            ],
            'brown' =>[
               'ru' => 'Коричневый',
               'cn' => '褐色',
            ],
            'red' =>[
               'ru' => 'Красный',
               'cn' => '红',
            ],
            'purple' =>[
               'ru' => 'Пурпурный',
               'cn' => '紫色',
            ],
            'gray' =>[
               'ru' => 'Серый',
               'cn' => '灰色',
            ],
        ];

        foreach ($map as $colorXmlId => $data) {
            $row = CatalogColor::getByExtID($colorXmlId);
            $row->update([
                'UF_DISPLAY_VALUE_RU' => $data['ru'],
                'UF_DISPLAY_VALUE_CN' => $data['cn'],
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
