<?php

namespace App\Core;

use Illuminate\Support\Fluent;

/**
 * Класс для ленивой загрузки данных, если атрибута нет в модели, то вызывается сначала геттер
 * Class ProductInfo
 *
 * @package App\Components
 */
class LazyLoadDto extends Fluent
{
    /**
     * @param string $name
     *
     * @return mixed
     * @throws \Exception
     */
    public function __get($name)
    {
        if (!isset($this->attributes[$name])) {
            $getterName = 'get'.ucfirst($name);

            //Если есть геттер, используем его
            if (is_callable([$this, $getterName])) {
                $this->attributes[$name] = $this->$getterName();
            } else {
                throw new \LogicException('Cant find getter '.$getterName.' in class '.static::class);
            }
        }

        if (!array_key_exists($name, $this->attributes)) {
            throw new \Exception("Property '$name'' is not found");
        }

        return parent::__get($name);
    }
}
