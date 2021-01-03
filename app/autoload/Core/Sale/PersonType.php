<?php

namespace App\Core\Sale;

use App\Core\Sale\Entity\PersonType as PersonTypeEntity;
use Bitrix\Main\Loader;
use CSalePersonType;

Loader::IncludeModule("sale");

/**
 * Класс для работы с типами плательщиков
 * Class PersonType
 * @package App\Core\Sale
 */
class PersonType extends CSalePersonType
{
    /** @var string - Символьный код типа плательщика "Юридическое лицо" */
    const LEGAL_ENTITY = 'LEGAL_ENTITY';

    /** @var string - Символьный код типа плательщика "Физическое лицо" */
    const PHYSICAL_ENTITY = 'PHYSICAL_ENTITY';

    /**
     * Получаем идентификатор типа плательщика
     *
     * @param string $code - Код типа плательщика
     * @return PersonTypeEntity
     */
    public static function getPersonType(string $code): PersonTypeEntity
    {
        $personType = parent::GetList([], ['CODE' => $code])->Fetch();
        return new PersonTypeEntity($personType);
    }
}
