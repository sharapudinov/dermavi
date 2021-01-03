<?php

namespace App\Models\HL;

use App\Helpers\LanguageHelper;
use Arrilot\BitrixModels\Models\D7Model;
use Illuminate\Support\Collection;

/**
 * Класс-модель для сущности "Страна"
 * Class Country
 * @package App\Models\HL
 */
class Country extends D7Model
{
    /** @var string - Символьный код таблицы */
    const TABLE_CODE = 'app_country';

    /**
     * Получаем класс таблицы
     *
     * @return string
     */
    public static function tableClass()
    {
        return highloadblock_class(self::TABLE_CODE);
    }

    /**
     * Получаем коллекцию всех стран с названиями на нужном языке
     *
     * @return Collection|self[]
     */
    public static function baseQuery(): Collection
    {
        $language = strtoupper(LanguageHelper::getLanguageVersion());
        return self::query()
            ->select('ID', 'UF_XML_ID', 'UF_NAME_' . $language)
            ->order('UF_NAME_' . $language)
            ->getList();
    }

    /**
     * Возвращает информацию для страны (символьный код и идентификатор языковой версии сайта для нее)
     *
     * @return array|string[]
     */
    public function getCountryLanguageInfo(): array
    {
        return LanguageHelper::getCountryLanguageAndSiteId($this);
    }

    /**
     * Является ли страна Россией
     *
     * @return bool
     */
    public function isRussia(): bool
    {
        return strstr($this->getCode(), 'Russia') !== false;
    }

    /**
     * Является ли страна Китаем
     *
     * @return bool
     */
    public function isChina(): bool
    {
        return strstr($this->getCode(), 'China') !== false;
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
     * Получает код страны
     *
     * @return string
     */
    public function getCode(): string
    {
        return $this['UF_XML_ID'];
    }

    /**
     * Получаем название страны на нужном языке
     *
     * @param null|string $lang - Язык, на котором нужно взять страну
     * @return string
     */
    public function getName(string $lang = null): string
    {
        return (string)LanguageHelper::getHlMultilingualFieldValue($this, 'NAME', $lang);
    }

    /**
     * Получает идентификатор страны в crm
     *
     * @return string|null
     */
    public function getCrmId(): ?string
    {
        return $this['UF_CRM_ID'];
    }

    /**
     * Возвращает код страны
     *
     * @return string|null
     */
    public function getCountryCode(): ?string
    {
        return $this['UF_CODE'];
    }
}
