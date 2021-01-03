<?php
namespace App\Core\Sale\View;

/**
 * Модель представления для адреса.
 * Class AddressViewModel
 * @package App\Core\Sale\View
 *
 * @property string $zip
 * @property string $country
 * @property string $region
 * @property string $city
 * @property string $address
 */
class AddressViewModel
{
    /** @var string Код поля почтового индекса */
    public const FIELD_ZIP = 'ZIP';

    /** @var string Код поля страны */
    public const FIELD_COUNTRY = 'COUNTRY';

    /** @var string Код поля региона */
    public const FIELD_REGION = 'REGION';

    /** @var string Код поля города */
    public const FIELD_CITY = 'CITY';

    /** @var string Код поля улица */
    public const FIELD_STREET = 'STREET';
    
    /** @var string Код поля дом */
    public const FIELD_HOUSE = 'HOUSE';
    
    /** @var string Код поля квартира */
    public const FIELD_FLAT = 'FLAT';

    /** @var array Части адреса */
    private $parts;

    /**
     * AddressViewModel конструктор.
     * @param array|null $source - источник для инициализации элементов
     * @param string $prefix - префикс ключей адреса в массиве-источнике
     */
    public function __construct(array $source = null, string $prefix = '')
    {
        if ($source === null) {
            $source = [];
        }

        $this->parts = [
            self::FIELD_ZIP => $this->prepareValue($source[$prefix . self::FIELD_ZIP]),
            self::FIELD_COUNTRY => $this->prepareValue($source[$prefix . self::FIELD_COUNTRY]),
            self::FIELD_REGION => $this->prepareValue($source[$prefix . self::FIELD_REGION]),
            self::FIELD_CITY => $this->prepareValue($source[$prefix . self::FIELD_CITY]),
            self::FIELD_STREET => $this->prepareValue($source[$prefix . self::FIELD_STREET]),
            self::FIELD_HOUSE => $this->prepareValue($source[$prefix . self::FIELD_HOUSE]),
            self::FIELD_FLAT => $this->prepareValue($source[$prefix . self::FIELD_FLAT]),
        ];
    }

    /**
     * Возвращает значение элемента адреса.
     *
     * @param $name string
     * @return string
     */
    public function __get($name): string
    {
        $name = strtoupper($name);
        return (string) $this->parts[$name];
    }

    /**
     * Устанавливает значение элемента адреса.
     *
     * @param $name string
     * @param $value string
     * @return void
     */
    public function __set($name, $value): void
    {
        $name = strtoupper($name);
        if (array_key_exists($name, $this->parts)) {
            $this->parts[$name] = $this->prepareValue($value);
        }
    }

    /**
     * Возвращает представление адреса.
     * @return string
     */
    public function __toString(): string
    {
        $values = array_filter($this->parts, function (string $value) {
            return strlen($value) > 0;
        });
        return implode(', ', $values);
    }

    /**
     * Возвращает корректное значение элемента адреса.
     * @param mixed $value
     * @return string
     */
    private function prepareValue($value): string
    {
        return trim((string) $value);
    }
}
