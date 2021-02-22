<?php

use App\Models\Tracing\HL\Caption;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;

/**
 * Класс, описывающий миграцию для добавления временного титра в трейсинг
 * Необходимо добавить титр для одного бриллианта и написать, что его выкупил Larry West в декабре 2019
 * Через некоторое время будет нормально функционирующая система, но сейчас задача критичная
 * Class AddTemporaryCaptionToTracing20200124123240390694
 */
class AddTemporaryCaptionToTracing20200124123240390694 extends BitrixMigration
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
            'UF_TEXT_EN' => '<div class="tracing__caption tracing__caption--diamond_buy_date tracing__caption--hidden">December, 2019<br> <span class="tracing__caption-value">Larry West</span> </div>',
            'UF_TEXT_RU' => '<div class="tracing__caption tracing__caption--diamond_buy_date tracing__caption--hidden">Декабрь, 2019<br><span class="tracing__caption-value">Larry West</span></div>',
            'UF_TEXT_CN' => '<div class="tracing__caption tracing__caption--diamond_buy_date tracing__caption--hidden">2019年12月<br> <span class="tracing__caption-value">Larry West</span> </div>',
            'UF_NAME' => 'diamond_buy_date',
            'UF_INDEX' => 0,
            'UF_ACTIVE' => true,
            'UF_SCENARIO_NUMBER' => 1,
            'UF_START_TIME' => '00:14:00',
            'UF_END_TIME' => '00:18:00',
            'UF_CAUSE_PARAM_NAME' => 'diamond_id',
            'UF_CAUSE_PARAM_VALUE' => '19023097'
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
        Caption::filter(['UF_NAME' => 'diamond_buy_date'])->first()->delete();
    }
}
