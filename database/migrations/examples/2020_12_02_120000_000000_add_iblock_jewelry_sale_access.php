<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Arrilot\BitrixMigrations\Constructors\IBlock;

final class AddIblockJewelrySaleAccess20201202120000000000 extends BitrixMigration
{
    private const IBLOCK_CODE = 'jewelry_sale_access';

    private const IBLOCK_TYPE = 'client';

    /**
     * @throws Exception
     */
    public function up()
    {
        $this->createIBlock();
    }

    /**
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
            ->constructDefault('Доступ к распродаже ювелирных изделий', static::IBLOCK_CODE, static::IBLOCK_TYPE)
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
        $factory->fields['FIELDS'] = [
            'CODE' => [
                'IS_REQUIRED' => 'Y', // Обязательное
            ],
        ];

        try {
            $iblockId = $factory->add();
            $this->formEdit($iblockId);
        } catch (Exception $exception) {
        }

        if (!$iblockId) {
            return $iblockId;
        }

        return $iblockId;
    }

    /**
     * @param int $iblockId
     */
    private function formEdit(int $iblockId): void
    {
        // Кастомизируем форму редактирования элемента
        if (!$iblockId) {
            return;
        }

        $tmpSettings = [
            'tabs' => str_replace(
                ["\n", "\r", "\t"],
                '',
                'edit1--#--Элемент--
					,--ACTIVE--#--Активность--
					,--NAME--#--*Имя--
					,--CODE--#--*E-mail пользователя--
				;--'
            )
        ];
        CUserOptions::SetOption('form', 'form_element_' . $iblockId, $tmpSettings, $common = true);
    }
}
