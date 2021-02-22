<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use App\Core\BitrixProperty\Property;
use App\Models\Jewelry\JewelrySku;

class AddSellingAvailableFilter20200911122112854582 extends BitrixMigration
{
    /**
     * Обновляет свойства
     *
     * @param bool $filtrable Флаг вывода поля фильтрации в списке админки
     *
     * @return void
     */
    private function update(bool $filtrable): void
    {
        $sellingAvailable = 'SELLING_AVAILABLE';

        $property = new Property(JewelrySku::iblockID());
        $property->addPropertyToQuery($sellingAvailable);
        $propertiesInfo = $property->getPropertiesInfo();

        (new CIBlockProperty())->Update(
            $propertiesInfo[$sellingAvailable]['PROPERTY_ID'],
            ['FILTRABLE' => $filtrable ? 'Y' : 'N']
        );
    }

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $this->update(true);
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        $this->update(false);
    }
}
