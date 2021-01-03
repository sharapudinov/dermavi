<?php

namespace App\Core\Sale;

use App\Core\Sale\Entity\PaySystem as PaySystemEntity;
use App\Core\SprintOptions\OrderSettings;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\Loader;
use Bitrix\Sale\BasketBase;
use CSalePaySystem;
use Illuminate\Support\Collection;
use Bitrix\Sale\PaySystem\Manager;
use Bitrix\Sale\Services\PaySystem\Restrictions\PersonType as RestrictionPersonType;
use Bitrix\Sale\Services\PaySystem\Restrictions\Delivery as RestrictionDelivery;
use Bitrix\Sale\Services\PaySystem\Restrictions\Price as RestrictionPrice;
use Bitrix\Sale\Services\PaySystem\Restrictions\Site as RestrictionSite;

Loader::IncludeModule('sale');

/**
 * Класс для работы с платежными системами
 * Class PaySystem
 * @package App\Core\Sale
 */
class PaySystem
{
    /**
     * Код платежной системы "Внутренний счёт"
     */
    const INNER_PS_CODE = 'INNER';
    /**
     * @var Collection|PaySystemEntity[] - доступные платежные системы
     */
    private static $instances;

    /**
     * Получаем информацию о платежной системе
     *
     * @param string $code - Символьный код
     * @return PaySystemEntity
     */
    public static function getPaySystem(string $code): PaySystemEntity
    {
        $paySystemInfo = CSalePaySystem::GetList([], ['CODE' => $code], false, false, ['ID'])->Fetch();
        return new PaySystemEntity($paySystemInfo);
    }

    /**
     * Получаем информацию о платежной системе
     *
     * @param string $id - идентификатор платежной системы
     * @return PaySystemEntity
     */
    public static function getById(string $id): PaySystemEntity
    {
        $paySystemInfo = CSalePaySystem::GetList([], ['ID' => $id], false, false, ['ID','CODE'])->Fetch();
        return new PaySystemEntity($paySystemInfo);
    }

    /**
     * @throws ArgumentException
     */
    protected static function loadInstances(): void
    {
        if(is_null(static::$instances)) {
            static::$instances = collect();
            $result = Manager::getList(array(
                'filter' => array('ACTIVE' => 'Y'),
                'order' => array('SORT' => 'ASC', 'NAME' => 'ASC')
            ));
            while ($paySystemInfo = $result->fetch()) {
                $paySystem = new PaySystemEntity($paySystemInfo);
                static::$instances->put($paySystem->getCode(), $paySystem);
            }
        }
    }

    /**
     * Получить платежные системы для доставки
     *
     * @param BasketBase $basket
     * @return Collection
     * @throws ArgumentException
     */
    public static function getDeliveryPaySystems(BasketBase $basket): Collection
    {
        return static::getPaySystemsWithRestrictions(OrderSettings::getCccbServiceId(), $basket);
    }

    /**
     * Получить платежные системы для самовывоза
     *
     * @param BasketBase $basket
     * @return Collection
     * @throws ArgumentException
     */
    public static function getPickupPaySystems(BasketBase $basket): Collection
    {
        return static::getPaySystemsWithRestrictions(OrderSettings::getPickupServiceId(), $basket);
    }

    /**
     * Получить платежные системы с учетом ограничений
     *
     * @param int $deliveryServiceId
     * @param BasketBase $basket
     * @param bool $skipInnerPaySystem
     * @return Collection
     * @throws ArgumentException
     * @throws \Bitrix\Main\ArgumentNullException
     */
    protected static function getPaySystemsWithRestrictions(
        int $deliveryServiceId,
        BasketBase $basket,
        bool $skipInnerPaySystem = true
    ): Collection
    {
        $deliveryPaySystems = collect();

        $user = user();
        $personTypeId = $user ? PersonType::getPersonType($user->getUserEntityTypeCode())->getPersonTypeId() : 0;
        $orderPrice = $basket->getPrice();

        static::loadInstances();
        foreach (static::$instances as $instance) {

            $result = true;
            if ($skipInnerPaySystem && $instance->getCode() == static::INNER_PS_CODE) {
                $result = false;
            }

            $restrictions = $result ? $instance->getRestrictions() : [];
            if ($restrictions) {
                foreach ($restrictions as $restriction) {
                    if (!RestrictionPersonType::check($personTypeId, $restriction['PARAMS'])) {
                        $result = false;
                        break;
                    }

                    if (isset($restriction['PARAMS']['MIN_VALUE']) || isset($restriction['PARAMS']['MAX_VALUE'])) {
                        if (!RestrictionPrice::check(['PRICE_PAYMENT' => $orderPrice], $restriction['PARAMS'])) {
                            $result = false;
                            break;
                        }
                    }

                    if ($result && !RestrictionSite::check(SITE_ID, $restriction['PARAMS'], $instance->getId())) {
                        $result = false;
                        break;
                    }
                }

                if ($result && !RestrictionDelivery::check([$deliveryServiceId], [], $instance->getId())) {
                    $result = false;
                }

                if ($result && !PaySystemConstructorRestriction::check($instance->getCode(), $basket)) {
                    $result = false;
                }
            }

            if ($result) {
                $deliveryPaySystems->put($instance->getCode(), $instance);
            }
        }

        return $deliveryPaySystems;
    }
}
