<?php

namespace App\Models\HL\Company;

use App\Helpers\LanguageHelper;
use Arrilot\BitrixModels\Models\D7Model;
use Illuminate\Support\Collection;

/**
 * Класс-модель для сущности "Сфера деятельности клиента"
 * Class CompanyActivity
 * @package App\Models\HL
 */
class CompanyActivity extends D7Model
{
    /** @var string - Символьный код таблицы */
    const TABLE_CODE = 'app_company_activity';

    /**
     * Получает класс таблицы
     *
     * @return string
     */
    public static function tableClass()
    {
        return highloadblock_class(self::TABLE_CODE);
    }

    /**
     * Получаем коллекцию всех сфер с названиями на нужном языке
     *
     * @return Collection
     */
    public static function baseQuery(): Collection
    {
        $language = strtoupper(LanguageHelper::getLanguageVersion());
        return self::query()->select('ID', 'UF_NAME_' . $language)->sort('UF_SORT', 'ASC')->getList();
    }

    /**
     * Получаем идентификатор страны в бд
     *
     * @return int
     */
    public function getId(): int
    {
        return $this['ID'];
    }

    /**
     * Получает символьный код сферы
     *
     * @return string
     */
    public function getXmlId(): string
    {
        return $this['UF_XML_ID'];
    }

    /**
     * Получаем название сферы деятельности на нужном языке
     *
     * @param null|string $lang - Язык, на котором нужно взять сферу деятельности
     * @return string
     */
    public function getName(string $lang = null): string
    {
        return LanguageHelper::getHlMultilingualFieldValue($this, 'NAME', $lang);
    }

    /**
     * Возвращает crm id деятельности
     *
     * @return string|null
     */
    public function getCrmId(): ?string
    {
        return $this['UF_CRM_ID'];
    }
}
