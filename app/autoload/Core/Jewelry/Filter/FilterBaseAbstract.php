<?php

namespace App\Core\Jewelry\Filter;

use App\Core\Currency\Currency;
use App\Core\Jewelry\Enum\FilterUrlEnum;
use App\Helpers\LanguageHelper;
use App\Helpers\TTL;

/**
 * Абстрактный класс, описывающий логику работы фильтра
 * Class FilterBaseAbstract
 *
 * @package App\Core\Jewelry\Filter
 */
abstract class FilterBaseAbstract
{
    /** @var string $sectionCode Символьный код раздела/типа */
    protected $sectionCode;

    /** @var string $language Языковая версия сайта */
    protected $language;

    /**
     * Фильтр, для модификации выборки
     * @var array
     */
    protected $filter = [];

    /** @var int Время кеширования, мин.*/
    private $cacheTTL = TTL::DAY;

    /** @var bool Обязательна ли должна быть задана секция инфоблока */
    private $sectionRequired = true;

    /** @var bool Увеличивать ли правую границу диапазонных значений при их равенстве */
    protected $expandEqualRange = true;

    /**
     * FilterBaseAbstract constructor.
     *
     * @param string $sectionCode Символьный код раздела/Типа
     */
    public function __construct(string $sectionCode)
    {
        $this->sectionCode = $sectionCode;
        $this->language    = mb_strtoupper(LanguageHelper::getLanguageVersion());
    }

    /**
     * @param array $filter
     * @return static
     */
    public function setFilter(array $filter)
    {
        $this->filter = $filter;

        return $this;
    }

    /**
     * Возвращает данные для параметров фильтра
     *
     * @return array|mixed[]
     */
    public function getFilterInfo(): array
    {
        /** @var string $cacheKey Ключ кеширования */
        $cacheKey = $this->getCacheKey();
        $cacheInitDir = str_replace('\\', '_', static::class);

        return cache(
            $cacheKey,
            $this->getCacheTTL(),
            function () use ($cacheInitDir) {
                cache_manager()->StartTagCache($cacheInitDir);
                foreach ($this->getCacheTags() as $tag) {
                    cache_manager()->RegisterTag($tag);
                }

                $result = $this->getUniqueInfo();
                if ($result && $this->expandEqualRange) {
                    if ($result[FilterUrlEnum::PRICE]['min'] == $result[FilterUrlEnum::PRICE]['max']) {
                        $result[FilterUrlEnum::PRICE]['max']++;
                    }
                    if ($result[FilterUrlEnum::WEIGHT]['min'] == $result[FilterUrlEnum::WEIGHT]['max']) {
                        $result[FilterUrlEnum::WEIGHT]['max'] += 0.1;
                    }
                }

                cache_manager()->EndTagCache();

                return $result;
            },
            $cacheInitDir
        );
    }

    /**
     * Возвращает уникальную информацию для фильтра по типу
     *
     * @return array|mixed[]
     */
    abstract protected function getUniqueInfo(): array;

    /**
     * @return string
     */
    protected function getCacheKey(): string
    {
        return get_default_cache_key(static::class)
            . '_' . $this->sectionCode
            . '_' . Currency::getCurrentCurrency()->getSymCode()
            .serialize(implode('_',$this->filter));
    }


    /**
     * @param int $ttl Время кеширования в минутах
     * @return static
     */
    public function setCacheTTL(int $ttl)
    {
        $this->cacheTTL = $ttl;

        return $this;
    }

    /**
     * Время кеширования в минутах
     *
     * @return int
     */
    public function getCacheTTL(): int
    {
        return $this->cacheTTL;
    }

    /**
     * @return bool
     */
    public function isSectionRequired(): bool
    {
        return $this->sectionRequired;
    }

    /**
     * @param bool $sectionRequired
     * @return static
     */
    public function setSectionRequired(bool $sectionRequired)
    {
        $this->sectionRequired = $sectionRequired;

        return $this;
    }

    /**
     * Возвращает теги для кеша
     *
     * @return string[]
     */
    protected function getCacheTags(): array
    {
        return [];
    }
}
