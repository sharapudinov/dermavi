<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Bitrix\Main\Numerator\Numerator;
use Bitrix\Main\Numerator\Generator\RandomNumberGenerator;
use Bitrix\Sale\Registry;

\Bitrix\Main\Loader::includeModule('sale');

class OrderAccountNumberGenerationSet20201026092846454860 extends BitrixMigration
{
    protected const TEMPLATE_CODE = '{USER_ID_ORDERS_COUNT}/{RANDOM}';
    protected $description = "Установка шаблона генератора номера заказа";

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $config = [
            Numerator::getType() => [
                'name' => 'Нумератор 1',
                'template' => static::TEMPLATE_CODE,
                'type' => Registry::REGISTRY_TYPE_ORDER,
            ],
            RandomNumberGenerator::getType() => [
                'length' => '6',
            ],
        ];


        $numeratorsOrderType = Numerator::getOneByType(Registry::REGISTRY_TYPE_ORDER);

        if ($numeratorsOrderType)
        {
            $numeratorForOrdersId = $numeratorsOrderType['ID'];
            $result = Numerator::update($numeratorForOrdersId, $config);

            if (!$result->isSuccess()) {
                echo implode(',', $result->getErrorMessages()).PHP_EOL;

                return false;
            }
        } else {
            $numeratorOrder = Numerator::create();
            $numeratorOrderValidationResult = $numeratorOrder->setConfig($config);

            if ($numeratorOrderValidationResult->isSuccess()) {
                $numeratorOrder->save();
            } else {
                $this->out(implode(',', $numeratorOrderValidationResult->getErrorMessages()));

                return false;
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
        $numeratorsOrderType = Numerator::getOneByType(Registry::REGISTRY_TYPE_ORDER);
        if ($numeratorsOrderType) {
            $numeratorForOrdersId = $numeratorsOrderType['ID'];
            $result = Numerator::delete($numeratorForOrdersId);

            if ($result->isSuccess()) {
                echo 'Генерация номера заказа по шаблону ОТКЛЮЧЕНА!!!'.PHP_EOL;
            }
        }
    }
}
