<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use App\Models\ForCustomers\FAQ;

class ChangeOrderAndPaymentUrlInDb20201029175455605494 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $oldValue = '/customer-service/order-and-shipping/';
        $newValue = '/customer-service/payment-and-shipping/';

        $code = 'faq-4';
        $element = FAQ::filter(
            [
                'CODE' => $code,
            ]
        )->first();

        $element->update(
            [
                'PROPERTY_ANSWER_RU_VALUE' => str_replace(
                    $oldValue,
                    $newValue,
                    $element->getFields()['PROPERTY_ANSWER_RU_VALUE']
                ),
            ]
        );

        $code = 'faq-7';
        $element = FAQ::filter(
            [
                'CODE' => $code,
            ]
        )->first();

        $element->update(
            [
                'PROPERTY_ANSWER_RU_VALUE' => str_replace(
                    $oldValue,
                    $newValue,
                    $element->getFields()['PROPERTY_ANSWER_RU_VALUE']
                ),
            ]
        );

        $code = 'faq-17';
        $element = FAQ::filter(
            [
                'CODE' => $code,
            ]
        )->first();

        $element->update(
            [
                'PROPERTY_ANSWER_RU_VALUE' => str_replace(
                    $oldValue,
                    $newValue,
                    $element->getFields()['PROPERTY_ANSWER_RU_VALUE']
                ),
            ]
        );

        $code = 'faq-18';
        $element = FAQ::filter(
            [
                'CODE' => $code,
            ]
        )->first();

        $element->update(
            [
                'PROPERTY_ANSWER_RU_VALUE' => str_replace(
                    $oldValue,
                    $newValue,
                    $element->getFields()['PROPERTY_ANSWER_RU_VALUE']
                ),
            ]
        );

    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        //
    }
}
