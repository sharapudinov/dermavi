<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class IblockDiamondTurnOffWorkflow20190415125923279621 extends BitrixMigration
{
    /**
     * Run the migration
     * @throws \Exception
     */
    public function up()
    {
        $iblockId = $this->getIblockIdByCode('diamond');
        if(!$iblockId) {
            throw new MigrationException('Ошибка получения идентификатора инфоблока "Бриллианты"');
        }
        
        $iblock = new CIBlock();
        if(!$iblock->Update($iblockId, ['WORKFLOW' => 'N'])) {
            throw new MigrationException("Ошибка обновления инфоблока \"Бриллианты\": {$iblock->LAST_ERROR}");
        }
    }

    /**
     * Reverse the migration
     * @throws \Exception
     */
    public function down()
    {
        $iblockId = $this->getIblockIdByCode('diamond');
        if(!$iblockId) {
            throw new MigrationException('Ошибка получения идентификатора инфоблока "Бриллианты"');
        }
    
        $iblock = new CIBlock();
        if(!$iblock->Update($iblockId, ['WORKFLOW' => 'Y'])) {
            throw new MigrationException("Ошибка обновления инфоблока \"Бриллианты\": {$iblock->LAST_ERROR}");
        }
    }
}
