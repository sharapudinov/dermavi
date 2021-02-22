<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\IBlock;
use Arrilot\BitrixMigrations\Constructors\IBlockProperty;
use Arrilot\BitrixIblockHelper\IblockId;
use Arrilot\BitrixIblockHelper\HLblock;

class ArticlesAddIb20190627160734073633 extends BitrixMigration
{
    const IBLOCK_TYPE_ID = 'articles';

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        
        $this->addIBArtilceCategories();

        $this->addIBArtilceGridElements();

        $this->addIBArticleList();

    }

    private function addIBArticleList()
    {
        $iblock = (new IBlock())
            ->constructDefault('Статьи', \App\Models\Blog\Article::IBLOCK_CODE, self::IBLOCK_TYPE_ID)
            ->setVersion(2)
            ->setIndexElement(false)
            ->setIndexSection(false)
            ->setWorkflow(false)
            ->setBizProc(false)
            ->setSort(400);

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
                "IS_REQUIRED" => "N",
                "IBLOCK_ID" => $iblockId,
            ],
            [
                "NAME" => "Превью текст (рус)",
                "SORT" => "100",
                "CODE" => "PREVIEW_TEXT_RU",
                "PROPERTY_TYPE" => "S",
                "USER_TYPE" => "HTML",
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "Y",
                "IBLOCK_ID" => $iblockId,
            ],
            [
                "NAME" => "Превью текст (анг)",
                "SORT" => "100",
                "CODE" => "PREVIEW_TEXT_EN",
                "PROPERTY_TYPE" => "S",
                "USER_TYPE" => "HTML",
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "Y",
                "IBLOCK_ID" => $iblockId,
            ],
            [
                "NAME" => "Превью текст (кит)",
                "SORT" => "100",
                "CODE" => "PREVIEW_TEXT_CN",
                "PROPERTY_TYPE" => "S",
                "USER_TYPE" => "HTML",
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "N",
                "IBLOCK_ID" => $iblockId,
            ],
            [
                "NAME" => "Изображение в списке статей",
                "CODE" => "IMAGE_LIST_PAGE",
                "PROPERTY_TYPE" => "F",
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "Y",
                "IBLOCK_ID" => $iblockId,
            ],
            [
                "NAME" => "Изображение на детальной странице",
                "CODE" => "IMAGE_DETAIL_PAGE",
                "PROPERTY_TYPE" => "F",
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "Y",
                "IBLOCK_ID" => $iblockId,
            ],
            [
                'NAME' => 'Категория',
                "CODE" => 'CATEGORY',
                'PROPERTY_TYPE' => 'E',
                'MULTIPLE' => 'N',
                'IS_REQUIRED' => 'N',
                'IBLOCK_ID' => $iblockId
            ],
            /*[
                'NAME' => 'Тип показа в списке',
                'CODE' => 'SHOW_TYPE',
                'PROPERTY_TYPE' => 'L',
                "VALUES" => [
                    [
                        "XML_ID" => "WIDE",
                        "VALUE" => "Широкий(вся строка)",
                        "DEF" => "N",
                        "SORT" => "100",
                    ],
                    [
                        "XML_ID" => "WIDE-REVERSE",
                        "VALUE" => "Широкий(вся строка) зеркальный",
                        "DEF" => "N",
                        "SORT" => "100",
                    ],
                    [
                        "XML_ID" => "MEDIUM",
                        "VALUE" => "Средний(1\\2 строки)",
                        "DEF" => "N",
                        "SORT" => "100",
                    ],
                    [
                        "XML_ID" => "SMALL",
                        "VALUE" => "Маленький(1\\3 строки)",
                        "DEF" => "N",
                        "SORT" => "100",
                    ],
                ],
                'MULTIPLE' => 'N',
                'IS_REQUIRED' => 'N',
                'IBLOCK_ID' => $iblockId,
            ],*/
            [
                'NAME' => 'Раздел грида',
                'CODE' => 'GRID_SECTION',
                'PROPERTY_TYPE' => 'G',
                'LIST_TYPE' => 'L',
                'LINK_IBLOCK_ID' => \App\Models\Blog\GridElement::iblockID(),
                'MULTIPLE' => 'Y',
                'IS_REQUIRED' => 'N',
                'IBLOCK_ID' => $iblockId
            ]

        ];

        foreach ($props as $prop) {
            $this->addIblockElementProperty($prop);
        }
    }

    private function addIBArtilceCategories()
    {
        $iblock = (new IBlock())
            ->constructDefault('Разделы статей', \App\Models\Blog\Category::IBLOCK_CODE, self::IBLOCK_TYPE_ID)
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
                "IS_REQUIRED" => "N",
                "IBLOCK_ID" => $iblockId,
            ],
        ];

        foreach ($props as $prop) {
            $this->addIblockElementProperty($prop);
        }
    }

    private function addIBArtilceGridElements()
    {
        $iblock = (new IBlock())
            ->constructDefault('Элементы грида', \App\Models\Blog\GridElement::IBLOCK_CODE, self::IBLOCK_TYPE_ID)
            ->setVersion(2)
            ->setIndexElement(false)
            ->setIndexSection(false)
            ->setWorkflow(false)
            ->setBizProc(false)
            ->setSort(600);

        $iblock->fields['LID'] = ['s1', 's2', 's3'];
        $iblock->fields['GROUP_ID'] = [1 => 'X', 2 => 'R'];

        $iblockId = $iblock->add();

        $props = [
            [
                'NAME' => 'Тип отображения',
                'CODE' => 'SHOW_TYPE',
                'SORT' => 100,
                'PROPERTY_TYPE' => 'L',
                "VALUES" => [
                    [
                        "XML_ID" => \App\Models\Blog\GridElement::SHOW_TYPE_TEXT,
                        "VALUE" => "Текст",
                        "DEF" => "N",
                        "SORT" => "100",
                    ],
                    [
                        "XML_ID" => \App\Models\Blog\GridElement::SHOW_TYPE_IMAGE_LEFT,
                        "VALUE" => "Текст с картинкой слева",
                        "DEF" => "N",
                        "SORT" => "100",
                    ],
                    [
                        "XML_ID" => \App\Models\Blog\GridElement::SHOW_TYPE_IMAGE_RIGHT,
                        "VALUE" => "Текст с картинкой справа",
                        "DEF" => "N",
                        "SORT" => "100",
                    ],
                    [
                        "XML_ID" => \App\Models\Blog\GridElement::SHOW_TYPE_VIDEO,
                        "VALUE" => "Видео",
                        "DEF" => "N",
                        "SORT" => "100",
                    ],
                    [
                        "XML_ID" => \App\Models\Blog\GridElement::SHOW_TYPE_TIP,
                        "VALUE" => "Подсказка",
                        "DEF" => "N",
                        "SORT" => "100",
                    ],
                ],
                'MULTIPLE' => 'N',
                'IS_REQUIRED' => 'Y',
                'IBLOCK_ID' => $iblockId,
            ],
            [
                "NAME" => "Заголовок (рус)",
                "SORT" => "100",
                "CODE" => "TITLE_RU",
                "PROPERTY_TYPE" => "S",
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "N",
                "IBLOCK_ID" => $iblockId,
            ],
            [
                "NAME" => "Заголовок (анг)",
                "SORT" => "100",
                "CODE" => "TITLE_EN",
                "PROPERTY_TYPE" => "S",
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "N",
                "IBLOCK_ID" => $iblockId,
            ],
            [
                "NAME" => "Заголовок (кит)",
                "SORT" => "100",
                "CODE" => "TITLE_CN",
                "PROPERTY_TYPE" => "S",
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "N",
                "IBLOCK_ID" => $iblockId,
            ],
            [
                "NAME" => "Текст (рус)",
                "SORT" => "100",
                "CODE" => "TEXT_RU",
                "PROPERTY_TYPE" => "S",
                "USER_TYPE" => "HTML",
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "N",
                "IBLOCK_ID" => $iblockId,
            ],
            [
                "NAME" => "Текст (анг)",
                "SORT" => "100",
                "CODE" => "TEXT_EN",
                "PROPERTY_TYPE" => "S",
                "USER_TYPE" => "HTML",
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "N",
                "IBLOCK_ID" => $iblockId,
            ],
            [
                "NAME" => "Текст (кит)",
                "SORT" => "100",
                "CODE" => "TEXT_CN",
                "PROPERTY_TYPE" => "S",
                "USER_TYPE" => "HTML",
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "N",
                "IBLOCK_ID" => $iblockId,
            ],
            [
                "NAME" => "Изображение в гриде",
                "SORT" => "100",
                "CODE" => "IMAGE",
                "PROPERTY_TYPE" => "F",
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "N",
                "IBLOCK_ID" => $iblockId,
            ],
            [
                "NAME" => "Кнопка (текст)",
                "SORT" => "100",
                "CODE" => "BUTTON_TEXT",
                "PROPERTY_TYPE" => "S",
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "N",
                "IBLOCK_ID" => $iblockId,
            ],
            [
                "NAME" => "Кнопка (ссылка)",
                "SORT" => "100",
                "CODE" => "BUTTON_URL",
                "PROPERTY_TYPE" => "S",
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "N",
                "IBLOCK_ID" => $iblockId,
            ],
            [
                "NAME" => "Видео ссылка(YouTube)",
                "SORT" => "100",
                "CODE" => "VIDEO_URL",
                "PROPERTY_TYPE" => "S",
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "N",
                "IBLOCK_ID" => $iblockId,
            ],
        ];

        foreach ($props as $prop) {
            $this->addIblockElementProperty($prop);
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
        $this->deleteIblockByCode(\App\Models\Blog\Article::IBLOCK_CODE);
        $this->deleteIblockByCode(\App\Models\Blog\Category::IBLOCK_CODE);
        $this->deleteIblockByCode(\App\Models\Blog\GridElement::IBLOCK_CODE);
    }
}
