<?php

namespace App\Core\SiteVersion\MainScreen;

/**
 * Класс, описывающий логику получения данных для главной страницы для нужного юр лица
 * Class MainScreenLegalPerson
 *
 * @package App\Core\SiteVersion\MainScreen
 */
class MainScreenLegalPerson extends MainScreenAbstract
{
    /**
     * Возвращает массив данных, загруженных для главной страницы для нужного типа пользователя (юр лицо, физ лицо)
     *
     * @return array|mixed[]
     */
    protected function getSpecificInfo(): array
    {
        return [];
    }
}
