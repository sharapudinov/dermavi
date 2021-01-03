<?php

use App\Core\BitrixProperty\Property;
use App\Models\Catalog\Diamond;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;

/**
 * Класс, описывающий миграцию для создания поля "Идентификатор интенсивности" в ИБ "Бриллиант"
 * Class AddIntensityIdFieldIntoDiamondIblock20191008073120377938
 */
class AddIntensityIdFieldIntoDiamondIblock20191008073120377938 extends BitrixMigration
{
    /** @var array|mixed[] $intensityProperty - Массив, описывающий свойство "Идентификатор интенсивности" */
    private $intensityProperty;

    /**
     * AddIntensityIdFieldIntoDiamondIblock20191008073120377938 constructor.
     */
    public function __construct()
    {
        $this->intensityProperty = [
            'NAME' => 'Идентификатор интенсивности',
            'CODE' => 'INTENSITY_ID',
            'ACTIVE' => 'Y',
            'PROPERTY_TYPE' => 'S',
            'IBLOCK_ID' => Diamond::iblockID()
        ];

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
        (new CIBlockProperty())->Add($this->intensityProperty);
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        /** @var Property $property - Экземпляр класса для работы со свойствами */
        $property = new Property(Diamond::iblockID());
        $property->addPropertyToQuery($this->intensityProperty['CODE']);
        CIBlockProperty::Delete($property->getPropertiesInfo()[$this->intensityProperty['CODE']]['PROPERTY_ID']);
    }
}
