<?php

namespace App\Core\Currency\Entity;

/**
 * Класс для сущности "Валюта"
 * Class CurrencyEntity
 * @package App\Core\Currency\Entity
 */
class CurrencyEntity
{
    /** @var string $symCode - Символьный код валюты (Например, USD) */
    private $symCode;

    /** @var float $amount - Стоимость валюты (в рублях) */
    private $amount;

    /** @var string $numberCode - Числовой код валюты по ISO-4217 */
    private $numberCode;

    /** @var string $symbol - Символ валюты */
    private $symbol;

    /**
     * CurrencyEntity constructor.
     *
     * @param array $currencyInfo - Информация о валюте из битриксового модуля "Валюты"
     */
    public function __construct(array $currencyInfo)
    {
        $this->symCode = $currencyInfo['CURRENCY'];
        $this->amount = $currencyInfo['AMOUNT'];
        $this->numberCode = $currencyInfo['NUMCODE'];

        /* Проверка на рубль нужна, т.к. если в админку вставить обновленный тег с символом рубля, то в заказе в админке
           вместо валюты будет буква "б". Стили, при этом, добавлять нет смысла, потому что битрикс вытаскивает для заказа
           содержимое этого тега, т.е. никакого класса там нет... */
        $this->symbol = str_replace('# ', '', $currencyInfo['FORMAT_STRING']);
        if ($this->symCode === 'RUB') {
            $this->symbol = html_entity_decode('<span class=\'rub\'>б</span>');
        }
    }

    /**
     * Получаем символьный код валюты
     *
     * @return string
     */
    public function getSymCode(): string
    {
        return $this->symCode;
    }

    /**
     * Получаем стоимость валюты в рублях
     *
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * Получаем числовой код валюты по ISO-4217
     *
     * @return string
     */
    public function getNumberCode(): string
    {
        return $this->numberCode;
    }

    /**
     * Получаем символ, обозначающий валюту
     *
     * @return string
     */
    public function getSymbol(): string
    {
        return $this->symbol;
    }
}
