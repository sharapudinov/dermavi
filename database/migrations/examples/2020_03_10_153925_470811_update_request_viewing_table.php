<?php

use App\Core\BitrixProperty\Property;
use App\Models\HL\ViewingRequestForm;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\UserField;

/**
 * Класс, описывающий миграцию для добавления поля "Локация" в таблицу "Форма "Запросить показ"
 * Class UpdateRequestViewingTable20200310153925470811
 */
class UpdateRequestViewingTable20200310153925470811 extends BitrixMigration
{
    /** @var string Символьный код свойства */
    private const NEW_PROPERTY_CODE = 'UF_LOCATION';

    /** @var string $entityId Символьный код сущности */
    private $entityId;

    /** @var array|int[] $updateProperties Массив свойств для обновления */
    private $updateProperties = [];

    /**
     * UpdateRequestViewingTable20200310153925470811 constructor.
     */
    public function __construct()
    {
        $this->entityId = 'HLBLOCK_' . highloadblock(ViewingRequestForm::TABLE_CODE)['ID'];
        $this->updateProperties = Property::getUserFields(
            ViewingRequestForm::TABLE_CODE,
            [
                'UF_COUNTRY',
                'UF_COMMENT'
            ]
        );

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
        foreach ($this->updateProperties as $property) {
            (new UserField())->constructDefault($this->entityId, $property['FIELD_NAME'])
                ->setMandatory(false)
                ->update($property['ID']);
        }

        (new UserField())->constructDefault($this->entityId, self::NEW_PROPERTY_CODE)
            ->setXmlId(self::NEW_PROPERTY_CODE)
            ->setLangDefault('ru', 'Локация просмотра')
            ->setLangDefault('en', 'Viewing location')
            ->setLangDefault('cn', 'Viewing location')
            ->add();
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        UserField::delete(
            Property::getUserFields(ViewingRequestForm::TABLE_CODE, [self::NEW_PROPERTY_CODE])[0]['ID']
        );

        foreach ($this->updateProperties as $property) {
            (new UserField())->constructDefault($this->entityId, $property['FIELD_NAME'])
                ->setMandatory(true)
                ->update($property['ID']);
        }
    }
}
