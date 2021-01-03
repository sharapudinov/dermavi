<?php

namespace App\Components;

use CBitrixComponent;
use LogicException;
use Illuminate\Container\Container;

/**
 * Class BaseComponent
 *
 * @package App\Components
 *
 * @property-write array|mixed[] $arResult Массив, описывающий результаты выполнения компонента
 * @property-read array|mixed[] $arParams Массив, описывающий параметры компонента
 *
 * @method void includeComponentTemplate(string $template = null)
 */
class BaseComponent extends CBitrixComponent
{
    /**
     * Массив параметров.
     *
     * @var array
     */
    protected $params = [];
    
    /**
     * Завершение работы компонента показом 404-ой страницы.
     */
    public function show404()
    {
        $this->abortResultCache();

        show404();
    }

    /**
     * @return mixed|void
     */
    public function executeComponent()
    {
        if (method_exists($this, 'execute')) {
            if (class_exists(Container::class)) {
                Container::getInstance()->call([$this, 'execute']);
            } else {
                $this->execute();
            }
        }

        debug_var($this->arResult, "Результат компонента " . $this->getName());
    }
    
    /**
     * Обработка $arParams на основе $this->params.
     * Доступные ключи:
     *  'required' - помечает параметр как обязательный
     *  'default' - задает значение по умолчанию
     *  'type' - конвертирует значение к указанному типу: "bool", "int", "float", "array", "array", "null"
     *
     * @param $arParams
     * @return array
     * @throws LogicException
     */
    public function onPrepareComponentParams($arParams)
    {
        foreach ($this->params as $code => $parameter) {
            if (!empty($parameter['required']) && !isset($arParams[$code])) {
                throw new LogicException('Required parameter "' .$code . '" is not set in "$arParams"');
            }

            if (isset($parameter['default']) && !isset($arParams[$code])) {
                $arParams[$code] = $parameter['default'];
            }

            if (!empty($parameter['type'])) {
                settype($arParams[$code], $parameter['type']);
            }
        }

        debug_var($arParams, "Параметры компонента " . $this->getName());

        return $arParams;
    }

    /**
     * Добавить свой путь к шаблонам blade
     *
     * @param $path
     */
    public function addBladePath($path)
    {
        $finder = Container::getInstance()->make('view.finder');

        $paths = $finder->getPaths();

        if (!in_array($path, $paths)) {
            $paths[] = $path;
        }

        // Необходимо очистить внутренний кэш ViewFinder-а
        // Потому что иначе если в родительском компоненте есть @include('foo'), то при вызове @include('foo')
        // из дочернего, он не будет искать foo в дочернем, а сразу подключит foo из родительского компонента
        $finder->flush();

        $finder->setPaths($paths);
    }

    /**
     * Возвращает значение из $arResult.
     *
     * @param string $name
     *
     * @return mixed
     */
    public function __get($name)
    {
        return $this->arResult[$name];
    }

    /**
     * Устанавливает значение в $arResult
     *
     * @param string $name
     * @param mixed  $value
     */
    public function __set($name, $value)
    {
        $this->arResult[$name] = $value;
    }

    /**
     * @param $name
     *
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->arResult[$name]);
    }
}
