<?php

use App\Models\HL\Country;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\UserField;
use CUserTypeEntity;
use Illuminate\Support\Collection;

/**
 * Класс, описывающий миграцию для создания поля "Код страны" в таблице "Страна"
 * Class AddCountryCodeColumnIntoAppCountry20191106155633540178
 */
class AddCountryCodeColumnIntoAppCountry20191106155633540178 extends BitrixMigration
{
    /** @var string $columnCode - Символьный код столбца */
    private static $columnCode = 'UF_CODE';

    /** @var array|string $countriesCodesMapping - Массив, описывающий соответствие стран и их кодов */
    private static $countriesCodesMapping = [
        'Russian Fed.' => 'RU',
        'Belarus' => 'BY',
        'Ukraine' => 'UA',
        'Armenia' => 'AM',
        'Azerbaijan' => 'AZ',
        'Uzbekistan' => 'UZ',
        'Tajikistan' => 'TJ',
        'Kyrgyzstan' => 'KG',
        'Kazakhstan' => 'KZ',
        'China' => 'CN',
        'Hong Kong' => 'HK'
    ];

    /** @var string $entityId - Идентификатор сущности */
    private $entityId;

    /**
     * AddCountryCodeColumnIntoAppCountry20191106155633540178 constructor.
     */
    public function __construct()
    {
        $this->entityId = 'HLBLOCK_' . highloadblock(Country::TABLE_CODE)['ID'];
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
        (new UserField())->constructDefault($this->entityId, self::$columnCode)
            ->setXmlId(self::$columnCode)
            ->setLangDefault('ru', 'Код страны')
            ->setLangDefault('en', 'Country code')
            ->setLangDefault('cn', 'Country code')
            ->add();

        /** @var Collection|Country[] $countries - Коллекция стран */
        $countries = Country::filter(['UF_XML_ID' => array_keys(self::$countriesCodesMapping)])->getList();
        foreach ($countries as $country) {
            $country->update([self::$columnCode => self::$countriesCodesMapping[$country->getCode()]]);
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
        /** @var array|mixed[] $field - Массив, описывающий свойство */
        $field = CUserTypeEntity::GetList([], ['ENTITY_ID' => $this->entityId, 'FIELD_NAME' => self::$columnCode])
            ->Fetch();
        UserField::delete($field['ID']);
    }
}
