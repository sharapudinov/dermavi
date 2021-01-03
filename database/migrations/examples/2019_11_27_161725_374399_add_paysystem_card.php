<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Bitrix\Main\Loader;
use Bitrix\Sale\PaySystem\Manager;
use Bitrix\Sale\Internals\PaySystemActionTable;
use Bitrix\Sale\Services\PaySystem\Restrictions\Manager as RestrictionsManager;
use App\Core\Sale\PersonType;
use App\Core\Sale\DeliveryServiceWrapper;

/**
 * Class AddPaysystemCard20191127161725374399
 */
class AddPaysystemCard20191127161725374399 extends BitrixMigration
{
    /** @var bool Используем транзакцию */
    public $use_transaction = true;
    
    /**
     * AddPaysystemCard20191127161725374399 constructor.
     * @throws Bitrix\Main\LoaderException
     */
    public function __construct()
    {
        parent::__construct();
        
        Loader::includeModule('sale');
    }
    
    /**
     * Run the migration.
     * @throws Exception
     */
    public function up(): void
    {
        foreach ($this->getPaySystems() as $fields) {
            $restrictions = $fields['RESTRICTIONS'];
            unset($fields['RESTRICTIONS']);
            $res = Manager::add($fields);
            
            if (!$res->isSuccess()) {
                throw new MigrationException("Cannot add paysystem ".$fields['CODE']
                    . '. ' . implode("\n", $res->getErrorMessages()));
            }
            
            foreach ($restrictions as $classRestriction => $restriction) {
                $restriction['SERVICE_ID'] = $res->getId();
                $classRestriction::save($restriction);
            }
        }
    }
    
    /**
     * Reverse the migration.
     * @throws Exception
     */
    public function down(): void
    {
        $ids = [];
        $codes = array_map(function (array $fields) {
            return $fields['CODE'];
        }, $this->getPaySystems());
        
        $rs = PaySystemActionTable::getList();
        while ($paySystem = $rs->fetch()) {
            if (in_array($paySystem['CODE'], $codes)) {
                $ids[] = $paySystem['ID'];
            }
        }
        
        foreach ($ids as $paySystemId) {
            Manager::delete($paySystemId);
        }
    }
    
    /**
     * Возвращает данные для добавляемых платежных систем.
     * @return array
     */
    private function getPaySystems(): array
    {
        return [
            [
                'NAME' => 'Оплата картой',
                'PSA_NAME' => 'Оплата картой',
                'CODE' => 'CREDIT_CARD',
                'SORT' => 400,
                'ACTION_FILE' => '',
                'NEW_WINDOW' => 'N',
                'ACTIVE' => 'Y',
                'HAVE_PAYMENT' => 'Y',
                'ALLOW_EDIT_PAYMENT' => 'Y',
                'IS_CASH' => 'N',
                'CAN_PRINT_CHECK' => 'Y',
                'ENTITY_REGISTRY_TYPE' => 'ORDER',
                'RESTRICTIONS' => [
                    '\Bitrix\Sale\Services\PaySystem\Restrictions\PersonType' => [
                        "SERVICE_TYPE" => RestrictionsManager::SERVICE_TYPE_PAYMENT,
                        "SORT" => 100,
                        "PARAMS" => [
                            'PERSON_TYPE_ID' => [PersonType::getPersonType(PersonType::PHYSICAL_ENTITY)->getPersonTypeId()]
                        ]
                    ],
                    '\Bitrix\Sale\Services\PaySystem\Restrictions\Price' => [
                        "SERVICE_TYPE" => RestrictionsManager::SERVICE_TYPE_PAYMENT,
                        "SORT" => 200,
                        "PARAMS" => [
                            'MIN_VALUE' => 0,
                            'MAX_VALUE' => 100000,
                        ]
                    ]
                ]
            ]
        ];
    }
}
