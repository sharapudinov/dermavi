<?php

namespace App\Helpers;

/**
 * Class ViewContent
 *
 * @package App\Helpers
 */
class ViewContent extends \App\Core\System\Singleton
{
    /** @var string Серый ли логотип. 0 или 1 (из за особенностей битрикса) */
    public const CSS_IS_GRAY_LOGO = 'CSS_IS_GRAY_LOGO';

    /** @var string Класс для блока меню в шапке сайта */
    public const CSS_MENU_CLASS = 'CSS_MENU_CLASS';

    /** @var string Класс для логотипа в шапке сайта */
    public const CSS_LOGO_CLASS = 'CSS_LOGO_CLASS';

    /** @var string класс для тега body */
    public const CSS_BODY_CLASS = 'CSS_BODY_CLASS';

    /**
     * @param $code
     *
     * @return mixed
     */
    public function getProp($code, $default=false)
    {
        return app()->GetPageProperty($code, $default);
    }

    /**
     * @param $code
     *
     * @return mixed
     */
    public function getPropDelayed($code, $default = false)
    {
        return app()->ShowProperty($code, $default);
    }

    /**
     * @param $func
     */
    public function getBufferContent(callable $func)
    {
        app()->AddBufferContent($func);
    }

    /**
     * @param $code
     * @param $value
     *
     * @return mixed
     */
    public function setProp($code, $value)
    {
        return app()->SetPageProperty($code, $value);
    }
}
