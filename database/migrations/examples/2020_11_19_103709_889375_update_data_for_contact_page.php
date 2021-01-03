<?php

use App\Models\About\Office;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class UpdateDataForContactPage20201119103709889375 extends BitrixMigration
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
            'CODE' => 'B2B_NUMBERS_EN',
            'NAME' => 'Номера телефона для клиентов B2B (для английской версии сайта)',
            'MULTIPLE' => 'Y',
            'SORT' => '520',
            'ROW_COUNT' => '1',
            'COL_COUNT' => '60',
            'MULTIPLE_CNT' => '1',
            'IBLOCK_ID' => Office::IblockID(),
        ]);
        (new CIBlockProperty())->Add([
            'ACTIVE' => 'Y',
            'CODE' => 'B2B_NUMBERS_RU',
            'NAME' => 'Номера телефона для клиентов B2B (для русской версии сайта)',
            'MULTIPLE' => 'Y',
            'SORT' => '520',
            'ROW_COUNT' => '1',
            'COL_COUNT' => '60',
            'MULTIPLE_CNT' => '1',
            'IBLOCK_ID' => Office::IblockID(),
        ]);
        (new CIBlockProperty())->Add([
            'ACTIVE' => 'Y',
            'CODE' => 'B2C_NUMBERS_EN',
            'NAME' => 'Номера телефона для клиентов B2С (для анлийской версии сайта)',
            'MULTIPLE' => 'Y',
            'SORT' => '520',
            'ROW_COUNT' => '1',
            'COL_COUNT' => '60',
            'MULTIPLE_CNT' => '1',
            'IBLOCK_ID' => Office::IblockID(),
        ]);
        (new CIBlockProperty())->Add([
            'ACTIVE' => 'Y',
            'CODE' => 'B2C_NUMBERS_RU',
            'NAME' => 'Номера телефона для клиентов B2С (для русской версии сайта)',
            'MULTIPLE' => 'Y',
            'SORT' => '520',
            'ROW_COUNT' => '1',
            'COL_COUNT' => '60',
            'MULTIPLE_CNT' => '1',
            'IBLOCK_ID' => Office::IblockID(),
        ]);

        // заполняю свойство B2C номерами, для центрального офиса
        $office_moskow = Office::filter(['NAME' => 'Магазин в Москве'])->first();
        $office_moskow->update(
            [
                'PROPERTY_B2C_NUMBERS_RU_VALUE' => [
                    '+7 (495) 777-10-87'
                ]
            ]
        );
        $office_moskow->update(
            [
                'PROPERTY_B2C_NUMBERS_EN_VALUE' => [
                    '+7 (4812) 20-69-20',
                    '8 (800) 200-88-01'
                ]
            ]
        );

        // заполняю свойство B2C номерами, для центрального офиса
        $office_moskow = Office::filter(['NAME' => 'Магазин в Москве'])->first();
        $office_moskow->update(
            [
                'PROPERTY_B2B_NUMBERS_RU_VALUE' => [
                    '+7 (4812) 20-69-20',
                    '8 (800) 200-88-01'
                ]
            ]
        );
        $office_moskow->update(
            [
                'PROPERTY_B2B_NUMBERS_EN_VALUE' => [
                    '+7 (495) 777-29-69',
                    '+7 (495) 777-09-46'
                ]
            ]
        );

        // заполняю свойство B2C номерами, для центрального офиса
        $office_moskow = Office::filter(['NAME' => 'Магазин в Санкт-Петербурге'])->first();
        $office_moskow->update(
            [
                'PROPERTY_B2B_NUMBERS_EN_VALUE' => [
                    '+7 (495) 777-29-69',
                    '+7 (495) 777-09-46'
                ]
            ]
        );
        $office_moskow->update(
            [
                'PROPERTY_B2C_NUMBERS_RU_VALUE' => [
                    '+7 (495) 777-29-69',
                    '+7 (495) 777-09-46'
                ]
            ]
        );
        $office_moskow->update(
            [
                'PROPERTY_B2C_NUMBERS_EN_VALUE' => [
                    '+7 (495) 777-29-69',
                    '+7 (495) 777-09-46'
                ]
            ]
        );
        $office_moskow->update(
            [
                'PROPERTY_EMAIL_FOR_LINE_VALUE' => 'polished-auction@alrosa.ru',
            ]
        );

        // заполняю свойство B2C номерами, для центрального офиса
        $office_moskow = Office::filter(['NAME' => 'Магазин в Смоленске'])->first();
        $office_moskow->update(
            [
                'PROPERTY_B2B_NUMBERS_EN_VALUE' => [
                    '+7 (4812) 200-139'
                ]
            ]
        );
        $office_moskow->update(
            [
                'PROPERTY_B2C_NUMBERS_EN_VALUE' => [
                    '+7 (4812) 200-139'
                ]
            ]
        );
        $office_moskow->update(
            [
                'PROPERTY_B2C_NUMBERS_RU_VALUE' => [
                    '+7 (4812) 200-139'
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
        // удаляю старые свойства
        $codes = [
            'OTHER_NUMBERS',
            'B2C_NUMBERS_EN',
            'B2C_NUMBERS_RU',
            'B2B_NUMBERS_EN',
            'B2B_NUMBERS_RU'
        ];

        $rsProperty = CIBlockProperty::GetList(
            [],
            [
                'IBLOCK_ID' => Office::IblockID(),
            ]
        );

        while ($arProperty = $rsProperty->Fetch()) {
            if (!in_array($arProperty['CODE'], $codes, true)) {
                continue;
            }

            CIBlockProperty::Delete($arProperty['ID']);
        }
    }
}
