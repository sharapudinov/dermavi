<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\UserField;

/**
 * Миграция для создания свойств для определения возможности покупки для пользователя
 * Class AddBuyStatusesPropertiesToUser20190916111057059744
 */
class AddBuyStatusesPropertiesToUser20190916111057059744 extends BitrixMigration
{
    /** @var array|array[] $properties - Массив, описывающий добавляемые свойства */
    private $properties = [
        [
            'code' => 'UF_CAN_AUCTION',
            'name' => [
                'ru' => 'Может участвовать в аукционах',
                'en' => 'Can auction'
            ]
        ],
        [
            'code' => 'UF_PURCHASE_UP_100',
            'name' => [
                'ru' => 'Доступна покупка до 100 000',
                'en' => 'Purchase available up to 100 000'
            ]
        ],
        [
            'code' => 'UF_PURCHASE_UP_600',
            'name' => [
                'ru' => 'Доступна покупка до 600 000',
                'en' => 'Purchase available up to 600 000'
            ]
        ],
        [
            'code' => 'UF_PURCHASE_OVER_600',
            'name' => [
                'ru' => 'Доступна покупка более 600 000',
                'en' => 'Purchase available over 600 000'
            ]
        ]
    ];

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        foreach ($this->properties as $property) {
            (new UserField())->constructDefault('USER', $property['code'])
                ->setXmlId($property['code'])
                ->setUserType('boolean')
                ->setLangDefault('ru', $property['name']['ru'])
                ->setLangDefault('en', $property['name']['en'])
                ->setLangDefault('cn', $property['name']['en'])
                ->add();
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
        foreach (array_column($this->properties, 'code') as $property) {
            $by = '';
            $order = '';
            $field = CUserTypeEntity::GetList(
                [$by => $order],
                ['ENTITY_ID' => 'USER', 'FIELD_NAME' => $property]
            )->Fetch();

            UserField::delete($field['ID']);
        }
    }
}
