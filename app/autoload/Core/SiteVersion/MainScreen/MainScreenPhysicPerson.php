<?php

namespace App\Core\SiteVersion\MainScreen;

use App\Helpers\LanguageHelper;
use App\Models\Catalog\Catalog;
use App\Models\Catalog\CatalogSection;

/**
 * Класс, описывающий логику получения данных для главной страницы для нужного физ лица
 * Class MainScreenPhysicPerson
 *
 * @package App\Core\SiteVersion\MainScreen
 */
class MainScreenPhysicPerson extends MainScreenAbstract
{
    /**
     * Возвращает массив данных, загруженных для главной страницы для нужного типа пользователя (юр лицо, физ лицо)
     *
     * @return array|mixed[]
     */
    protected function getSpecificInfo(): array
    {
        return [
            'isRussian' => LanguageHelper::isRussianVersion()
//            'recommendedDiamonds' => Diamond::active()
//                ->fromSectionWithCode(CatalogSection::FOR_PHYSIC_PERSONS_SECTION_CODE)
//                ->limit(6)
//                ->getList() //todo пока непонятно какая логика получения
        ];
    }
}
