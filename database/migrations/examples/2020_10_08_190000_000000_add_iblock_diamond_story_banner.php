<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\IBlockProperty;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Arrilot\BitrixMigrations\Constructors\IBlock;

final class AddIblockDiamondStoryBanner20201008190000000000 extends BitrixMigration
{
    private const IBLOCK_CODE = 'diamond_story_banner';

    private const IBLOCK_TYPE = 'banners';

    /**
     * Run the migration.
     * @throws Exception
     */
    public function up()
    {
        $this->createIBlock();
    }

    /**
     * Reverse the migration.
     * @throws MigrationException
     */
    public function down()
    {
        $this->deleteIblockByCode(static::IBLOCK_CODE);
    }

    /**
     * @return int
     */
    private function createIBlock(): int
    {
        $iblockId = 0;
        try {
            $iblockId = $this->getIblockIdByCode(static::IBLOCK_CODE, static::IBLOCK_TYPE);
        } catch (Exception $exception) {
            // it's ok
        }

        if ($iblockId) {
            return $iblockId;
        }

        $factory = (new IBlock())
            ->constructDefault('Баннер для страницы "Путь бриллианта"', static::IBLOCK_CODE, static::IBLOCK_TYPE)
            ->setVersion(2)
            ->setIndexElement(false)
            ->setIndexSection(false)
            ->setWorkflow(false)
            ->setBizProc(false)
            ->setSort(5000)
            ->setListPageUrl('')
            ->setDetailPageUrl('')
            ->setSectionPageUrl('');

        $factory->fields['LID'] = ['s1', 's2', 's3'];
        $factory->fields['GROUP_ID'] = [1 => 'X', 2 => 'R'];

        try {
            $iblockId = $factory->add();
        } catch (Exception $exception) {
        }

        if (!$iblockId) {
            return $iblockId;
        }

        // ---
        $prop = (new IBlockProperty())
            ->constructDefault('IMAGE_MAIN_RU', 'Изображение основное (рус)', $iblockId)
            ->setSort(100)
            ->setPropertyTypeFile('png,jpg,jpeg,webp,gif')
            ->setFiltrable(false)
            ->setIsRequired(false)
            ->setHint('');
        try {
            $prop->add();
        } catch (Exception $exception) {
            // ignore
        }

        $prop = (new IBlockProperty())
            ->constructDefault('IMAGE_ADAPTIVE_RU', 'Изображение адаптив (рус)', $iblockId)
            ->setSort(200)
            ->setPropertyTypeFile('png,jpg,jpeg,webp,gif')
            ->setFiltrable(false)
            ->setIsRequired(false)
            ->setHint('');
        try {
            $prop->add();
        } catch (Exception $exception) {
            // ignore
        }

        // ---
        $prop = (new IBlockProperty())
            ->constructDefault('IMAGE_MAIN_EN', 'Изображение основное (англ)', $iblockId)
            ->setSort(300)
            ->setPropertyTypeFile('png,jpg,jpeg,webp,gif')
            ->setFiltrable(false)
            ->setIsRequired(false)
            ->setHint('Выводится по умолчанию, если не задано значение для других версий');
        try {
            $prop->add();
        } catch (Exception $exception) {
            // ignore
        }

        $prop = (new IBlockProperty())
            ->constructDefault('IMAGE_ADAPTIVE_EN', 'Изображение адаптив (англ)', $iblockId)
            ->setSort(400)
            ->setPropertyTypeFile('png,jpg,jpeg,webp,gif')
            ->setFiltrable(false)
            ->setIsRequired(false)
            ->setHint('Выводится по умолчанию, если не задано значение для других версий');
        try {
            $prop->add();
        } catch (Exception $exception) {
            // ignore
        }

        // ---
        $prop = (new IBlockProperty())
            ->constructDefault('IMAGE_MAIN_CN', 'Изображение основное (кит)', $iblockId)
            ->setSort(500)
            ->setPropertyTypeFile('png,jpg,jpeg,webp,gif')
            ->setFiltrable(false)
            ->setIsRequired(false)
            ->setHint('');
        try {
            $prop->add();
        } catch (Exception $exception) {
            // ignore
        }

        $prop = (new IBlockProperty())
            ->constructDefault('IMAGE_ADAPTIVE_CN', 'Изображение адаптив (кит)', $iblockId)
            ->setSort(600)
            ->setPropertyTypeFile('png,jpg,jpeg,webp,gif')
            ->setFiltrable(false)
            ->setIsRequired(false)
            ->setHint('');
        try {
            $prop->add();
        } catch (Exception $exception) {
            // ignore
        }

        //
        // ---
        //
        $prop = (new IBlockProperty())
            ->constructDefault('TITLE_RU', 'Заголовок (рус)', $iblockId)
            ->setSort(700)
            ->setFiltrable(false)
            ->setIsRequired(false)
            ->setHint('');
        try {
            $prop->add();
        } catch (Exception $exception) {
            // ignore
        }

        $prop = (new IBlockProperty())
            ->constructDefault('SUBTITLE_RU', 'Подзаголовок (рус)', $iblockId)
            ->setSort(800)
            ->setFiltrable(false)
            ->setIsRequired(false)
            ->setHint('');
        try {
            $prop->add();
        } catch (Exception $exception) {
            // ignore
        }

        // ---
        $prop = (new IBlockProperty())
            ->constructDefault('TITLE_EN', 'Заголовок (англ)', $iblockId)
            ->setSort(900)
            ->setFiltrable(false)
            ->setIsRequired(false)
            ->setHint('');
        try {
            $prop->add();
        } catch (Exception $exception) {
            // ignore
        }

        $prop = (new IBlockProperty())
            ->constructDefault('SUBTITLE_EN', 'Подзаголовок (англ)', $iblockId)
            ->setSort(1000)
            ->setFiltrable(false)
            ->setIsRequired(false)
            ->setHint('');
        try {
            $prop->add();
        } catch (Exception $exception) {
            // ignore
        }

        // ---
        $prop = (new IBlockProperty())
            ->constructDefault('TITLE_CN', 'Заголовок (кит)', $iblockId)
            ->setSort(1100)
            ->setFiltrable(false)
            ->setIsRequired(false)
            ->setHint('');
        try {
            $prop->add();
        } catch (Exception $exception) {
            // ignore
        }

        $prop = (new IBlockProperty())
            ->constructDefault('SUBTITLE_CN', 'Подзаголовок (кит)', $iblockId)
            ->setSort(1200)
            ->setFiltrable(false)
            ->setIsRequired(false)
            ->setHint('');
        try {
            $prop->add();
        } catch (Exception $exception) {
            // ignore
        }

        //
        // ---
        //
        $prop = (new IBlockProperty())
            ->constructDefault('BUTTON_TEXT_RU', 'Кнопка (рус)', $iblockId)
            ->setSort(1300)
            ->setFiltrable(false)
            ->setIsRequired(false)
            ->setHint('');
        try {
            $prop->add();
        } catch (Exception $exception) {
            // ignore
        }

        $prop = (new IBlockProperty())
            ->constructDefault('BUTTON_URL_RU', 'Ссылка с кнопки (рус)', $iblockId)
            ->setSort(1400)
            ->setFiltrable(false)
            ->setIsRequired(false)
            ->setHint('Для вывода кнопки должна быть задана пара значений: кнока и ссылка');
        try {
            $prop->add();
        } catch (Exception $exception) {
            // ignore
        }

        // ---
        $prop = (new IBlockProperty())
            ->constructDefault('BUTTON_TEXT_EN', 'Кнопка (англ)', $iblockId)
            ->setSort(1500)
            ->setFiltrable(false)
            ->setIsRequired(false)
            ->setHint('');
        try {
            $prop->add();
        } catch (Exception $exception) {
            // ignore
        }

        $prop = (new IBlockProperty())
            ->constructDefault('BUTTON_URL_EN', 'Ссылка с кнопки (англ)', $iblockId)
            ->setSort(1600)
            ->setFiltrable(false)
            ->setIsRequired(false)
            ->setHint('Для вывода кнопки должна быть задана пара значений: кнока и ссылка');
        try {
            $prop->add();
        } catch (Exception $exception) {
            // ignore
        }

        // ---
        $prop = (new IBlockProperty())
            ->constructDefault('BUTTON_TEXT_CN', 'Кнопка (кит)', $iblockId)
            ->setSort(1700)
            ->setFiltrable(false)
            ->setIsRequired(false)
            ->setHint('');
        try {
            $prop->add();
        } catch (Exception $exception) {
            // ignore
        }

        $prop = (new IBlockProperty())
            ->constructDefault('BUTTON_URL_CN', 'Ссылка с кнопки (кит)', $iblockId)
            ->setSort(1800)
            ->setFiltrable(false)
            ->setIsRequired(false)
            ->setHint('Для вывода кнопки должна быть задана пара значений: кнока и ссылка');
        try {
            $prop->add();
        } catch (Exception $exception) {
            // ignore
        }

        return $iblockId;
    }
}
