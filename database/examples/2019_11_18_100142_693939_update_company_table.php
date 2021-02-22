<?php

use App\Models\HL\Company\Company;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\UserField;

/**
 * Класс, описывающий миграцию для добавления в таблицу "Компания" полей, связанных с личной анкетой пользователя
 * Class UpdateCompanyTable20191118100142693939
 */
class UpdateCompanyTable20191118100142693939 extends BitrixMigration
{
    /** @var array|array[] $properties Массив, описывающий новые свойства */
    private static $properties = [
        [
            'name' => 'UF_TAX_NUMBER',
            'type' => 'string',
            'langs' => [
                'ru' => 'ИНН',
                'en' => 'Tax number'
            ]
        ],
        [
            'name' => 'UF_KPP',
            'type' => 'string',
            'langs' => [
                'ru' => 'КПП',
                'en' => 'KPP'
            ]
        ],
        [
            'name' => 'UF_OKPO',
            'type' => 'string',
            'langs' => [
                'ru' => 'ОКПО',
                'en' => 'OKPO'
            ]
        ],
        [
            'name' => 'UF_OGRN',
            'type' => 'string',
            'langs' => [
                'ru' => 'ОГРН',
                'en' => 'OGRN'
            ]
        ],
        [
            'name' => 'UF_REG_COUNTRY',
            'type' => 'integer',
            'langs' => [
                'ru' => 'Страна регистрации',
                'en' => 'Reg country'
            ]
        ],
        [
            'name' => 'UF_REG_PLACE',
            'type' => 'string',
            'langs' => [
                'ru' => 'Место регистрации',
                'en' => 'Reg place'
            ]
        ],
        [
            'name' => 'UF_REG_AUTHORITY',
            'type' => 'string',
            'langs' => [
                'ru' => 'Орган регистрации',
                'en' => 'Reg authority'
            ]
        ],
        [
            'name' => 'UF_REG_DATE',
            'type' => 'date',
            'langs' => [
                'ru' => 'Дата регистрации',
                'en' => 'Reg date'
            ]
        ],
        [
            'name' => 'UF_LOCATION_ADDR',
            'type' => 'integer',
            'langs' => [
                'ru' => 'Адрес места нахождения',
                'en' => 'Location address'
            ]
        ],
        [
            'name' => 'UF_POST_ADDR',
            'type' => 'integer',
            'langs' => [
                'ru' => 'Почтовый адрес',
                'en' => 'Post address'
            ]
        ],
        [
            'name' => 'UF_TYPE_ID',
            'type' => 'integer',
            'langs' => [
                'ru' => 'Тип компании',
                'en' => 'Company type'
            ]
        ],
        [
            'name' => 'UF_CATEGORY_ID',
            'type' => 'integer',
            'langs' => [
                'ru' => 'Категория',
                'en' => 'Category'
            ]
        ]
    ];

    /** @var string $entityId Идентификатор сущности */
    private $entityId;

    /**
     * UpdateCompanyTable20191118100142693939 constructor.
     */
    public function __construct()
    {
        $this->entityId = 'HLBLOCK_' . highloadblock(Company::TABLE_CODE)['ID'];
        parent::__construct();
    }

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        foreach (self::$properties as $property) {
            (new UserField())->constructDefault($this->entityId, $property['name'])
                ->setXmlId($property['name'])
                ->setUserType($property['type'])
                ->setLangDefault('ru', $property['langs']['ru'])
                ->setLangDefault('en', $property['langs']['en'])
                ->setLangDefault('cn', $property['langs']['en'])
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
        foreach (self::$properties as $property) {
            $propertyId = CUserTypeEntity::GetList(
                [],
                ['ENTITY_ID' => $this->entityId, 'FIELD_NAME' => $property['name']]
            )->Fetch();
            UserField::delete($propertyId);
        }
    }
}
