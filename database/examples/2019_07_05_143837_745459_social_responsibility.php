<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Arrilot\BitrixMigrations\Constructors\IBlock;

class SocialResponsibility20190705143837745459 extends BitrixMigration
{

    private $elements = [
        [
            'CODE' => 'buildings',
            'TITLE_RU' => 'Мы строим жилые дома',
            'TITLE_EN' => '&nbsp;',
            'TITLE_CN' => '&nbsp;',
            'DESCRIPTION_RU' => '<p class="page-responsibility__photo-text"> Мы строим жилые дома, коммуникации и дороги, больницы и реабилитационные центры, спортивные объекты, школы и детские сады, которыми могут пользоваться все без исключения. </p>',
            'DESCRIPTION_EN' => '&nbsp;',
            'DESCRIPTION_CN' => '&nbsp;',
        ],
        [
            'CODE' => 'altruism',
            'TITLE_RU' => 'altruism',
            'TITLE_EN' => '&nbsp;',
            'TITLE_CN' => '&nbsp;',
            'DESCRIPTION_RU' => '<p class="page-responsibility__row-text"> Мы добываем алмазы в Якутии, поэтому считаем своим долгом помогать местному населению и региону. Мы поддерживаем экономические, социальные, инфраструктурные, инновационные проекты в Якутии, в том числе в тех мелких деревеньках, где вовсе нет никаких алмазов. </p>',
            'DESCRIPTION_EN' => '&nbsp;',
            'DESCRIPTION_CN' => '&nbsp;',
        ],
        [
            'CODE' => 'social_comfort',
            'TITLE_RU' => 'social_comfort',
            'TITLE_EN' => '&nbsp;',
            'TITLE_CN' => '&nbsp;',
            'DESCRIPTION_RU' => '<p class="page-responsibility__row-text"> Еще один залог стабильности — достойные условия жизни, комфортная среда обитания для людей. Большинство сотрудников АЛРОСА вместе с их семьями живет в Якутии, в небольших моногородах, созданных вокруг алмазодобывающих предприятий, на значительном удалении от крупных населенных пунктов. Поэтому наша задача — сделать их жизнь не слишком отличающейся от жизни в крупных городах. </p> <p class="page-responsibility__row-text"> АЛРОСА ежегодно реализует масштабные социальные программы для работников и членов их семей, в рамках которых обеспечивает их здравоохранение и отдых, возможность заниматься спортом и посещать творческие кружки, культурные мероприятия; помогает с жильем и ипотекой, выплачивает негосударственные пенсии. </p>',
            'DESCRIPTION_EN' => '&nbsp;',
            'DESCRIPTION_CN' => '&nbsp;',
        ],
        [
            'CODE' => 'growth_stability',
            'TITLE_RU' => 'Основы стабильности алроса',
            'TITLE_EN' => '&nbsp;',
            'TITLE_CN' => '&nbsp;',
            'DESCRIPTION_RU' => '<p class="page-responsibility__intro-text"> Залогом успешного развития любой компании являются не только объемы запасов или технологии, но прежде всего — трудовой коллектив. Сегодня команда АЛРОСА — это 37 тысяч профессионалов, мастеров своего дела. Мы хотим быть компанией, которой гордятся ее сотрудники. Вот почему мы обеспечиваем для них лучшие условия труда. Сегодня средняя зарплата сотрудников АЛРОСА вдвое выше, чем средняя зарплата в Якутии, и втрое выше, чем средняя зарплата в России. </p>',
            'DESCRIPTION_EN' => '&nbsp;',
            'DESCRIPTION_CN' => '&nbsp;',
        ],
        [
            'CODE' => 'social_responsibility',
            'TITLE_RU' => 'Социальная ответственность',
            'TITLE_EN' => '&nbsp;',
            'TITLE_CN' => '社会责任',
            'DESCRIPTION_RU' => '<p class="page-responsibility__intro-text"> АЛРОСА по праву может называть себя не только самой крупной алмазодобывающей компанией, но и самой социально ответственной. По данным отчета PwC, АЛРОСА — лидер алмазодобывающей и золотодобывающей отрасли по доле отчислений на социальные программы. </p>',
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
            ->constructDefault('Социальная ответственность', \App\Models\About\SocialResponsibility::IBLOCK_CODE, "about")
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
            \App\Models\About\SocialResponsibility::create([
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
        $this->deleteIblockByCode(\App\Models\About\SocialResponsibility::IBLOCK_CODE);        //
    }
}
