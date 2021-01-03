<?php

namespace App\Models\HL;

use App\Helpers\TTL;
use Arrilot\BitrixModels\Models\D7Model;

/**
 * Класс-модель для описания сущности "Тип адреса в CRM"
 * Class AddressType
 * @package App\Models\Auxiliary\CRM
 */
class AddressType extends D7Model
{
    /** @var string - Символьный код таблицы */
    public const TABLE_CODE = 'app_crm_address_type';

    /** @var string - Наименование типа адреса доставки */
    public const DELIVERY_TYPE = 'Доставки';

    /** @var string - Наименование типа юридического адреса */
    public const LEGAL_TYPE = 'Юридический';

    /** @var string - Наименование типа домашнего адреса */
    public const PHYSIC_TYPE = 'Домашний';

    /** @var string - Наименование типа фактического адреса */
    public const ACTUAL_TYPE = 'Фактический';

    /** @var string Наименования типа адреса регистрации */
    public const REGISTER_ADDRESS = 'Регистрации';

    /**
     * Возвращает идентификатор типа адреса по его названию
     *
     * @param string $name - Название типа
     *
     * @return self
     */
    private static function getAddressTypeByName(string $name): self
    {
        return self::cache(TTL::DAY)->filter(['UF_NAME' => $name])->first();
    }

    /**
     * Возвращает класс таблицы
     *
     * @return string
     */
    public static function tableClass()
    {
        return highloadblock_class(self::TABLE_CODE);
    }

    /**
     * Возвращает тип юридического адреса
     *
     * @return self
     */
    public static function getLegalAddressType(): self
    {
        return self::getAddressTypeByName(self::LEGAL_TYPE);
    }

    /**
     * Возвращает тип фактического адреса
     *
     * @return AddressType
     */
    public static function getActualAddressType(): self
    {
        return self::getAddressTypeByName(self::ACTUAL_TYPE);
    }

    /**
     * Возвращает тип домашнего адреса
     *
     * @return AddressType
     */
    public static function getPhysicAddressType(): self
    {
        return self::getAddressTypeByName(self::PHYSIC_TYPE);
    }

    /**
     * Возвращает тип адреса доставки
     *
     * @return self
     */
    public static function getDeliveryAddressType(): self
    {
        return self::getAddressTypeByName(self::DELIVERY_TYPE);
    }

    /**
     * Возвращает тип адреса регистрации
     *
     * @return AddressType
     */
    public static function getRegisterAddressType(): self
    {
        return self::getAddressTypeByName(self::REGISTER_ADDRESS);
    }

    /**
     * Возвращает идентификатор типа адреса
     *
     * @return int
     */
    public function getId(): int
    {
        return $this['ID'];
    }

    /**
     * Возвращает crm id типа адреса
     *
     * @return string
     */
    public function getCrmId(): string
    {
        return $this['UF_CRM_ID'];
    }

    /**
     * Возвращает наименование типа адреса
     *
     * @return string
     */
    public function getName(): string
    {
        return $this['UF_NAME'];
    }
}
