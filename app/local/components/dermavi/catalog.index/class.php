<?php

namespace App\Local\Component;

use App\Components\BaseComponent;
use App\Models\User;
use Bitrix\Main\Loader;
use CComponentEngine;

/**
 * Начальная страница каталога.
 *
 * Class CatalogIndexComponent
 * @package App\Local\Component
 */
class CatalogIndexComponent extends BaseComponent
{

    /**
     * Реализует логику компонента
     *
     * @return void
     */
    public function execute(): void
    {
        if (isset($this->arParams["USE_FILTER"]) && $this->arParams["USE_FILTER"] == "Y") {
            $this->arParams["FILTER_NAME"] = trim($this->arParams["FILTER_NAME"]);
            if ($this->arParams["FILTER_NAME"] === '' || !preg_match(
                    "/^[A-Za-z_][A-Za-z01-9_]*$/",
                    $this->arParams["FILTER_NAME"]
                )) {
                $this->arParams["FILTER_NAME"] = "arrFilter";
            }
        } else {
            $this->arParams["FILTER_NAME"] = "";
        }

//default gifts
        if (empty($this->arParams['USE_GIFTS_SECTION'])) {
            $this->arParams['USE_GIFTS_SECTION'] = 'Y';
        }
        if (empty($this->arParams['GIFTS_SECTION_LIST_PAGE_ELEMENT_COUNT'])) {
            $this->arParams['GIFTS_SECTION_LIST_PAGE_ELEMENT_COUNT'] = 3;
        }
        if (empty($this->arParams['GIFTS_MAIN_PRODUCT_DETAIL_PAGE_ELEMENT_COUNT'])) {
            $this->arParams['GIFTS_MAIN_PRODUCT_DETAIL_PAGE_ELEMENT_COUNT'] = 4;
        }
        if (empty($this->arParams['GIFTS_DETAIL_PAGE_ELEMENT_COUNT'])) {
            $this->arParams['GIFTS_DETAIL_PAGE_ELEMENT_COUNT'] = 4;
        }

        $this->arParams['ACTION_VARIABLE'] = (isset($this->arParams['ACTION_VARIABLE']) ? trim(
            $this->arParams['ACTION_VARIABLE']
        ) : 'action');
        if ($this->arParams["ACTION_VARIABLE"] == '' || !preg_match(
                "/^[A-Za-z_][A-Za-z01-9_]*$/",
                $this->arParams["ACTION_VARIABLE"]
            )) {
            $this->arParams["ACTION_VARIABLE"] = "action";
        }

        $smartBase = ($this->arParams["SEF_URL_TEMPLATES"]["section"] ? $this->arParams["SEF_URL_TEMPLATES"]["section"] : "#SECTION_ID#/");
        $arDefaultUrlTemplates404 = [
            "sections"     => "",
            "section"      => "#SECTION_ID#/",
            "element"      => "#SECTION_ID#/#ELEMENT_ID#/",
            "compare"      => "compare.php?action=COMPARE",
            "smart_filter" => $smartBase . "filter/#SMART_FILTER_PATH#/apply/"
        ];

        $arDefaultVariableAliases404 = [];

        $arDefaultVariableAliases = [];

        $arComponentVariables = [
            "SECTION_ID",
            "SECTION_CODE",
            "ELEMENT_ID",
            "ELEMENT_CODE",
            "action",
        ];

        if ($this->arParams["SEF_MODE"] == "Y") {
            $arVariables = [];

            $engine = new CComponentEngine($this);
            if (Loader::includeModule('iblock')) {
                $engine->addGreedyPart("#SECTION_CODE_PATH#");
                $engine->addGreedyPart("#SMART_FILTER_PATH#");
                $engine->setResolveCallback(["CIBlockFindTools", "resolveComponentEngine"]);
            }
            $arUrlTemplates = CComponentEngine::makeComponentUrlTemplates(
                $arDefaultUrlTemplates404,
                $this->arParams["SEF_URL_TEMPLATES"]
            );
            $arVariableAliases = CComponentEngine::makeComponentVariableAliases(
                $arDefaultVariableAliases404,
                $this->arParams["VARIABLE_ALIASES"]
            );

            $componentPage = $engine->guessComponentPath(
                $this->arParams["SEF_FOLDER"],
                $arUrlTemplates,
                $arVariables
            );

            if ($componentPage === "smart_filter") {
                $componentPage = "section";
            }

            if (!$componentPage && isset($_REQUEST["q"])) {
                $componentPage = "search";
            }

            $b404 = false;
            if (!$componentPage) {
                $componentPage = "sections";
                $b404 = true;
            }

            if ($componentPage == "section") {
                if (isset($arVariables["SECTION_ID"])) {
                    $b404 |= (intval($arVariables["SECTION_ID"]) . "" !== $arVariables["SECTION_ID"]);
                } else {
                    $b404 |= !isset($arVariables["SECTION_CODE"]);
                }
            }

            if ($b404 && \CModule::IncludeModule('iblock')) {
                $folder404 = str_replace("\\", "/", $this->arParams["SEF_FOLDER"]);
                if ($folder404 != "/") {
                    $folder404 = "/" . trim($folder404, "/ \t\n\r\0\x0B") . "/";
                }
                if (mb_substr($folder404, -1) == "/") {
                    $folder404 .= "index.php";
                }

                if ($folder404 != app()->GetCurPage(true)) {
                    \Bitrix\Iblock\Component\Tools::process404(
                        ""
                        ,
                        ($this->arParams["SET_STATUS_404"] === "Y")
                        ,
                        ($this->arParams["SET_STATUS_404"] === "Y")
                        ,
                        ($this->arParams["SHOW_404"] === "Y")
                        ,
                        $this->arParams["FILE_404"]
                    );
                }
            }

            CComponentEngine::initComponentVariables(
                $componentPage,
                $arComponentVariables,
                $arVariableAliases,
                $arVariables
            );
            $this->arResult = [
                "FOLDER"        => $this->arParams["SEF_FOLDER"],
                "URL_TEMPLATES" => $arUrlTemplates,
                "VARIABLES"     => $arVariables,
                "ALIASES"       => $arVariableAliases
            ];
        } else {
            $arVariables = [];

            $arVariableAliases = CComponentEngine::makeComponentVariableAliases(
                $arDefaultVariableAliases,
                $this->arParams["VARIABLE_ALIASES"]
            );
            CComponentEngine::initComponentVariables(false, $arComponentVariables, $arVariableAliases, $arVariables);

            $componentPage = "";

            $arCompareCommands = [
                "COMPARE",
                "DELETE_FEATURE",
                "ADD_FEATURE",
                "DELETE_FROM_COMPARE_RESULT",
                "ADD_TO_COMPARE_RESULT",
                "COMPARE_BUY",
                "COMPARE_ADD2BASKET"
            ];

            if (isset($arVariables["action"]) && in_array($arVariables["action"], $arCompareCommands)) {
                $componentPage = "compare";
            } elseif (isset($arVariables["ELEMENT_ID"]) && intval($arVariables["ELEMENT_ID"]) > 0) {
                $componentPage = "element";
            } elseif (isset($arVariables["ELEMENT_CODE"]) && $arVariables["ELEMENT_CODE"] <> '') {
                $componentPage = "element";
            } elseif (isset($arVariables["SECTION_ID"]) && intval($arVariables["SECTION_ID"]) > 0) {
                $componentPage = "section";
            } elseif (isset($arVariables["SECTION_CODE"]) && $arVariables["SECTION_CODE"] <> '') {
                $componentPage = "section";
            } elseif (isset($_REQUEST["q"])) {
                $componentPage = "search";
            } else {
                $componentPage = "sections";
            }

            $currentPage = htmlspecialcharsbx(app()->GetCurPage()) . "?";
            $this->arResult = [
                "FOLDER"        => "",
                "URL_TEMPLATES" => [
                    "section" => $currentPage . $arVariableAliases["SECTION_ID"] . "=#SECTION_ID#",
                    "element" => $currentPage . $arVariableAliases["SECTION_ID"] . "=#SECTION_ID#" . "&" . $arVariableAliases["ELEMENT_ID"] . "=#ELEMENT_ID#",
                    "compare" => $currentPage . "action=COMPARE",
                ],
                "VARIABLES"     => $arVariables,
                "ALIASES"       => $arVariableAliases
            ];
        }

        $this->IncludeComponentTemplate($componentPage);
    }
}
