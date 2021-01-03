<?php

namespace App\Core\SiteVersion;

/**
 * Интерфейс для реализации логики определения языковой версии сайта для пользователя
 * Interface SiteVersionInterface
 *
 * @package App\Core\SiteVersion
 */
interface SiteVersionInterface
{
    /**
     * Возвращает идентификатор языковой версии сайта для текущего пользователя
     *
     * @return null|string
     */
    public function getUserSiteVersion(): ?string;
}
