<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Arrilot\BitrixMigrations\Constructors\IBlock;

class EnvironmentalResponsibility20190705143946480354 extends BitrixMigration
{

    private $elements = [
        [
            'CODE' => 'wild_deer',
            'TITLE_RU' => 'wild_deer',
            'TITLE_EN' => '&nbsp;',
            'TITLE_CN' => '&nbsp;',
            'DESCRIPTION_RU' => '<p class="page-responsibility__photo-text page-responsibility__photo-text--cl-global"> Мы помогаем защитить популяцию диких оленей, поэтому с помощью GPS следим за перемещением стад и останавливаем движение в том месте, где они переходят дорогу. А еще мы построили парк-заповедник «Живые алмазы», площадью с Финляндию, в котором больные или редкие северные животные могут жить в естественной среде. </p>',
            'DESCRIPTION_EN' => '<p class="page-responsibility__photo-text page-responsibility__photo-text--cl-global">We help protect the wild deer population, so with the help of GPS we monitor the movement of herds and stop the movement where they cross the road. We have also built the Living Diamonds Park, an area with Finland where sick or rare northern animals can live in a natural environment.</p>',
            'DESCRIPTION_CN' => '&nbsp;',
        ],
        [
            'CODE' => 'nature_up',
            'TITLE_RU' => 'nature_up',
            'TITLE_EN' => '&nbsp;',
            'TITLE_CN' => '&nbsp;',
            'DESCRIPTION_RU' => '<p class="page-responsibility__row-text"> Мы сохраняем живую природу. В водоемы Якутии мы регулярно заселяем рыб, которые очищают их и поддерживают хорошую биологическую среду. Нарушенные бурением или добычей земли — восстанавливаем и засаживаем цветами и деревьями. </p>',
            'DESCRIPTION_EN' => '<p class="page-responsibility__row-text">We\'re keeping nature alive. In the reservoirs of Yakutia, we regularly infiltrate fish, which purify them and maintain a good biological environment. Disrupted by drilling or extraction of land - we restore and plant flowers and trees.</p>',
            'DESCRIPTION_CN' => '&nbsp;',
        ],
        [
            'CODE' => 'garbage',
            'TITLE_RU' => 'garbage',
            'TITLE_EN' => '&nbsp;',
            'TITLE_CN' => '&nbsp;',
            'DESCRIPTION_RU' => '<p class="page-responsibility__row-text">Мы стараемся минимизировать отходы и выбросы. К счастью, в части выбросов алмазодобывающая отрасль является одной из самых «чистых» в горной добыче. Наш производственный цикл не включает в себя пиролиза или других процессов, при которых могли бы испаряться вредные вещества. Основной источник наших выбросов – пыль и выхлопы машин, которые мы тоже стремимся перевести на газовое топливо.</p> <p class="page-responsibility__row-text">Мы также бережно утилизируем мусор. В основном, это пустая порода – камни и песок, оставшиеся после извлечения руды из земли. Они складируются в специально отведенных местах, а иногда и используются на благое дело – например, для отсыпки дорог.</p>',
            'DESCRIPTION_EN' => '<p class="page-responsibility__row-text">We try to minimize waste and emissions. Fortunately, in terms of emissions, the diamond mining industry is one of the cleanest in mining. Our production cycle does not include pyrolysis or other processes that could vaporize harmful substances. The main source of our emissions is dust and machine emissions, which we also seek to convert to gas fuel.</p> <p class="page-responsibility__row-text">We\'re also carefully disposing of the garbage. These are mostly waste rock - rocks and sand left behind after extracting ore from the ground. They are stored in specially designated places, and sometimes used for good deeds - for example, for dumping roads.</p>',
            'DESCRIPTION_CN' => '&nbsp;',
        ],
        [
            'CODE' => 'energy',
            'TITLE_RU' => 'energy',
            'TITLE_EN' => '&nbsp;',
            'TITLE_CN' => '&nbsp;',
            'DESCRIPTION_RU' => '<p class="page-responsibility__photo-text">Мы стараемся беречь энергию. Значительная часть энергии, которую мы используем, идет с гидроэлектростанции, но есть и «классические» источники. Мы переводим наши котельные на газовое топливо, поскольку оно более экологично и не создает выбросов в окружающую среду. Мы также модернизируем оборудование, чтобы оно потребляло меньше энергии на ту же самую работу. </p>',
            'DESCRIPTION_EN' => '<p class="page-responsibility__photo-text">We\'re trying to save energy. Much of the energy we use comes from a hydroelectric power plant, but there are also "classic" sources. We are converting our boilers to gas fuel, because it is more ecological and does not create emissions into the environment. We are also upgrading our equipment so that it consumes less energy for the same work.</p>',
            'DESCRIPTION_CN' => '&nbsp;',
        ],
        [
            'CODE' => 'alrosa_and_nature',
            'TITLE_RU' => 'Алроса и природа',
            'TITLE_EN' => 'ALROSA and nature',
            'TITLE_CN' => '&nbsp;',
            'DESCRIPTION_RU' => '<p class="page-responsibility__intro-text page-responsibility__intro-text--special">  Мы стараемся сохранять и беречь воду. В России с ней нет проблем, но мы знаем, что в мире есть множество регионов, где бутылка воды на вес золота. Сегодня мы свели к минимуму забор воды из внешних источников. Большинство наших производственных мощностей оборудованы системой оборотного водоснабжения – то есть, используют одну и ту же воду по кругу, фильтруя ее после использования и заново запуская в производственную цепочку. Зачем брать новое, если можно использовать то, что уже есть?</p> <p class="page-responsibility__intro-text">Мы также стараемся очищать воду, и свою, и чужую. Являясь ключевой компанией для целого региона, мы оборудуем очистными сооружениями не только свои объекты, но и коммунальные службы городов.</p>',
            'DESCRIPTION_EN' => '<p class="page-responsibility__intro-text page-responsibility__intro-text--special">We\'re trying to preserve the water. There are no problems with it in Russia, but we know that there are many regions in the world where a bottle of water is worth its weight in gold. Today, we have minimized water intake from external sources. Most of our production facilities are equipped with a water recycling system - that is, they use the same water in a circle, filtering it after use and re-launching it into the production chain. Why take a new one if you can use what you already have?</p> <p class="page-responsibility__intro-text">We also try to purify water, both our own and someone else\'s. As a key company for the whole region, we are equipping treatment facilities not only with our own facilities, but also with municipal services.</p>',
            'DESCRIPTION_CN' => '&nbsp;',
        ],
        [
            'CODE' => 'environmental_responsibility',
            'TITLE_RU' => 'Экологическая ответственность',
            'TITLE_EN' => 'Environmental responsibility',
            'TITLE_CN' => '生态责任',
            'DESCRIPTION_RU' => '<p class="page-responsibility__intro-text">Как говорит наш главный эколог, «Человек – не царь природы, а ее арендатор, причем арендует он ее у своих собственных детей». Мало кто мог бы сказать лучше. Вся работа компании выстроена вокруг этого подхода, поэтому АЛРОСА стремится минимизировать негативное воздействие на окружающую среду. Ежегодно мы направляем на эту работу порядка 5 млрд рублей.</p>',
            'DESCRIPTION_EN' => '<p class="page-responsibility__intro-text">As our chief ecologist says, "Man is not the king of nature, but its tenant, and he rents it from his own children. Few people could have said it better. All the work of the company is built around this approach, so ALROSA strives to minimize the negative impact on the environment. Every year we allocate about 5 billion rubles for this work.</p>',
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
            ->constructDefault('Экологическая ответственность', \App\Models\About\EnvironmentalResponsibility::IBLOCK_CODE, "about")
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
            \App\Models\About\EnvironmentalResponsibility::create([
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
        $this->deleteIblockByCode(\App\Models\About\EnvironmentalResponsibility::IBLOCK_CODE);        //
    }
}
