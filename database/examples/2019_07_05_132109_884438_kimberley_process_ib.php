<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Arrilot\BitrixMigrations\Constructors\IBlock;

class KimberleyProcessIb20190705132109884438 extends BitrixMigration
{

    private $elements = [
        [
            'CODE' => 'partitipation',
            'TITLE_RU' => 'участие в процессе',
            'TITLE_EN' => '&nbsp;',
            'TITLE_CN' => '&nbsp;',
            'DESCRIPTION_RU' => '<p class="page-kimberley__text"> Сертификат — не единственный инструмент работы Кимберлийского Процесса. В случае наличия достоверных данных о нелегальной добыче алмазов в интересах гражданского конфликта, КП имеет право наложить запрет на вывоз алмазов из этого государства. В истории КП были случаи принятия соответствующих решений. Запрет не снимается до тех пор, пока государство не подтвердит выполнение всех требуемых условий. </p> <p class="page-kimberley__text"> Россия стояла у истоков создания Кимберлийского Процесса. В том числе, представители нашей страны присутствовали и на той исторической встрече в Кимберли, а также участвовали в разработке механизмов сертификации. Обзорные визиты Кимберлийского Процесса в Россию свидетельствуют о полном соответствии механизмов контроля всем возможным нормам, что гарантирует полную уверенность в неконфликтном происхождении алмазов АЛРОСА. </p>',
            'DESCRIPTION_EN' => '&nbsp;',
            'DESCRIPTION_CN' => '&nbsp;',
        ],
        [
            'CODE' => 'process_positions',
            'TITLE_RU' => 'положения процесса',
            'TITLE_EN' => '&nbsp;',
            'TITLE_CN' => '&nbsp;',
            'DESCRIPTION_RU' => '<p class="page-kimberley__photo-text"> По правилам КП, торговать друг с другом могут только страны — участники Процесса, то есть, только те, кто может подтвердить легальность происхождения алмазов. На сегодняшний день в состав Кимберлийского Процесса входит 54 государства (включая Евросоюз, учитывающий в себе все страны ЕС).<br>В Кимберлийском Процессе как организации не участвуют представители компаний или бирж — только представители стран (государственных органов). Это обеспечивает абсолютную независимость процессов и исключает принятие решений в пользу какой-то конкретной компании. По той же причине председательство в КП каждый год передается другой стране. </p>',
            'DESCRIPTION_EN' => '&nbsp;',
            'DESCRIPTION_CN' => '&nbsp;',
        ],
        [
            'CODE' => 'diamond_sorting',
            'TITLE_RU' => 'СОРТИРОВКА АЛМАЗОВ',
            'TITLE_EN' => '&nbsp;',
            'TITLE_CN' => '&nbsp;',
            'DESCRIPTION_RU' => '<p class="page-kimberley__text"> Кимберлийский Процесс, получивший поддержку ООН, был создан, чтобы помешать конфликтным алмазам попадать на рынок — соответственно, чтобы помешать людям, которые нелегально добывают алмазы, зарабатывать на этом деньги. Для этого была разработана схема сертификации. Каждая партия алмазов на рынке должна сопровождаться сертификатом, подтверждающим, что эти алмазы добыты вне зоны конфликтных действий. Только с таким сертификатом партия алмазов может пересечь любую границу. </p> <p class="page-kimberley__text"> Кроме того, внутри страны выстраивается механизм контроля, который следит за тем, чтобы конфликтные или «серые» алмазы не попадали в торговую цепочку. Кимберлийский Процесс регулярно организует выездные проверки, чтобы подтвердить соответствие той или иной страны этим требованиям, а также располагает всей информацией о добыче и экспорте-импорте алмазов каждой страны. </p>',
            'DESCRIPTION_EN' => '&nbsp;',
            'DESCRIPTION_CN' => '&nbsp;',
        ],
        [
            'CODE' => 'process_creation',
            'TITLE_RU' => 'создание процесса',
            'TITLE_EN' => '&nbsp;',
            'TITLE_CN' => '&nbsp;',
            'DESCRIPTION_RU' => '<p class="page-kimberley__photo-text page-kimberley__photo-text--center"> В 2000 году представители алмазодобывающих и торговых стран, а также неправительственных организаций провели первую в истории встречу для того, чтобы придумать, как решить эту проблему. Встреча проходила в городе Кимберли (ЮАР), потому и образовавшаяся в результате организация получила название Кимберлийского Процесса. </p>',
            'DESCRIPTION_EN' => '&nbsp;',
            'DESCRIPTION_CN' => '&nbsp;',
        ],
        [
            'CODE' => 'kp',
            'TITLE_RU' => 'КИМБЕРЛИЙСКИЙ ПРОЦЕСС',
            'TITLE_EN' => 'Kimberley Process',
            'TITLE_CN' => '金伯利进程',
            'DESCRIPTION_RU' => '<p class="page-kimberley__text"> Некоторое время назад каждый человек знал фразу «кровавый алмаз». Конфликтные алмазы — те, которые добываются нелегально и финансируют терроризм и антиправительственные вооруженные конфликты — долгое время были большой проблемой стран Африки. Сегодня это словосочетание начинает забываться, а проблема конфликтных камней искоренена практически полностью. Сейчас ваши шансы купить кольцо с конфликтным камнем сведены к нулю — если, конечно, вы покупаете его в магазине, а не в темном переулке у незнакомого человека за четверть цены. И произошло это благодаря Кимберлийскому Процессу. </p>',
            'DESCRIPTION_EN' => '&nbsp;',
            'DESCRIPTION_CN' => '&nbsp;',
        ],
    ];


    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $this->addIB();

        $this->addElements();
    }

    /**
     * Добавляем ИБ Кимберлийского процесса
     * @throws MigrationException
     */
    private function addIB()
    {
        $iblock = (new IBlock())
            ->constructDefault('Кимберлийский процесс', \App\Models\About\KimberleyProcess::IBLOCK_CODE, "about")
            ->setVersion(2)
            ->setIndexElement(false)
            ->setIndexSection(false)
            ->setWorkflow(false)
            ->setBizProc(false)
            ->setSort(500);

        $iblock->fields['LID'] = ['s1', 's2', 's3'];
        $iblock->fields['GROUP_ID'] = [1 => 'X', 2 => 'R'];

        $iblockId = $iblock->add();

        $props = [
            [
                "NAME" => "Заголовок (рус)",
                "SORT" => "100",
                "CODE" => "TITLE_RU",
                "PROPERTY_TYPE" => "S",
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "Y",
                "IBLOCK_ID" => $iblockId,
            ],
            [
                "NAME" => "Заголовок (анг)",
                "SORT" => "100",
                "CODE" => "TITLE_EN",
                "PROPERTY_TYPE" => "S",
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "Y",
                "IBLOCK_ID" => $iblockId,
            ],
            [
                "NAME" => "Заголовок (кит)",
                "SORT" => "100",
                "CODE" => "TITLE_CN",
                "PROPERTY_TYPE" => "S",
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "Y",
                "IBLOCK_ID" => $iblockId,
            ],
            [
                "NAME" => "Описание (рус)",
                "SORT" => "100",
                "CODE" => "DESCRIPTION_RU",
                "PROPERTY_TYPE" => "S",
                "USER_TYPE" => "HTML",
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "Y",
                "IBLOCK_ID" => $iblockId,
            ],
            [
                "NAME" => "Описание (англ)",
                "SORT" => "100",
                "CODE" => "DESCRIPTION_EN",
                "PROPERTY_TYPE" => "S",
                "USER_TYPE" => "HTML",
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "Y",
                "IBLOCK_ID" => $iblockId,
            ],
            [
                "NAME" => "Описание (кит)",
                "SORT" => "100",
                "CODE" => "DESCRIPTION_CN",
                "PROPERTY_TYPE" => "S",
                "USER_TYPE" => "HTML",
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "Y",
                "IBLOCK_ID" => $iblockId,
            ],
        ];

        foreach ($props as $prop) {
            $this->addIblockElementProperty($prop);
        }
    }

    private function addElements()
    {

        $sort = 100;
        foreach ($this->elements as $element) {
            $sort += 100;
            \App\Models\About\KimberleyProcess::create([
                'NAME' => $element['TITLE_RU'],
                'CODE' => $element['CODE'],
                'SORT' => $sort,
                'ACTIVE' => 'Y',
                'PROPERTY_VALUES' => [
                    'TITLE_RU' => $element['TITLE_RU'],
                    'TITLE_EN' => $element['TITLE_EN'],
                    'TITLE_CN' => $element['TITLE_CN'],
                    'DESCRIPTION_RU' => ['VALUE' => ['TEXT' => $element['DESCRIPTION_RU'], 'TYPE' => 'HTML',]],
                    'DESCRIPTION_EN' => ['VALUE' => ['TEXT' => $element['DESCRIPTION_EN'], 'TYPE' => 'HTML',]],
                    'DESCRIPTION_CN' => ['VALUE' => ['TEXT' => $element['DESCRIPTION_CN'], 'TYPE' => 'HTML',]],
                ],
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
        $this->deleteIblockByCode(\App\Models\About\KimberleyProcess::IBLOCK_CODE);        //
    }
}
