<?php

use App\Core\BitrixProperty\Property;
use App\Models\HL\Consignee;
use App\Models\HL\Signatory;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\UserField;

/**
 * Класс, описывающий миграцию для добавления полей "Телефон" и "Email" сущностям "Подписант" и "Грузополучатель"
 * Class AddEmailAndPhoneFieldsToSignatoryAndConsignees20200127195724421882
 */
class AddEmailAndPhoneFieldsToSignatoryAndConsignees20200127195724421882 extends BitrixMigration
{
    /** @var array|string[] Массив символьных кодов свойств */
    private const PROPERTIES = [
        'UF_PHONE' => [
            'ru' => 'Телефон',
            'en' => 'Phone'
        ],
        'UF_EMAIL' => [
            'ru' => 'Email',
            'en' => 'Email'
        ]
    ];

    /** @var array|string[] Массив неймспейсов сущностей, которым надо добавить свойства */
    private $entities = [
        Signatory::class,
        Consignee::class
    ];

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        foreach ($this->entities as $entity) {
            $entityId = 'HLBLOCK_' . highloadblock($entity::TABLE_CODE)['ID'];

            foreach (self::PROPERTIES as $code => $info) {
                (new UserField())->constructDefault($entityId, $code)
                    ->setXmlId($code)
                    ->setLangDefault('ru', $info['ru'])
                    ->setLangDefault('en', $info['en'])
                    ->setLangDefault('cn', $info['en'])
                    ->add();
            }
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
        foreach ($this->entities as $entity) {
            $properties = Property::getUserFields(
                $entity::TABLE_CODE,
                array_keys(self::PROPERTIES)
            );

            foreach ($properties as $property) {
                UserField::delete($property['ID']);
            }
        }
    }
}
