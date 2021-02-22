<?php

use App\Models\Auxiliary\Auctions\AgreementStatus;
use App\Models\Auxiliary\Auctions\UserRebiddingAgreement;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;

/**
 * Класс, описывающий миграцию для создания таблиц "app_user_rebidding_agreement" и "app_rebidding_agreement_status"
 * Class AddUserRebiddingAgreementTables20191017140955902122
 */
class AddUserRebiddingAgreementTables20191017140955902122 extends BitrixMigration
{
    /** @var array|mixed[] $agreementStatuses - Массив, описывающий данные таблицы "app_rebidding_agreement_status"  */
    private $agreementStatuses = ['Увеличение', 'Сохранение'];

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        db()->query('CREATE TABLE IF NOT EXISTS ' . (new AgreementStatus())->getTable() . ' (
            id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL
        )');
        foreach ($this->agreementStatuses as $status) {
            AgreementStatus::create(['name' => $status]);
        }

        db()->query('CREATE TABLE IF NOT EXISTS ' . (new UserRebiddingAgreement())->getTable() . ' (
            id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            auction_lot_id INT(11) NOT NULL,
            user_id INT(11) NOT NULL,
            status_id INT(11) NOT NULL,
            created_at DATETIME NOT NULL,
            updated_at DATETIME NOT NULL
        )');
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        db()->query('DROP TABLE IF EXISTS ' . (new UserRebiddingAgreement())->getTable());
        db()->query('DROP TABLE IF EXISTS ' . (new AgreementStatus())->getTable());
    }
}
