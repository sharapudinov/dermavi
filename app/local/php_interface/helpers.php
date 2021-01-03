<?php

use App\Core\Geolocation\DaDataSuggest;
use App\Helpers\LanguageHelper;
use App\Helpers\ServerHelper;
use App\Helpers\SiteHelper;
use App\Helpers\StringHelper;
use App\Helpers\UrlHelper;
use App\Helpers\ImageHelper;
use App\Models\User;
use Arrilot\GoogleRecaptcha\Recaptcha;
use Bitrix\Main\Application;
use Bitrix\Main\Context;
use Bitrix\Main\Localization\Loc;
use Illuminate\Container\Container;
use Illuminate\Support\Str;

/**
 * db()->query('SELECT * FROM b_user');
 *
 * @param string $name
 * @return \Bitrix\Main\DB\Connection
 */
function db($name = '')
{
    return Bitrix\Main\Application::getConnection($name);
}

function ending($number, $one, $two, $five)
{
    $number = $number % 100;

    if ( ($number > 4 && $number < 21) || $number == 0 )
    {
        $ending = $five;
    }
    else
    {
        $last_digit = substr($number, -1);

        if ( $last_digit > 1 && $last_digit < 5 )
            $ending = $two;
        elseif ( $last_digit == 1 )
            $ending = $one;
        else
            $ending = $five;
    }

    return $ending;
}

/**
 * Получение ID инфоблока по коду (или по коду и типу).
 * Всегда выполняет лишь один запрос в БД на скрипт.
 *
 * @param string $code
 * @param string|null $type
 * @return int
 *
 * @throws RuntimeException
 */
function iblock_id($code, $type = null)
{
    return Arrilot\BitrixIblockHelper\IblockId::getByCode($code, $type);
}

/**
 * Получение данных хайлоадблока по названию его таблицы.
 * Всегда выполняет лишь один запрос в БД на скрипт и возвращает массив вида:
 *
 * array:3 [
 *   "ID" => "2"
 *   "NAME" => "Subscribers"
 *   "TABLE_NAME" => "app_subscribers"
 * ]
 *
 * @param string $table
 * @return array
 */
function highloadblock($table)
{
    return Arrilot\BitrixIblockHelper\HLblock::getByTableName($table);
}

/**
 * Компилирование и возвращение класса для хайлоадблока для таблицы $table.
 *
 * Пример для таблицы `app_subscribers`:
 * $subscribers = highloadblock_class('app_subscribers');
 * $subscribers::getList();
 *
 * @param string $table
 * @return string
 */
function highloadblock_class($table)
{
    return Arrilot\BitrixIblockHelper\HLblock::compileClass($table);
}

/**
 * Компилирование сущности для хайлоадблока для таблицы $table.
 * Выполняется один раз.
 *
 * Пример для таблицы `app_subscribers`:
 * $entity = \Arrilot\BitrixIblockHelper\HLblock::compileEntity('app_subscribers');
 * $query = new Entity\Query($entity);
 *
 * @param string $table
 * @return \Bitrix\Main\Entity\Base
 */
function highloadblock_entity($table)
{
    return Arrilot\BitrixIblockHelper\HLblock::compileEntity($table);
}

/**
 * Входная точка в класс работы со сборщиком.
 * frontend()->img()...
 *
 * @return \App\Frontend\Frontend
 */
function frontend()
{
    static $frontend = null;

    if ($frontend === null) {
        $frontend = new \App\Frontend\Frontend();
    }

    return $frontend;
}

/**
 * Работа с подсказками дадаты
 *
 * @return DaDataSuggest
 */
function dadata()
{
    static $dadata = null;

    if ($dadata === null) {
        $dadata = new DaDataSuggest();
    }

    return $dadata;
}

/**
 * Завершение запроса показом 404-ой страницы.
 *
 * @return void
 */
function show404()
{
    global $APPLICATION;

    if ($APPLICATION->RestartWorkarea()) {
        require(Application::getDocumentRoot() . "/404.php");
        die();
    }
}

/**
 * Находимся ли мы на 404 странице
 *
 * @return bool
 */
function is404(): bool
{
    return defined('ERROR_404') && ERROR_404 == 'Y';
}

/**
 * Хэлпер для получения Service Container-а
 *
 * @return Container
 */
function container()
{
    return Container::getInstance();
}

/**
 * Resolve a service from the container.
 *
 * @param  string $name
 * @param array $parameters
 * @return mixed
 */
function resolve(string $name, array $parameters = [])
{
    return Container::getInstance()->make($name, $parameters);
}

/**
 * logger()->error('Error message here');
 *
 * @param string $name
 * @return \Monolog\Logger
 */
function logger($name = 'common')
{
    return Monolog\Registry::getInstance($name);
}

/**
 * Является ли текущий запрос AJAX запросом?
 *
 * @return bool
 */
function is_ajax()
{
    return isset($_SERVER['HTTP_X_REQUESTED_WITH'])
    && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
}

/**
 * Определяет был ли даннй запрос выполнен через api
 *
 * @return bool
 */

function is_api(): bool
{
    return isset($_SERVER['SCRIPT_NAME']) && $_SERVER['SCRIPT_NAME'] == '/api/index.php';
}

/**
 * Определяет зашёл ли пользователь с мобильного устройства.
 * Определение проводится лишь один раз за запрос и кэшируется в локальную переменную
 * В результате данный метод можно безбоязнено вызывать сколько угодно раз.
 *
 * @return bool
 */
function is_mobile()
{
    static $result = null;

    if ($result === null) {
        $result = (new Mobile_Detect())->isMobile();
    }

    return $result;
}

/**
 *  Выбор нужной формы слова.
 *
 *   $forms = [
 *     "банан",
 *     "банана",
 *     "бананов"
 *   ];
 *
 *   plural_form(1, $forms); //банан
 *   plural_form(2, $forms); //банана
 *   plural_form(5, $forms); //бананов
 *
 * @param $n
 * @param $forms
 * @return string
 */
function plural_form($n, $forms)
{
    return $n % 10 == 1 && $n % 100 != 11 ? $forms[0] : ($n % 10 >= 2 && $n % 10 <= 4 && ($n % 100 < 10 || $n % 100 >= 20) ? $forms[1] : $forms[2]);
}

/**
 * Оставляет в строке/массиве id только числовые.
 *
 * @param string|array $ids
 *
 * @return string
 */
function ids_to_sql($ids)
{
    if (!$ids) {
        return '';
    }

    $result = [];

    if (!is_array($ids)) {
        $ids = explode(',', $ids);
    }

    foreach ($ids as $id) {
        $id = (int) $id;
        if ($id) {
            $result[] = $id;
        }
    }

    return implode(',', $result);
}

/**
 * Запущен ли скрипт через консоль?
 *
 * @return bool
 */
function in_console()
{
    return php_sapi_name() === 'cli';
}

/**
 * Находимся ли мы в боевой среде?
 *
 * @return bool
 */
function in_production()
{
    return env('APP_ENV', 'production') === 'production';
}

/**
 * Находимся ли мы в боевой среде или на предпроде?
 *
 * @return bool
 */
function in_production_or_stage()
{
    return in_array(env('APP_ENV', 'production'), ['production', 'stage'], true);
}

/**
 * @return Recaptcha
 */
function recaptcha()
{
    return Recaptcha::getInstance();
}

/**
 * Добавить переменную к отладке
 * @param mixed $var переменная, информацию о которой необходимо вывести в отладчик
 * @param string $name название переменной. По умолчанию будет использовано реальное название переменной или No Name
 * @param int $backtrace_i порядковый номер элемента стека вызова, который будет использоваться для получения информации о файле и строке вызова
 */
function debug_var($var, $name = '', $backtrace_i = 0)
{
    \MsNatali\BitrixDebug\DebugVar::get()->debug($var, $name, $backtrace_i + 1);
}

/**
 * Возвращает текущего пользователя или пользователя по id
 *
 * @param null|int $id Идентификатор пользователя
 * @param array|string[] $with Массив, описывающий модели для жадной загрузки
 *
 * @return User|null
 */
function user(int $id = null, array $with = [])
{
    if (is_null($id)) {
        $user = User::current();
        if (!$user->id) {
            $user = null;
        }
    } else {
        $user = User::query()->with($with)->getById($id);
    }

    return $user;
}

/**
 * Возвращает часть request_uri
 *
 * @param int|null $number
 * @param string|null $url
 * @return string
 */
function get_code(int $number = null, string $url = null): ?string
{
    $string = $url ?? strtok($_SERVER['REQUEST_URI'], '?');

    $array = explode('/', $string);
    remove_empty_elements_from_array($array);

    if ($number) {
        $code = $array[$number];
    } else {
        $code = end($array);
    }

    if ($position = strpos($code, 'clear_cache=Y')) {
        $code = substr($code, 0, $position - 1);
    }

    if ($code == LanguageHelper::getLanguageVersion()) {
        $code = substr($code, 0, 0);
    }

    return $code;
}

/**
 * Получаем экземпляр класса CMain
 *
 * @return CMain
 */
function app(): CMain
{
    global $APPLICATION;
    return $APPLICATION;
}

/**
 * Логирует исключение в общий лог
 *
 * @param Throwable $e
 */
function logException(Throwable $e)
{
    logger('common')->error(
        $e->getMessage(),
        [
            'exception' => [
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTrace(),
            ],
            'request' => [
                'REQUEST_URI' => $_SERVER['REQUEST_URI'] ?? null,
                'REQUEST_METHOD' => $_SERVER['REQUEST_METHOD'] ?? null,
                'QUERY_STRING' => $_SERVER['QUERY_STRING'] ?? null,
                'SCRIPT_FILENAME' => $_SERVER['SCRIPT_FILENAME'] ?? null,
            ],
        ]
    );
}

/**
 * Контекст приложения
 *
 * @return Context
 */
function context(): Context
{
    return Application::getInstance()->getContext();
}

/**
 * Генерируем ссылку на ту же самую страницу, на которой находится пользователь,
 * только в другой языковой версии
 *
 * @param string|null $lang
 * @return string
 */
function generate_link_by_language_version(string $lang = null): string
{
    if ($lang == LanguageHelper::DEFAULT_LANGUAGE) {
        $lang = null;
    }

    $uri = explode('?', $_SERVER['REQUEST_URI']);

    $langs = LanguageHelper::getAvailableLanguages();

    $startWithLang = false;
    foreach ($langs as $potencialLang) {
        $startWithLang |= substr($uri[0], 0, 4) == '/' . $potencialLang . '/';
    }

    if ($startWithLang) {
        $uri[0] = substr($uri[0], 3);
    }

    $url = ($lang ? '/' . $lang : '') . $uri[0] . (count($uri) > 1 ? ('?' . $uri[1]) : '');
    return $url;
}

/**
 * Получаем префикс для ссылки, указывающий языковую версию сайта
 *
 * @return string
 */
function get_language_version_href_prefix(): string
{
    $prefix = '';
    if (!LanguageHelper::isDefaultVersion()) {
        $prefix = '/' . LanguageHelper::getLanguageVersion();
    }
    return $prefix;
}

/**
 * Получаем название класса без неймспейса
 *
 * @param string $class - название класса (__CLASS__)
 * @return string
 */
function get_class_name_without_namespace(string $class): string
{
    return substr(strrchr($class, "\\"), 1);
}

/**
 * Возвращает значение опции из sprint.options
 *
 * @param string $optionName
 * @return mixed
 */
function get_sprint_option(string $optionName)
{
    if (!\CModule::IncludeModule("sprint.options")) {
        return false;
    }

    $value = sprint_options_get($optionName);
    $values = unserialize($value);
    if ($values) {
        $values = array_filter($values, function ($v, $k) {
            return !empty(trim($v));
        }, ARRAY_FILTER_USE_BOTH);

        return $values;
    }

    return $value;
}

function cache_manager(): CCacheManager
{
    global $CACHE_MANAGER;
    return $CACHE_MANAGER;
}

function init_tag_cache(array $tags, string $cacheDir = '/')
{
    cache_manager()->StartTagCache($cacheDir);
    foreach ($tags as $tag) {
        cache_manager()->RegisterTag($tag);
    }
    cache_manager()->EndTagCache();
}

/**
 * Возвращает полную ссылку на сайт для внешнего использования.
 *
 * @param bool $uri - Надо ли использовать REQUEST_URI
 * @param bool $protocol - Надо ли использовать протокол
 * @return string
 */
function get_external_url(bool $uri = true, bool $protocol = true): string
{
    return ($protocol ? ServerHelper::getProtocol() . '://' : '')
        . ServerHelper::getHttpHost() . ($uri ? $_SERVER['REQUEST_URI'] : '');
}

/**
 * Генерируем случайную строку заданного размера
 *
 * @param int $length - Длина строки
 * @return string
 */
function generate_hash(int $length): string
{
    $chars = 'abdefhiknrstyzABDEFGHKNQRSTYZ1234567890?-_';
    $numChars = strlen($chars);
    $string = '';

    for ($i = 0; $i < $length; $i++) {
        $string .= substr($chars, rand(1, $numChars) - 1, 1);
    }

    return $string;
}

/**
 * Получаем стандартный ключ для кеширования
 *
 * @param string $class - Класс, в котором вызывается функция
 * @return string
 */
function get_default_cache_key(string $class): string
{
    return get_class_name_without_namespace($class) . '_' . LanguageHelper::getLanguageVersion();
}

/**
 * Устанавливаем заголовок страницы
 *
 * @param string $title - Заголовок
 * @return void
 */
function set_title(string $title): void
{
    app()->SetTitle($title);
}

/**
 * Получаем заголовок страницы
 *
 * @return string
 */
function get_title(): string
{
    return app()->GetTitle();
}

/**
 * Вставка включаемой html области.
 *
 * @param string $file - Название файла
 */
function include_bitrix_area(string $file): void
{
    $path = SiteHelper::getSiteTemplatePath() . '/includes/' . LanguageHelper::getLanguageVersion() . '/' . $file;
    app()->IncludeFile($path, false, ['MODE' => 'html']);
}

/**
 * Подгружаем шаблон компонента
 *
 * @param $class
 * @param $template
 *
 * @return string
 */
function loadTemplate($class, $template)
{
    ob_start();
    $class->includeComponentTemplate($template);
    $html = ob_get_contents();
    ob_get_clean();

    return $html;
}

/**
 * Находимся ли мы на главной странице
 *
 * @return bool
 */
function is_main_page(): bool
{
    return Context::getCurrent()->getRequest()->getRequestedPageDirectory() === get_language_version_href_prefix();
}

/**
 * Проверяет находится ли пользователь на определенной странице
 *
 * @param string $url Ссылка
 *
 * @return bool
 */
function is_page(string $url): bool
{
    return UrlHelper::isPage($url);
}

/**
 * Проверяет находится ли пользователь в определенном разделе
 *
 * @param string $url Ссылка
 *
 * @return bool
 */
function is_directory(string $url): bool
{
    return strpos(
            Context::getCurrent()->getRequest()->getRequestedPageDirectory() . '/',
            sprintf('%s%s', get_language_version_href_prefix(), $url)
        ) === 0;
}

/**
 * Получаем скрытое поле с csrf-токеном
 *
 * @return string
 */
function get_csrf_hidden(): string
{
    return bitrix_sessid_post('csrf_token');
}

/**
 * Проверить csrf-токен
 *
 * @param string|null $csrfToken csrf токен
 *
 * @return bool
 */
function check_csrf_token(string $csrfToken = null): bool
{
    $access = true;
    if (!check_bitrix_sessid('csrf_token') && $csrfToken != bitrix_sessid()) {
        $access = false;
    }

    return $access;
}

/**
 * Удаляем пустые значения из массива
 *
 * @param array $array - массив
 */
function remove_empty_elements_from_array(array &$array): void
{
    $array = array_values(array_filter($array, function ($element) {
        return $element != '';
    }));
}

/**
 * Получаем фразу из языкового файла по ключу
 *
 * @param string $key - Ключ
 * @return null|string
 */
function get_lang_phrase(string $key): ?string
{
    return Loc::getMessage($key);
}

/**
 * Очищаем массив от html тегов
 *
 * @param $array
 * @return array
 */
function htmlentities_on_array($array): array
{
    $processedArray = [];
    foreach ($array as $key => $value) {
        $processedArray[e($key)] = e($value);
    }
    return $processedArray;
}

/**
 * Преобразует значение в тип boolean.
 *
 * @param mixed $value
 * @return bool
 */
function to_boolean($value): bool
{
    if (is_bool($value)) {
        return $value;
    }

    if (is_string($value)) {
        return in_array(Str::lower($value), ['true', 'yes', 'on', 'y']);
    }

    return (bool) $value;
}

/**
 * Получает путь к файлу с опциональным ресайзом.
 *
 * @param int $id
 * @param int|null $width
 * @param int|null $height
 * @return string
 */
function filepath(int $id, ?int $width = null, ?int $height = null): string
{
    if ($id <= 0) {
        return '';
    }

    if ($width === null && $height === null) {
        return (string) CFile::GetPath($id);
    }

    $size = [];
    if ((int) $width > 0) {
        $size['width'] = $width;
    }

    if ((int) $height > 0) {
        $size['height'] = $height;
    }

    return (string) CFile::ResizeImageGet($id, $size, BX_RESIZE_IMAGE_PROPORTIONAL_ALT)['src'];
}

/**
 * @param     $path
 * @param int $width
 * @param int $height
 *
 * @return string
 */
function resizeImage($path, int $width, int $height) {
    // ALRSUP-1270 Вынес метод в класс-хелпер для работы с изображениями
    return ImageHelper::resizeImageByPath($path, $width, $height);
}

/**
 * Разделяет текст на отдельные слова и добавляет между ними заданный разделитель.
 *
 * @param string|null $text
 * @param string $delimeter
 * @return string
 */
function word_wrap(?string $text, string $delimeter = ' <br> '): string
{
    $words = array_filter(explode(' ', (string) $text), static function ($word) {
        return trim($word) !== '';
    });

    return implode($delimeter, $words);
}

/**
 * Возвращает транслитерованную строку
 *
 * @param string $string Строка
 * @param bool $snakeCase Использовать ли снейк кейс
 *
 * @return string
 */
function translit(string $string, bool $snakeCase = false): string
{
    $string = StringHelper::translit($string);
    return $snakeCase ? str_replace('-', '_', $string) : $string;
}

/**
 * Возвращает html компонента и результат
 *
 * @param string $name
 * @param string $template
 * @param array  $params
 *
 * @return array
 */
function component_to_string_with_result(string $name, string $template='', array $params=[])
{
    ob_start();
    $componentResult = app()->IncludeComponent($name, $template, $params);
    $html            = ob_get_contents();
    ob_get_clean();

    return [$html,$componentResult];
}

/**
 * @return \App\Helpers\ViewContent
 */
function vc()
{
    return \App\Helpers\ViewContent::getInstance();
}

/**
 * Возвращает поля объекта
 *
 * @param array|array[] $fields Массив полей
 * @param string $name Полей, значение которого надо получить
 *
 * @return string|null
 */
function get_object_value_by_name(array $fields, string $name): ?string
{
    $array = array_filter(
        $fields,
        function (stdClass $object) use ($name) {
            return $object->name == $name;
        }
    );
    return array_shift($array)->value;
}

/**
 * Возвращает массив файлов, ключ которых соответствует шаблону
 *
 * @param array|array[] $files Массив файлов
 * @param string $pattern Шаблон
 *
 * @return array|array[]
 */
function get_file_by_template_name(array $files, string $pattern): array
{
    return array_filter($files, function ($file, string $key) use ($pattern) {
        return preg_match('/' . $pattern . '/', $key) ? $file : null;
    }, ARRAY_FILTER_USE_BOTH);
}

/**
 * Функция для вывода текстовых числовых вариантов
 *
 * @param $int  - число
 * @param $expr - массив текстовых значений
 *
 * @return string
 */
function decl(int $int, array $expr)
{
    settype($int, "integer");
    $count = $int % 100;
    if ($count >= 5 && $count <= 20) {
        $result = $int . " " . $expr['2'];
    } else {
        $count = $count % 10;
        if ($count == 1) {
            $result = $int . " " . $expr['0'];
        } elseif ($count >= 2 && $count <= 4) {
            $result = $int . " " . $expr['1'];
        } else {
            $result = $int . " " . $expr['2'];
        }
    }

    return $result;
}
/**
 * Находимся ли мы в разделе аукционов
 *
 * @return bool
 */
function inAuctions(): bool
{
    $directories = env('AUCTION_DIRECTORY_LIST', ['/auctions/', '/auctions_pb/']);

    foreach ($directories as $directory) {
        if (is_directory($directory)) {
            return true;
        }
    }

    return false;
}

/**
 * Для вставок в украшениях возвращает 2 знака после точки
 *
 * @param $weight
 *
 * @return string
 */
function getUniformCaratFormatWeight($weight): string
{
    return number_format($weight, 2, '.', ' ');
}
/**
 * Первую букву в строке делает заглавной
 *
 * @param        $str
 * @param string $encoding
 *
 * @return string
 */
function mbUcFirst($str, $encoding='UTF-8'):string
{
    $str = mb_ereg_replace('^[\ ]+', '', $str);
    $str = mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding).
        mb_substr($str, 1, mb_strlen($str), $encoding);
    return $str;
}
