<?php

use App\Core\BitrixProperty\Property;
use App\Models\HL\Company\SingleExecutiveAuthority;
use App\Models\HL\Consignee;
use App\Models\HL\Signatory;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\UserField;

/**
 * Класс, описывающий миграцию для удаления ненужных свойств в сущностях личной анкеты (Подписанты, ЕИО и Грузополучатели)
 * К этим полям относятся дублированные, денормализующие бд, поля.
 * Class RemoveUnnecessaryFieldsFromPersonalFormEntities20200116105500804285
 */
class RemoveUnnecessaryFieldsFromPersonalFormEntities20200116105500804285 extends BitrixMigration
{
    /** @var array|string[] Массив неймспейсов моделей сущностей */
    private const ENTITIES = [
        Signatory::class,
        Consignee::class,
        SingleExecutiveAuthority::class
    ];

    /** @var array|string[] Массив символьных кодов свойств */
    private const PROPERTIES = [
        [
            'code' => 'UF_CITIZENSHIP',
            'type' => 'integer',
            'ru' => 'Гражданство',
            'en' => 'Citizenship'
        ],
        [
            'code' => 'UF_BIRTH_COUNTRY',
            'type' => 'integer',
            'ru' => 'Страна рождения',
            'en' => 'Birth country'
        ],
        [
            'code' => 'UF_BIRTH_PLACE',
            'type' => 'string',
            'ru' => 'Место рождения',
            'en' => 'Birth place'
        ],
        [
            'code' => 'UF_BIRTHDAY',
            'type' => 'date',
            'ru' => 'Дата рождения',
            'en' => 'Birthday'
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
        foreach (self::ENTITIES as $entity) {
            $properties = Property::getUserFields(
                $entity::TABLE_CODE,
                array_column(self::PROPERTIES, 'code')
            );

            foreach ($properties as $property) {
                UserField::delete($property['ID']);
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
        foreach (self::ENTITIES as $entity) {
            $entityId = 'HLBLOCK_' . highloadblock($entity::TABLE_CODE)['ID'];

            foreach (self::PROPERTIES as $property) {
                (new UserField())->constructDefault($entityId, $property['code'])
                    ->setXmlId($property['code'])
                    ->setUserType($property['type'])
                    ->setLangDefault('ru', $property['ru'])
                    ->setLangDefault('en', $property['en'])
                    ->setLangDefault('cn', $property['en'])
                    ->add();
            }
        }
    }
}
