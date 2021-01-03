<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Arrilot\BitrixMigrations\Constructors\IBlock;

class CreateIbForRjcPageContent20200729114740746454 extends BitrixMigration
{
    private $elements = [
        [
            'CODE' => 'rjc-first-section',
            'TITLE_RU' => 'Совет по&nbsp;ответственной практике в&nbsp;ювелирном бизнесе',
            'TITLE_EN' => 'Responsible jewellery council',
            'TITLE_CN' => '&nbsp;',
            'DESCRIPTION_RU' => 'Сегодня мировая алмазно-бриллиантовая отрасль сталкивается с&nbsp;рядом вызовов в&nbsp;сфере обеспечения неконфликтного
        происхождения алмазов и&nbsp;ответственных цепочек поставок, повышения прозрачности, исправления неверных представлений
        общественности о&nbsp;ее&nbsp;деятельности. Они могут существенно подорвать устойчивое развитие отрасли и&nbsp;поэтому
        требуют от&nbsp;всех ее&nbsp;участников согласованных действий. В&nbsp;связи с&nbsp;этим АЛРОСА проводит активную
        работу, направленную на&nbsp;создание и&nbsp;повышение эффективности международных отраслевых механизмов
        по&nbsp;продвижению ответственных бизнес-практик, развитию системы отраслевого саморегулирования и&nbsp;укреплению
        доверия потребителей в&nbsp;рамках многостороннего сотрудничества в&nbsp;международных отраслевых организациях.',
            'DESCRIPTION_EN' => '&nbsp;',
            'DESCRIPTION_CN' => '&nbsp;',
        ],
        [
            'CODE' => 'rjc-about-first-block',
            'TITLE_RU' => 'Об RJC',
            'TITLE_EN' => '&nbsp;',
            'TITLE_CN' => '&nbsp;',
            'DESCRIPTION_RU' => 'RJC&nbsp;&mdash; международная некоммерческая организация, занимающаяся сертификацией и&nbsp;установлением стандартов.
            В&nbsp;ее&nbsp;состав входят более 1100&nbsp;компаний, охватывающих всю цепочку поставок ювелирных изделий
            от&nbsp;месторождения до&nbsp;розничной продажи.',
            'DESCRIPTION_EN' => '&nbsp;',
            'DESCRIPTION_CN' => '&nbsp;',
        ],
        [
            'CODE' => 'rjc-about-second-block',
            'TITLE_RU' => 'Об RJC (второй блок)',
            'TITLE_EN' => '&nbsp;',
            'TITLE_CN' => '&nbsp;',
            'DESCRIPTION_RU' => 'Члены RJC обязуются следовать Кодексу ответственных практик&nbsp;&mdash; международному стандарту ответственного ведения
                    бизнеса по&nbsp;добыче алмазов, золота и&nbsp;металлов платиновой группы. Кодекс затрагивает права человека, трудовые
                    права, влияние на&nbsp;окружающую среду, добывающую деятельность, раскрытие информации и&nbsp;множество других важных
                    тем в&nbsp;цепочке поставок. RJC также занимается многосторонними инициативами по&nbsp;ответственному подбору
                    поставщиков и&nbsp;комплексной юридической проверке цепочки поставок. Сертификация цепочки поставок драгоценных
                    металлов, проводимая RJC, поддерживает эти инициативы и&nbsp;может быть использована в&nbsp;качестве инструмента для
                    обеспечения более широкого круга возможностей для членов и&nbsp;заинтересованных сторон.',
            'DESCRIPTION_EN' => '&nbsp;',
            'DESCRIPTION_CN' => '&nbsp;',
        ],
        [
            'CODE' => 'rjc-about-third-block',
            'TITLE_RU' => 'Система сертификации RJC',
            'TITLE_EN' => '&nbsp;',
            'TITLE_CN' => '&nbsp;',
            'DESCRIPTION_RU' => '<p class="page-jewellery-council__sect-cols-text page-jewellery-council__sect-cols-text--l">Система сертификации RJC обеспечивает компании, вовлеченные в&nbsp;цепочку поставок алмазов, золота и&nbsp;металлов платиновой группы, возможностью продемонстрировать свою приверженность этическим нормам и&nbsp;практикам ответственного ведения бизнеса.</p>
                    <p class="page-jewellery-council__sect-cols-text page-jewellery-council__sect-cols-text--l">RJC является полноправным участником ISEAL Alliance&nbsp;&mdash; мировой ассоциации по&nbsp;стандартам устойчивого развития. Дополнительная информация об&nbsp;участниках RJC, сертификации и&nbsp;стандартах&nbsp;&mdash; <a class="link" href="mailto:responsiblejewellery.com">responsiblejewellery.com</a></p>',
            'DESCRIPTION_EN' => '&nbsp;',
            'DESCRIPTION_CN' => '&nbsp;',
        ],
        [
            'CODE' => 'alrosa-in-rjc-first-block',
            'TITLE_RU' => 'АЛРОСА в RJC',
            'TITLE_EN' => '&nbsp;',
            'TITLE_CN' => '&nbsp;',
            'DESCRIPTION_RU' => '<h3 class="page-jewellery-council__rjc-history-year">В 2016 году</h3>
                                <p class="page-jewellery-council__rjc-history-text">АЛРОСА вошла <br> в&nbsp;состав RJC</p>------<h3 class="page-jewellery-council__rjc-history-year">В 2017 году</h3>
                                <p class="page-jewellery-council__rjc-history-text">АЛРОСА была избрана <br> в&nbsp;Совет директоров RJC</p>------<h3 class="page-jewellery-council__rjc-history-year">В 2018-2019 годах</h3>
                                <p class="page-jewellery-council__rjc-history-text">
                                    Представители АЛРОСА избраны на&nbsp;должности заместителя председателя и&nbsp;члена Комитета по&nbsp;стандартам
                                </p>',
            'DESCRIPTION_EN' => '&nbsp;',
            'DESCRIPTION_CN' => '&nbsp;',
        ],
        [
            'CODE' => 'alrosa-in-rjc-second-block',
            'TITLE_RU' => 'АЛРОСА в RJC (второй блок)',
            'TITLE_EN' => '&nbsp;',
            'TITLE_CN' => '&nbsp;',
            'DESCRIPTION_RU' => '<p class="page-jewellery-council__text-container">Для проверки соответствия деятельности АЛРОСА Кодексу ответственных практик RJC была привлечена крупная независимая аудиторская компания PwC. В&nbsp;течение нескольких месяцев АЛРОСА прошла всесторонний аудит, охватывающий программы социальной ответственности, защиту окружающей среды, а&nbsp;также этические принципы ведения бизнеса&nbsp;&mdash;
            противодействие коррупции, соблюдение прав человека, обеспечение достойных условий труда. В&nbsp;рамках аудита
            представители RJC и&nbsp;компании-аудитора посетили объекты АЛРОСА, включая основные производственные
            площадки&nbsp;&mdash; Мирнинский, Айхальский, Удачнинский горно-обогатительные комбинаты, дочерние предприятия
            компании&nbsp;&mdash; &laquo;АЛРОСА-Нюрба&raquo;, &laquo;Алмазы Анабара&raquo; и&nbsp;&laquo;Севералмаз&raquo;, центры
            сортировки алмазного сырья в&nbsp;Мирном и&nbsp;Архангельске.</p>
        <p class="page-jewellery-council__text-container">По&nbsp;итогам аудита АЛРОСА получила сертификат на&nbsp;максимально возможный срок&nbsp;&mdash; 3&nbsp;года.</p>
        <p class="page-jewellery-council__text-container">
            <a href="javascript:;" target="_blank" rel="noopener noreferer">
                Подробнее о&nbsp;сертификации АЛРОСА
                <svg class="icon icon--external-link page-jewellery-council__link-icon">
                    <use xlink:href="#icon-external_link"></use>
                </svg>
            </a>
        </p>',
            'DESCRIPTION_EN' => '&nbsp;',
            'DESCRIPTION_CN' => '&nbsp;',
        ],
        [
            'CODE' => 'rjc-standards',
            'TITLE_RU' => 'Развитие стандартов RJC',
            'TITLE_EN' => '&nbsp;',
            'TITLE_CN' => '&nbsp;',
            'DESCRIPTION_RU' => 'В&nbsp;настоящее время мы&nbsp;активно занимаемся продвижением и&nbsp;реформированием стандартов RJC. В&nbsp;апреле 2019 года основной документ&nbsp;&mdash; Кодекс ответственных практик&nbsp;&mdash; был существенно обновлен. К&nbsp;числу основных улучшений относятся приведение требований в&nbsp;соответствие с&nbsp;Руководящими принципами ОЭСР
            по&nbsp;ответственным цепочкам поставок минеральных ресурсов, усиление обязательств по&nbsp;соблюдению прав человека
            на&nbsp;основе Руководящих принципов ООН по&nbsp;предпринимательской деятельности в&nbsp;аспекте прав человека
            и&nbsp;охраны окружающей среды, а&nbsp;также новые требования по&nbsp;обнаружению искусственно выращенных алмазов для
            защиты прав потребителей.',
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
            ->constructDefault('Совет по ответственной практике в ювелирном бизнесе', \App\Models\About\ResponsibleJewelleryCouncil::IBLOCK_CODE, "about")
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
            \App\Models\About\ResponsibleJewelleryCouncil::create([
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
        $this->deleteIblockByCode(\App\Models\About\ResponsibleJewelleryCouncil::IBLOCK_CODE);
    }
}
