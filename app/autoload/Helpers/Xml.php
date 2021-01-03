<?php

namespace App\Helpers;

/**
 * Class Xml
 *
 * @package App\Helpers
 */
class Xml
{
    public static function displayXmlError($error)
    {
        //$return = $xml[$error->line - 1]."\n";
        $return='';
        $return .= str_repeat('-', $error->column)."^\n";

        switch ($error->level) {
            case LIBXML_ERR_WARNING:
                $return .= "Warning $error->code: ";
                break;
            case LIBXML_ERR_ERROR:
                $return .= "Error $error->code: ";
                break;
            case LIBXML_ERR_FATAL:
                $return .= "Fatal Error $error->code: ";
                break;
        }

        $return .= trim($error->message).
            "\n  Line: $error->line".
            "\n  Column: $error->column";

        if ($error->file) {
            $return .= "\n  File: $error->file";
        }

        return "$return\n\n--------------------------------------------\n\n";
    }
}
