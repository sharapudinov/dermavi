<?php

namespace App\Helpers;

use App\Models\Auxiliary\CRM\FamilyStatus;
use App\Models\Auxiliary\CRM\Gender;
use App\Models\HL\SalutationType;

/**
 * Класс-хелпер для работы с полом пользователя
 * Class GenderHelper
 * @package App\Helpers
 */
class GenderHelper
{
    /**
     * Возвращает название пола на основе обращения
     *
     * @param SalutationType $salutationType Тип обращения
     *
     * @return string
     */
    public static function getGenderByAppeal(SalutationType $salutationType): string
    {
        $gender = 'Мужской';
        if ($salutationType->getValue() == 'миссис' || $salutationType->getValue() == 'г-жа') {
            $gender = 'Женский';
        }

        return $gender;
    }

    /**
     * Возвращает обращение на основе наименования пола
     *
     * @param Gender|null $gender - Модель пола пользователя
     * @param FamilyStatus|null $familyStatus - Модель семейного положения
     *
     * @return string|null
     */
    public static function getAppealByGenderAndFamilyStatus(?Gender $gender, ?FamilyStatus $familyStatus): ?string
    {
        $appeal = null;
        if ($gender) {
            if ($gender->getValue() == 'Женский') {
                if ($familyStatus && $familyStatus->isMarried()) {
                    $appeal = 'mrs';
                } elseif ($familyStatus && !$familyStatus->isMarried()) {
                    $appeal = 'miss';
                }
            } else {
                $appeal = 'mr';
            }
        }

        return $appeal;
    }

    /**
     * Возвращает идентификатор семейного положения в crm на основе обращения
     * Поскольку в crm нет признака для разделения женщин на miss и mrs, то приходится делать так
     *
     * @param string $appeal - Обращение
     *
     * @return string
     */
    public static function getFamilyStatusByAppeal(string $appeal): string
    {
        if ($appeal == 'М' || $appeal == 'Ж' || $appeal == 'Mr' || $appeal == 'Miss') {
            $familyStatus = FamilyStatus::where('status_value', 'Не в браке')->first()->getCode();
        } else {
            $familyStatus = FamilyStatus::where('status_value', 'В браке')->first()->getCode();
        }

        return $familyStatus;
    }

    /**
     * Возвращает тип обращения на основе выбранного пола/обращения при регистрации
     *
     * @param string $info
     *
     * @return SalutationType
     */
    public static function getSalutationByRegFormInfo(string $info): SalutationType
    {        
        if ($info == 'М' || $info == 'Mr') {
            $salutation = 'г-н';
        } elseif ($info == 'Ж' || $info == 'Miss') {
            $salutation = 'г-жа';
        } else {
            $salutation = 'миссис';
        }


        return SalutationType::cache(TTL::DAY)->filter(['UF_VALUE' => $salutation])->first();
    }
}
