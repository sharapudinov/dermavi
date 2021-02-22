<?php

use App\Core\BitrixProperty\Property;
use App\Models\Catalog\HL\DiamondPacket;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\UserField;

/**
 * Класс, описывающий миграцию для добавления новых полей в таблицу "Информация о пакете"
 * Class UpdateDiamondPacketTable20200603110802781076
 */
class UpdateDiamondPacketTable20200603110802781076 extends BitrixMigration
{
    /** @var array|string[] Массив свойств */
    private const PROPERTIES = [
        'UF_GIA_2_COLOR_ID' => 'Ключ цвета по GIA внутреннему',
        'UF_GIA_2_QUAL_ID' => 'Ключ качества по GIA внутреннему',
        'UF_GIA_2_CUT_ID' => 'Ключ качества огранки по GIA внутреннему',
        'UF_GIA_2_FORM_ID' => 'Ключ формы по GIA внутреннему',
        'UF_GIA_2_FLUOR_ID' => 'Ключ флю по GIA внутреннему',
        'UF_GIA_2_DEPTH' => 'Высота, значения в процентах от 25 до 80%, по внутренней оценке',
        'UF_GIA_2_TABLE' => 'Площадка, значения в процентах от 25 до 80%, по внутренней оценке',
        'UF_GIA_2_SYMMETRY' => 'Ключ симметрии, по внутренней оценке',
        'UF_GIA_2_POLISH' => 'Ключ полировки, по внутренней оценке',
        'UF_GIA_2_CULET' => 'Ключ кулеты, по внутренней оценке',
        'UF_DIAMETER' => 'Значение диаметра для круглых, длина для фантазийных форм огранки',
        'UF_WIDTH' => 'Значение ширины для фантазийных форм огранки'
    ];

    /** @var string $entity Символьный код сущности для обновления */
    private $entity;

    /**
     * UpdateDiamondPacketTable20200603110802781076 constructor.
     */
    public function __construct()
    {
        $this->entity = 'HLBLOCK_' . highloadblock(DiamondPacket::TABLE_NAME)['ID'];
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
        foreach (self::PROPERTIES as $propertyCode => $propertyName) {
            (new UserField())->constructDefault($this->entity, $propertyCode)
                ->setXmlId($propertyCode)
                ->setLangDefault('ru', $propertyName)
                ->setLangDefault('en', $propertyName)
                ->setLangDefault('cn', $propertyName)
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
        $properties = Property::getUserFields(DiamondPacket::TABLE_NAME, array_keys(self::PROPERTIES));
        foreach ($properties as $property) {
            UserField::delete($property['ID']);
        }
    }
}
