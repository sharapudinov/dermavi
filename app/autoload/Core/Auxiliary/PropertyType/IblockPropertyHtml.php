<?php

namespace App\Core\Auxiliary\PropertyType;

/**
 * Класс-обработчик добавления нового свойства HL инфоблока "HTML"
 * Class IblockPropertyCurrency
 *
 * @package App\Core\Auxiliary\PropertyType
 */
class IblockPropertyHtml extends \CUserTypeString
{
    /**
     * Обработчик для создания свойства в ИБ
     *
     * @return array
     */
    public function getUserTypeDescription(): array
    {
        return [
            "BASE_TYPE"     => "string",
            "USER_TYPE_ID"  => "HTML",
            "DESCRIPTION"   => "HTML",
            "CLASS_NAME"    => __CLASS__,
            "EDIT_CALLBACK" => [__CLASS__, 'GetPublicEdit'],
            "VIEW_CALLBACK" => [__CLASS__, 'GetPublicView'],
        ];
    }
    
    /**
     * @param $arUserField
     * @param $arHtmlControl
     * @return false|string
     */
    function GetEditFormHTML($arUserField, $arHtmlControl)
    {
        if (\CModule::includeModule("fileman")) {
            $editor = new \CHTMLEditor;
            $res    = [
                'useFileDialogs'      => false,
                'height'              => 200,
                'minBodyWidth'        => 350,
                'normalBodyWidth'     => 555,
                'bAllowPhp'           => false,
                'limitPhpAccess'      => false,
                'showTaskbars'        => false,
                'showNodeNavi'        => false,
                'askBeforeUnloadPage' => true,
                'bbCode'              => false,
                'siteId'              => SITE_ID,
                'autoResize'          => true,
                'autoResizeOffset'    => 40,
                'saveOnBlur'          => true,
                'controlsMap'         => [
                    ['id' => 'Bold', 'compact' => true, 'sort' => 80],
                    ['id' => 'Italic', 'compact' => true, 'sort' => 90],
                    ['id' => 'Underline', 'compact' => true, 'sort' => 100],
                    ['id' => 'Strikeout', 'compact' => true, 'sort' => 110],
                    ['id' => 'RemoveFormat', 'compact' => true, 'sort' => 120],
                    ['id' => 'Color', 'compact' => true, 'sort' => 130],
                    ['id' => 'FontSelector', 'compact' => false, 'sort' => 135],
                    ['id' => 'FontSize', 'compact' => false, 'sort' => 140],
                    ['separator' => true, 'compact' => false, 'sort' => 145],
                    ['id' => 'OrderedList', 'compact' => true, 'sort' => 150],
                    ['id' => 'UnorderedList', 'compact' => true, 'sort' => 160],
                    ['id' => 'AlignList', 'compact' => false, 'sort' => 190],
                    ['separator' => true, 'compact' => false, 'sort' => 200],
                    ['id' => 'InsertLink', 'compact' => true, 'sort' => 210, 'wrap' => 'bx-b-link-'.$id],
                    ['id' => 'InsertImage', 'compact' => false, 'sort' => 220],
                    ['id' => 'InsertVideo', 'compact' => true, 'sort' => 230, 'wrap' => 'bx-b-video-'.$id],
                    ['id' => 'InsertTable', 'compact' => false, 'sort' => 250],
                    ['id' => 'Code', 'compact' => true, 'sort' => 260],
                    ['id' => 'Quote', 'compact' => true, 'sort' => 270, 'wrap' => 'bx-b-quote-'.$id],
                    ['id' => 'Smile', 'compact' => false, 'sort' => 280],
                    ['separator' => true, 'compact' => false, 'sort' => 290],
                    ['id' => 'Fullscreen', 'compact' => false, 'sort' => 310],
                    ['id' => 'BbCode', 'compact' => true, 'sort' => 340],
                    ['id' => 'More', 'compact' => true, 'sort' => 400],
                ],
                
                'name'      => $arHtmlControl['NAME'],
                'inputName' => $arHtmlControl['NAME'],
                'id'        => $id,
                'width'     => '100%',
                'content'   => htmlspecialcharsback($arHtmlControl['VALUE']),
            ];
            
            ob_start();
            echo '<input type="hidden" name="'.$fieldName.'[TYPE]" value="html">';
            $editor->show($res);
            $result = ob_get_contents();
            ob_end_clean();
        }
        
        return $result;
    }
    
    /**
     * @param $arUserField
     * @param $arHtmlControl
     * @return mixed
     */
    function GetAdminListViewHTML($arUserField, $arHtmlControl)
    {
        return htmlspecialcharsback($arHtmlControl['VALUE']);
    }
}

