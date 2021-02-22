<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Bitrix\Main\Loader;

/**
 * Класс, описывающий миграцию для обновления сортировки платежной системы "Оплата картой"
 * Class UpdateCardPaymentSystemSort20200117170303177955
 */
class UpdateCardPaymentSystemSort20200117170303177955 extends BitrixMigration
{
    /** @var int $paySystemId Идентификатор платежной системы */
    private $paySystemId;

    /**
     * UpdateCardPaymentSystemSort20200117170303177955 constructor.
     */
    public function __construct()
    {
        Loader::includeModule('sale');

        $this->paySystemId = CSalePaySystem::GetList([], ['CODE' => 'CREDIT_CARD'])->Fetch()['ID'];
    }

    /**
     * Обновляет платежную систему
     *
     * @param int $sort Сортировка
     *
     * @return void
     */
    private function updatePaySystem(int $sort): void
    {
        CSalePaySystem::Update($this->paySystemId, ['SORT' => $sort]);
    }

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $this->updatePaySystem(90);
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        $this->updatePaySystem(400);
    }
}
