<?php

use App\Models\Tracing\HL\Caption;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class NewCaptionForNamesMessages20200203152405342794 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        Caption::create([
            'UF_TEXT_EN' => '<div class="tracing__caption tracing__caption--diamond-name tracing__caption--hidden">#diamondName#<br> <span class=" tracing__caption--diamond-message ">#diamondMessage#</span> </div>',
            'UF_TEXT_RU' => '<div class="tracing__caption tracing__caption--diamond-name tracing__caption--hidden">#diamondName#<br><span class=" tracing__caption--diamond-message ">#diamondMessage#</span></div>',
            'UF_TEXT_CN' => '<div class="tracing__caption tracing__caption--diamond-name tracing__caption--hidden">#diamondName#<br> <span class=" tracing__caption--diamond-message">#diamondMessage#</span> </div>',
            'UF_NAME' => 'diamondName',
            'UF_INDEX' => 0,
            'UF_ACTIVE' => true,
            'UF_SCENARIO_NUMBER' => 1,
            'UF_START_TIME' => '00:14:00',
            'UF_END_TIME' => '00:18:00',
            'UF_CAUSE_PARAM_NAME' => 'diamondName',
            'UF_CAUSE_PARAM_VALUE' => 'Y'
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
        Caption::filter(['UF_NAME' => 'diamondName'])->first()->delete();
    }
}
