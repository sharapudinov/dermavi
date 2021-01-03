<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use App\Models\Catalog\PaidService;

class UpdatePriceEngraving20200805184154763323 extends BitrixMigration
{
    /** @var PaidService $element */
    private $element;

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        // изменение цены гравировки брилианта
        $this->element = PaidService::filter([
            'CODE' => 'engraving'
        ])->first();
        if (!$this->element) {
            throw new MigrationException('Элемент engraving не найден, обновление цены не удалось выполнить');
        }
        $this->element->update([
            'PROPERTY_MAXIMUM_COUNT_VALUE' => '50'
        ]);
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        // изменение цены гравировки брилианта
        $this->element = PaidService::filter([
            'CODE' => 'engraving'
        ])->first();
        if (!$this->element) {
            throw new MigrationException('Элемент engraving не найден, обновление цены не удалось выполнить');
        }
        $this->element->update([
            'PROPERTY_MAXIMUM_COUNT_VALUE' => '40'
        ]);
    }
}
