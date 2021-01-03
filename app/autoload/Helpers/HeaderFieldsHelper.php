<?php

namespace App\Helpers;

use App\Models\HL\HeaderFields;

/**
 * Class HeaderFieldsHelper
 * @package App\Helpers
 */
class HeaderFieldsHelper
{
    /** @var array */
    protected static $headerList;

    /**
     * @return HeaderFieldsHelper
     */
    public static function init()
    {
        $headerFields = HeaderFields::getFieldsForUser();
        if ($headerFields instanceof HeaderFields) {
            self::$headerList = $headerFields->getHeaderFields();
        }

        return new self();
    }

    /**
     * @param string $name     название чекбокса
     * @param bool   $defParam значение по умолчанию
     *
     * @return string
     */
    public function getChecked(string $name, bool $defParam = false): string
    {
        if (null === self::$headerList) {
            return $defParam ? 'checked' : '';
        }

        return in_array($name, self::$headerList) ? 'checked' : '';
    }
}
