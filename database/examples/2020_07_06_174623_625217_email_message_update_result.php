<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Bitrix\Main\Mail\Internal\EventMessageTable;

class EmailMessageUpdateResult20200706174623625217 extends BitrixMigration
{




    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $query = EventMessageTable::getList(['filter' => ['EVENT_NAME' => 'AUCTION_RESULTS_USER']]);
        while ($event = $query->fetch()) {
            EventMessageTable::update(
                $event['ID'],
                [
                    'MESSAGE' => "<?php EventMessageThemeCompiler::includeComponent('email.dispatch:auction.results.user', #TEMPLATE#, [
                    'auction_id' => #AUCTION_ID#,
                    'user_id' => #USER_ID#
                    ])?>"
                ]
            );
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
        $query = EventMessageTable::getList(['filter' => ['EVENT_NAME' => 'AUCTION_RESULTS_USER']]);
        while ($event = $query->fetch()) {
            EventMessageTable::update(
                $event['ID'],
                [
                    'MESSAGE' => "<?php EventMessageThemeCompiler::includeComponent('email.dispatch:auction.results.user', '', [
                    'auction_id' => #AUCTION_ID#,
                    'user_id' => #USER_ID#
                    ])?>"
                ]
            );
        }
    }
}
