<?php

namespace App\Helpers;

use App\Core\Jewelry\Constructor\Config;
use App\Core\Traits\Sale\StashedProductTrait;

/**
 * Класс-хелпер для помощи по работе с фронтендом
 * Class FrontendHelper
 * @package App\Helpers
 */
class FrontendHelper
{
    /** Используем трейт для работы с отложенными товарами */
    use StashedProductTrait;

    /**
     * @var array $pagesAndClasses - Многомерный массив классов, где ключ первого массива - страница,
     * ключ во вложенном массиве - элемент, значение во вложенном массиве - класс элемента
     */
    private static $pagesAndClasses;

    /**
     * Возвращает многомерный массив классов, где ключ первого массива - страница,
     * ключ во вложенном массиве - элемент, значение во вложенном массиве - класс элемента.
     *
     * @return array
     */
    protected static function getPagesAndClasses(): array
    {
        if (self::$pagesAndClasses === null) {
            self::$pagesAndClasses = [
                'about' => [
                    'menu' => 'header-main__content--violet',
                    'logo' => 'header-main__logo--violet',
                    'grey_logo' => 1
                ],
                '/^(\/en|\/ru|\/cn)?\/diamonds\/([0-9\-\_\%]+)/' => [
                    'menu' => 'header-main__content--violet',
                    'logo' => 'header-main__logo--violet',
                    'body' => 'fixed-bottom-panel',
                    'grey_logo' => 1
                ],
                '/^(\/en|\/ru|\/cn)?\/jewelry\/([0-9a-z\-\_\%]+)\/([0-9a-z\-\_\%]+)/' => [
                    'menu' => 'header-main__content--violet',
                    'logo' => 'header-main__logo--violet',
                    'body' => 'fixed-bottom-panel',
                    'grey_logo' => 1
                ],
                '/(\/en|\/ru|\/cn)?\/' . preg_quote(Config::BASE_DIR, '/') . '\/([0-9a-z\-\_\%]+)/?(\\?.*)?$#' => [
                    'menu' => 'header-main__content--violet',
                    'logo' => 'header-main__logo--violet',
                    'body' => 'fixed-bottom-panel',
                    'grey_logo' => 1
                ],
                '/(\/en|\/ru|\/cn)?\/' . preg_quote(Config::BASE_DIR, '/') . '\/constructor\/from-(frame|diamond)\/(frame|diamond)\/([0-9a-z\-\_\%]+)/' => [
                    'menu' => 'header-main__content--dark',
                    'logo' => 'header-main__content--dark',
                    'body' => 'fixed-bottom-panel',
                    'grey_logo' => 1
                ],
                '/(\/en|\/ru|\/cn)?\/' . preg_quote(Config::BASE_DIR, '/') . '\/constructor\/from-(frame|diamond)\/ready-product/' => [
                    'menu' => 'header-main__content--dark',
                    'logo' => 'header-main__content--dark',
                    'body' => 'fixed-bottom-panel',
                    'grey_logo' => 1
                ],
                '/(\/en|\/ru|\/cn)?\/' . preg_quote(Config::BASE_DIR, '/') . '\/assistant-result/' => [
                    'menu' => 'header-main__content--dark',
                    'logo' => 'header-main__content--dark',
                    'body' => 'fixed-bottom-panel',
                    'grey_logo' => 1
                ],
                '/(\/en|\/ru|\/cn)?\/' . preg_quote(Config::BASE_DIR, '/') . '\/assistant-result\//' => [
                    'menu' => 'header-main__content--violet',
                    'logo' => 'header-main__logo--violet',
                    'body' => 'fixed-bottom-panel',
                    'grey_logo' => 1
                ],
                'search' => [
                    'menu' => 'header-main__content--violet',
                    'logo' => 'header-main__logo--violet',
                ]
            ];
        }

        return self::$pagesAndClasses;
    }

    /**
     * Получаем классы для элементов в шапке сайта
     *
     * @param string $key - Ключ, означающий, класс какого тега надо получить
     * @return null|string
     */
    private static function getHeaderClass(string $key): ?string
    {
        $pageCode = strtok($_SERVER['REQUEST_URI'], '?');
        $classesArray = cache(
            get_class_name_without_namespace(self::class) . '_' . $pageCode,
            TTL::DAY,
            function () use ($pageCode) {
                /** @var array $classesArray - Массив с классами блоков */
                $classesArray = [];

                /** Проверяем, что находимся не на 404 странице */
                if (!is404()) {
                    $pageCodeArray = array_reverse(explode('/', $pageCode));
                    remove_empty_elements_from_array($pageCodeArray);

                    /**  Ищем совпадения в массиве по ключу */
                    /* @var string $key */
                    $key = $pageCodeArray[0];
                    $classesArray = self::getPagesAndClasses()[$key];

                    /**
                     * Если не нашлось совпадений, то, возможно, мы на детальной странице
                     * и надо смотреть по регулярке
                     */
                    if (empty($classesArray)) {
                        foreach (self::getPagesAndClasses() as $page => $classes) {
                            if (preg_match($page, $pageCode)) {
                                $classesArray = $classes;
                                break;
                            }
                        }
                    }
                }

                return $classesArray;
            }
        );
        return $classesArray[$key];
    }

    /**
     * Получаем класс для блока меню в шапке сайта
     *
     * @return null|string
     */
    public static function getHeaderMenuClass(): ?string
    {
        return vc()->getPropDelayed(vc()::CSS_MENU_CLASS, self::getHeaderClass('menu'));
    }

    /**
     * Получаем класс для логотипа в шапке сайта
     *
     * @return null|string
     */
    public static function getHeaderLogoClass(): ?string
    {
        return vc()->getPropDelayed(vc()::CSS_LOGO_CLASS, self::getHeaderClass('logo'));
    }

    /**
     * Получаем класс для тега body
     *
     * @return null|string
     */
    public function getBodyTagClass(): ?string
    {
        $tag = self::getHeaderClass('body');
        if ($tag) {
            if (!$this->getStashedForCartProductsInfo()) {
                $tag = null;
            }
        }

        return vc()->getPropDelayed(vc()::CSS_BODY_CLASS, $tag);
    }

    /**
     * Серый ли логотип.
     * todo !!! ВРЕМЕННЫЙ МЕТОД ДЛЯ ПЕРВОГО ЭТАПА. ДАЛЬШЕ ВЕРНЕТСЯ БРЕНД OH!DIAMONDS И НУЖНО БУДЕТ ЕГО ВЫПИЛИТЬ !!!
     * @deprecated
     *
     * @return bool
     */
    public static function isGreyLogo(): bool
    {
        return (bool)vc()->getProp(vc()::CSS_IS_GRAY_LOGO, self::getHeaderClass('grey_logo') ?? 0);
    }

    /**
     * Класс для иконки логотипа в хедера (для русской версии)
     * @return string
     */
    public static function getGrayLogoClass(): string
    {
        return \App\Helpers\FrontendHelper::isGreyLogo() ? 'grey' : 'white';
    }

    /**
     * Иконла логотипа в хедере (для остальных версий)
     * @return string
     */
    public static function getGrayLogoIcon(): string
    {
        return \App\Helpers\FrontendHelper::isGreyLogo() ? 'icon-logo_alrosa_grey_en' : 'icon-logo_alrosa_mini';
    }


}
