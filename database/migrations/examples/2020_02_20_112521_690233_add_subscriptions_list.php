<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Bitrix\Main\Loader;

/**
 * Класс, описывающий миграцию для добавления списка рассылок по подпискам
 * Class AddSubscriptionsList20200220112521690233
 */
class AddSubscriptionsList20200220112521690233 extends BitrixMigration
{
    /**
     * AddSubscriptionsList20200220112521690233 constructor.
     */
    public function __construct()
    {
        Loader::IncludeModule('subscribe');
    }

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        /** @var array|array[] $rubrics Список рассылок, которые надо добавить */
        $rubrics = [
            [
                'name' => 'Новости сайта и рекламная информация',
                'code' => 'news_and_advertising',
                'sort' => '1'
            ],
            [
                'name' => 'Маркетинговые предложения и акции',
                'code' => 'marketing_and_actions',
                'sort' => '2'
            ],
            [
                'name' => 'Оповещения о предстоящих аукционах',
                'code' => 'auctions_remind',
                'sort' => '3'
            ]
        ];

        foreach ($rubrics as $rubric) {
            (new CRubric())->Add([
                'NAME' => $rubric['name'],
                'CODE' => $rubric['code'],
                'SORT' => $rubric['sort'],
                'LID' => 's1'
            ]);
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
        $rubricsQuery = CRubric::GetList();
        while ($rubric = $rubricsQuery->GetNext()) {
            CRubric::Delete($rubric['ID']);
        }
    }
}
