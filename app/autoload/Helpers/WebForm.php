<?php

namespace App\Helpers;

use Bitrix\Main\Loader;

/**
 * Class Form класс работы с формами
 *
 * @package App\Helpers
 */
class WebForm
{
    const EVENT_NAME_PREFFIX = 'FORM_FILLING_';

    const JEWELRY_ORDER = 'JEWELRY_ORDER'; //Заявка на заказ ЮИ

    /**
     * Подготовить инфу о полять в удобном виде
     *
     * @param array $arResult arResult компонента form.result.new
     *
     * @return array
     */
    public static function getFieldInfo($arResult)
    {
        $prepareQuest = [];
        foreach ($arResult['QUESTIONS'] as $key => $value) {
            preg_match("/name=\"([^\"]+)\"/", $value["HTML_CODE"], $match);
            if ($arResult['arQuestions'][$key]['COMMENTS']) {
                $prepareQuest[$arResult['arQuestions'][$key]['COMMENTS']] = [
                    "FIELD_NAME" => $match[1],
                    "OPTIONS"    => $arResult['arAnswers'][$key],
                    'DATA'       => $arResult['arQuestions'][$key],
                ];
            }
        }

        return $prepareQuest;
    }

    /**
     * Получить результаты заполнения формы
     *
     * @param $webFormId
     * @param $resultId
     *
     * @throws \Bitrix\Main\LoaderException
     */
    public static function getResult($webFormId, $resultId)
    {
        if (!Loader::includeModule('form')) {
            return;
        }

        \CForm::GetResultAnswerArray($webFormId,
            $arrColumns,
            $arrAnswers,
            $arrAnswersVarname,
            ["RESULT_ID" => $resultId]);

        $result = [];
        foreach ($arrAnswers[$resultId] as $answers) {
            foreach ($answers as $answer) {
                $fieldName          = $arrColumns[$answer['FIELD_ID']]['COMMENTS'];
                $result[$fieldName] = $answer['USER_TEXT'] ?? $answer['ANSWER_TEXT'];
            }
        }

        return $result;
    }

    /**
     * Получить список форм
     *
     * @return array
     * @throws \Bitrix\Main\LoaderException
     */
    public static function getFormList()
    {
        if (!Loader::includeModule('form')) {
            return [];
        }

        return cache(
            "App_Helpers_Form_getFormList",
            10080,
            function () {
                $arFormList = [];

                $rsForms = \CForm::GetList($by = "s_sort", $order = "asc", [], $is_filtered = false);
                while ($arForm = $rsForms->Fetch()) {
                    $arFormList[$arForm['SID']] = $arForm;
                }

                return $arFormList;
            }
        );
    }

    /**
     * Получить ИД формы по коду
     *
     * @param string $code код формы
     *
     * @return bool
     * @throws \Bitrix\Main\LoaderException
     */
    public static function getIdByCode($code)
    {
        return self::getFormList()[$code]['ID'] ?: false;
    }

    /**
     * @param string $eventName название события
     *
     * @return bool
     */
    public static function isEventForWebForm($eventName)
    {
        return in_array($eventName, [
            static::EVENT_NAME_PREFFIX.static::QUESTION_CODE,
        ]);
    }
}
