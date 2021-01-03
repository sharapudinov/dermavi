<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\IBlock;

class RussianCutIb20190704175446156310 extends BitrixMigration
{

    private $elements = [
        [
            'CODE' => 'russian-cut',
            'TITLE_RU' => 'Русская огранка',
            'TITLE_EN' => 'Russian cut',
            'TITLE_CN' => '俄式切割',
            'DESCRIPTION_RU' => '<p class="page-russian-cut__text">Российские камни привлекают покупателей со всего мира не только благодаря высокому качеству алмазов, но и благодаря великолепной русской огранке. Понятие Russian Cut известно во всем мире и считается своеобразным «знаком качества» благодаря отточенным веками умениям наших мастеров. </p> <p class="page-russian-cut__text"> Извлеченные из недр земли алмазы имеют довольно мало общего с ослепительными, притягивающими взгляд камнями в ювелирных изделиях. Для того чтобы сделать алмаз ключевой составляющей изящного кольца, кулона или подвески, кристалл должен подвергнуться специальной обработке — огранке, которая выведет на передний план все его достоинства и скроет возможные недостатки. Огранка позволяет выявить присущую алмазу скрытую красоту, раскрыть максимальную игру и блеск кристалла. </p>',
            'DESCRIPTION_EN' => '<p class="page-russian-cut__text">Russian gemstones attract buyers from all over the world not only because of the high quality of the diamonds, but also because of the magnificent Russian cut. The concept of Russian Cut is known all over the world and is regarded as a kind of "quality mark" due to the skills of our masters honed by centuries.</p> <p class="page-russian-cut__text">Diamonds extracted from the bowels of the earth have very little in common with sparkling, eye-attracting gemstones seen in jewelry. In order to become a center piece of a graceful ring, a necklace or a pendant, a diamond is to undergo a special treatment – the cut that will bring all its advantages to the foreground and disguise possible shortcomings. The cut allows uncovering the hidden beauty inherent in a diamond, revealing the maximum trick of light and brilliance of the crystal.</p>',
            'DESCRIPTION_CN' => '&nbsp;',
        ],
        [
            'CODE' => 'petr-i',
            'TITLE_RU' => 'эпоха петра первого',
            'TITLE_EN' => '&nbsp;',
            'TITLE_CN' => '&nbsp;',
            'DESCRIPTION_RU' => '<p class="page-russian-cut__text"> Если ювелирному искусству никак не меньше тысячи лет, то гранить бриллианты начали сравнительно поздно – в 15 веке в Европе. В России эту тенденцию переняли чуть позже, и уже с 16 века шлифованные алмазы начали венчать украшения царской семьи. Сначала работать с алмазами умели только несколько придворных мастеров, однако в начале 18 века Петр Первый издал указ о создании первой в стране гранильной фабрики. </p>',
            'DESCRIPTION_EN' => '&nbsp;',
            'DESCRIPTION_CN' => '&nbsp;',
        ],
        [
            'CODE' => 'gold-age',
            'TITLE_RU' => 'золотой век',
            'TITLE_EN' => '&nbsp;',
            'TITLE_CN' => '&nbsp;',
            'DESCRIPTION_RU' => '<p class="page-russian-cut__text"> «Золотой век» российского ограночного и ювелириного искусства, конечно же, связан с двумя женщинами – Елизаветой I и Екатериной II. Увлеченные красотой этих камней, они искали лучших ювелиров и поощряли развитие их талантов. В том числе, из-за рубежа приглашались лучшие мастера для передачи опыта, они оставались в России и развивали свои умения, а потом – создавали целые династии. В 18 и 19 веке в России появляется множество великих имен: Позье, Экарт, Болин, Фаберже. </p>',
            'DESCRIPTION_EN' => '&nbsp;',
            'DESCRIPTION_CN' => '&nbsp;',
        ],
        [
            'CODE' => 'ideal-form',
            'TITLE_RU' => 'идеальная форма',
            'TITLE_EN' => '&nbsp;',
            'TITLE_CN' => '&nbsp;',
            'DESCRIPTION_RU' => '<p class="page-russian-cut__text"> Чуть позже немалую роль в развитии российской — да и мировой — огранки сыграет бельгиец русского происхождения Марсель Толковский. Инженер по образованию, он в 1919 году разработал для бриллианта идеальное сочетание углов и пропорций, при котором свет, проникающий в камень, создает максимальный блеск и игру. </p> <p class="page-russian-cut__text"> Бриллиант Толковского обязательно должен иметь круглую огранку и 57 граней — сегодня это стандарт, по которому работает весь мир. Огранка Толковского также предполагает точно выдержанные пропорции высоты и диаметра, выверенные углы наклона граней. Чем сильнее отступление от них, тем меньше в камне игры света. Создаваемые в середине 20 века российские гранильные заводы изначально стремились работать близко к этим параметрам </p>',
            'DESCRIPTION_EN' => '&nbsp;',
            'DESCRIPTION_CN' => '&nbsp;',
        ],
        [
            'CODE' => 'high-standards',
            'TITLE_RU' => 'высокие стандарты',
            'TITLE_EN' => '&nbsp;',
            'TITLE_CN' => '&nbsp;',
            'DESCRIPTION_RU' => '<p class="page-russian-cut__photo-text"> В 1977 году российская отрасль получила «технические условия на бриллианты», которые выдвигали жесткие требования к огранке и полировке драгкамней. В это время окончательно формируется Russian Cut, символизирующий высокие стандарты качества. В отличие от зарубежных огранщиков, часто нарушающих пропорции в угоду тому, чтобы камень имел большую массу, русские огранщики работали в точном соответствии с заданными стандартами и не могли от них отступить. Доскональное соблюдение высоких стандартов во всем — в целом отличительная черта России того времени. </p>',
            'DESCRIPTION_EN' => '&nbsp;',
            'DESCRIPTION_CN' => '&nbsp;',
        ],
        [
            'CODE' => 'soviet-legend',
            'TITLE_RU' => 'советская легенда',
            'TITLE_EN' => '&nbsp;',
            'TITLE_CN' => '&nbsp;',
            'DESCRIPTION_RU' => '<p class="page-russian-cut__text"> Советская школа огранки требовала, чтобы все возможные дефекты были исправлены, даже если для этого понадобится существенно снизить массу камня. Огранщик старался обеспечить бриллианту идеальную форму, зачастую не считаясь с возможными потерями, что казалось немыслимым его коллеге из Израиля или Бельгии. Кроме того, в огранку направлялись только наиболее крупное и дорогое алмазное сырье, завозилось современное оборудование. На каждом гранильном заводе работал отдел контроля, который возвращал бриллианты на доработку при наличии малейших недочетов. </p> <p class="page-russian-cut__text"> Задача делать лучшие бриллианты выполнялась в полной мере: российские бриллианты пользовались огромным спросом, предоставляя покупателям гарантию высочайшего качества. Собственно, и само понятие Russian Cut было придумано вовсе не огранщиками, а самими зарубежными клиентами. </p>',
            'DESCRIPTION_EN' => '&nbsp;',
            'DESCRIPTION_CN' => '&nbsp;',
        ],
        [
            'CODE' => 'footer',
            'TITLE_RU' => 'бриллианты алроса',
            'TITLE_EN' => '&nbsp;',
            'TITLE_CN' => '&nbsp;',
            'DESCRIPTION_RU' => '<p class="page-russian-cut__photo-text page-russian-cut__photo-text--cl-global"> Филиал «Бриллианты АЛРОСА», созданный на базе советских предприятий, продолжает эту традицию. Наши сегодняшние стандарты огранки еще жестче, чем требования к качеству бриллиантов 70-х годов. В «Бриллиантах АЛРОСА» работает около 500 высококлассных мастеров, которые еще помнят, что такое настоящий Russian Cut, либо учились у этих специалистов. Вот почему мы можем гарантировать исключительное качество бриллиантов, изготовленных АЛРОСА. </p>',
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

    private function addIB()
    {
        $iblock = (new IBlock())
            ->constructDefault('Русская огранка', \App\Models\About\RussianCut::IBLOCK_CODE, "about")
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
            \App\Models\About\RussianCut::create([
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
        $this->deleteIblockByCode(\App\Models\About\RussianCut::IBLOCK_CODE);
    }
}
