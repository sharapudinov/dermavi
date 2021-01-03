<?php

use App\Models\Catalog\PaidService;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Bitrix\Catalog\Model\Price;

class UpdatePrice20200731114620826167 extends BitrixMigration
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
        // изменение цены гравировки ювелирного изделия
        $this->element = PaidService::filter([
            'CODE' => 'engraving-jewelry'
        ])->first();
        if (!$this->element) {
            throw new MigrationException('Элемент engraving-jewelry не найден, обновление цены не удалось выполнить');
        }

        $price = Price::getList([
            'filter' => [
                'PRODUCT_ID' => $this->element->id,
            ],
        ])->fetch();

        $data = [
            "fields" => [
                "PRODUCT_ID" => $this->element->id,
                "PRICE" => 500,
                "CURRENCY" => "RUB",
            ],
        ];
        if(!$price){
            throw new MigrationException('Обновление цены не удалось выполнить');
        }
        Price::update($price['ID'], $data);

        // изменение цены гравировки брилианта
        $this->element = PaidService::filter([
            'CODE' => 'engraving'
        ])->first();
        if (!$this->element) {
            throw new MigrationException('Элемент engraving не найден, обновление цены не удалось выполнить');
        }

        $price = Price::getList([
            'filter' => [
                'PRODUCT_ID' => $this->element->id,
            ],
        ])->fetch();

        $data = [
            "fields" => [
                "PRODUCT_ID" => $this->element->id,
                "PRICE" => 40,
                "CURRENCY" => "RUB",
            ],
        ];
        if(!$price){
            throw new MigrationException('Обновление цены не удалось выполнить');
        }
        Price::update($price['ID'], $data);
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        // изменение цены гравировки ювелирного изделия
        $this->element = PaidService::filter([
            'CODE' => 'engraving-jewelry'
        ])->first();
        if (!$this->element) {
            throw new MigrationException('Элемент engraving-jewelry не найден, обновление цены не удалось выполнить');
        }

        $price = Price::getList([
            'filter' => [
                'PRODUCT_ID' => $this->element->id,
            ],
        ])->fetch();

        $data = [
            "fields" => [
                "PRODUCT_ID" => $this->element->id,
                "PRICE" => 40,
                "CURRENCY" => "RUB",
            ],
        ];
        if(!$price){
            throw new MigrationException('Обновление цены не удалось выполнить');
        }
        Price::update($price['ID'], $data);


        // изменение цены гравировки брилианта
        $this->element = PaidService::filter([
            'CODE' => 'engraving'
        ])->first();
        if (!$this->element) {
            throw new MigrationException('Элемент engraving не найден, обновление цены не удалось выполнить');
        }

        $price = Price::getList([
            'filter' => [
                'PRODUCT_ID' => $this->element->id,
            ],
        ])->fetch();

        $data = [
            "fields" => [
                "PRODUCT_ID" => $this->element->id,
                "PRICE" => 45,
                "CURRENCY" => "RUB",
            ],
        ];
        if(!$price){
            throw new MigrationException('Обновление цены не удалось выполнить');
        }
        Price::update($price['ID'], $data);
    }
}
