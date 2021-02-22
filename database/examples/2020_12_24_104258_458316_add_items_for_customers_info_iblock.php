<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use App\Models\ForCustomers\Info;

class AddItemsForCustomersInfoIblock20201224104258458316 extends BitrixMigration
{
    private $importantInfoPopupCode = 'important_info_popup';
    private $importantInfoCheckoutCode = 'important_info_checkout';

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $text = 'Дорогие друзья, поздравляем вас с Новым годом!<br>Обращаем ваше внимание, что с 18.12.2020 по 11.01.2021 сроки доставки заказов могут быть увеличены в связи с длительными выходными.';

        $importantInfoPopupName    = 'Доставка в праздники (попап)';
        $importantInfoCheckoutName = 'Доставка в праздники (корзина)';

        $importantInfoPopupFields = [
            'NAME'            => $importantInfoPopupName,
            'CODE'            => $this->importantInfoPopupCode,
            'ACTIVE'          => 'N',
            'PROPERTY_VALUES' => [
                'TITLE_RU'       => $importantInfoPopupName,
                'TITLE_EN'       => $importantInfoPopupName,
                'TITLE_CN'       => $importantInfoPopupName,
                'DESCRIPTION_RU' => [
                    'VALUE' => [
                        'TEXT' => $text,
                        'TYPE' => 'HTML',
                    ],
                ],
                'DESCRIPTION_EN' => [
                    'VALUE' => [
                        'TEXT' => $text,
                        'TYPE' => 'HTML',
                    ],
                ],
                'DESCRIPTION_CN' => [
                    'VALUE' => [
                        'TEXT' => $text,
                        'TYPE' => 'HTML',
                    ],
                ],
            ],
        ];

        $importantInfoCheckoutFields = [
            'NAME'            => $importantInfoCheckoutName,
            'CODE'            => $this->importantInfoCheckoutCode,
            'ACTIVE'          => 'N',
            'PROPERTY_VALUES' => [
                'TITLE_RU'       => $importantInfoCheckoutName,
                'TITLE_EN'       => $importantInfoCheckoutName,
                'TITLE_CN'       => $importantInfoCheckoutName,
                'DESCRIPTION_RU' => [
                    'VALUE' => [
                        'TEXT' => $text,
                        'TYPE' => 'HTML',
                    ],
                ],
                'DESCRIPTION_EN' => [
                    'VALUE' => [
                        'TEXT' => $text,
                        'TYPE' => 'HTML',
                    ],
                ],
                'DESCRIPTION_CN' => [
                    'VALUE' => [
                        'TEXT' => $text,
                        'TYPE' => 'HTML',
                    ],
                ],
            ],
        ];

        Info::create($importantInfoPopupFields);
        Info::create($importantInfoCheckoutFields);
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        $filter = [
            'CODE' => [
                $this->importantInfoPopupCode,
                $this->importantInfoCheckoutCode
            ]
        ];

        $rows = Info::select(['ID'])->filter($filter)->getList();

        foreach ($rows as $row) {
            $row->delete();
        }
    }
}
