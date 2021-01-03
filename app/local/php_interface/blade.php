<?php

use Arrilot\BitrixBlade\BladeProvider;

/**
 * Регистрация компонентов Blade
 */
$blade = BladeProvider::getCompiler();

$blade->directive('includeFile', static function ($code) {
    return '<?php $APPLICATION->IncludeFile(SITE_TEMPLATE_PATH . ' . $code . ') ?>';
});

$blade->component('components.carousel', 'carousel');
$blade->component('components.rangeSlider', 'rangeSlider');
$blade->component('components.filterCheckbox', 'filterCheckbox');
$blade->component('components.filterRange', 'filterRange');

$blade->directive(
    'rlang',
    static function ($arg_str) {
        $arg=explode(',',$arg_str);
        return '<?php echo str_replace('.$arg[0].',' .$arg[1].',GetMessage('.$arg[2].'))?>';
    }
);
