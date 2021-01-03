<?php
namespace App\Components;

use App\Helpers\TTL;
use App\Models\User;
use Arrilot\BitrixModels\Queries\BaseQuery;
use Illuminate\Container\Container;
use Illuminate\Support\Collection;
use LogicException;

/**
 * Расширения для базового класса компонента.
 * Class ExtendedComponent
 * @package App\Components
 */
class ExtendedComponent extends BaseComponent
{
    /**
     * Возвращает время кэширования, заданное в параметре CACHE_TIME переведенное в минуты.
     * @param int $default
     * @return int
     */
    protected function getCacheTime(int $default = TTL::DAY): int
    {
        $cacheTime = $this->arParams['CACHE_TIME'];
        if (!is_int($cacheTime) || $cacheTime < 0) {
            $cacheTime = $default;
        }
        return TTL::sec2min($cacheTime);
    }
    
    /**
     * Возвращает путь к папке для кэширования методов компонента.
     * @return string
     */
    protected function getCacheDir(): string
    {
        return SITE_ID . '/components/' . $this->getName();
    }
    
    /**
     * Добавляет значения из массива в $arResult компонента.
     * @param array $results
     */
    protected function addResults(array $results): void
    {
        $this->arResult = array_merge($this->arResult, $results);
    }
    
    /**
     * Добавляет авторизованного пользователя в выходной массив. Если такого нет - выполняет
     * редирект на $errorRedirect или возвращает 404 ошибку.
     *
     * @param string|null $errorRedirect
     * @return User
     */
    protected function requireAuthorizedUser(string $errorRedirect = null): User
    {
        $user = $this->addUser();
        if (!$user->isAuthorized()) {
            define('FROM_COMPONENT', true);
            $this->errorRedirect($errorRedirect);
        }
        return $user;
    }
    
    /**
     * Добавляет текущего или заданного в параметрах пользователя в выходной массив.
     * @return User
     */
    protected function addUser(): User
    {
        $user = $this->arParams['USER'] ?? $this->arParams['USER_ID'];
        
        if (is_int($user)) {
            $user = User::getById($user);
        }
        
        if (!($user instanceof User)) {
            $user = User::current();
        }
        
        $this->arResult['user'] = $user;
        return $user;
    }
    
    /**
     * Загружает справочник. Если коллекция элементов справочника передана в параметрах компонента,
     * то используется она, иначе выполняется запрос к БД.
     *
     * @param string $name - имя набора результатов
     * @param BaseQuery|string|callable $query - источник для получения набора
     * @param string|null $paramName - имя параметра, в котором ищется уже загруженный набор
     * @return Collection
     */
    protected function addResultset(string $name, $query, string $paramName = null): Collection
    {
        $value = $paramName ? $this->arParams[$paramName] : null;
        
        if (!($value instanceof Collection)) {
            if (is_string($query) && method_exists($this, $query)) {
                $value = call_user_func([$this, $query]);
            } elseif (is_callable($query)) {
                $value = $query();
            } else {
                $value = $query;
            }
            
            if ($value instanceof BaseQuery) {
                $value = $value->getList();
            }
        }
        
        if (!($value instanceof Collection)) {
            throw new LogicException("Expected Collection for resultset \"$name\"");
        }
        
        $this->arResult[$name] = $value;
        return $value;
    }
    
    /**
     * Кэширует и возвращает результат выполнения метода компонента.
     * @param string $methodName
     * @param null|array $keyParams
     * @return mixed
     */
    protected function cacheMethod(string $methodName, $keyParams = null)
    {
        $cacheKey = SITE_ID . '_' . $this->getName() . '_' . $methodName;
        if ($keyParams !== null) {
            $cacheKey .= '_' . md5(json_encode($keyParams));
        }
        
        return cache(
            $cacheKey,
            $this->getCacheTime(),
            function () use ($methodName) {
                return Container::getInstance()->call([$this, $methodName]);
            },
            $this->getCacheDir()
        );
    }
    
    /**
     * Выполняет редирект в случае ошибки.
     * @param string|null $errorRedirect
     */
    protected function errorRedirect(string $errorRedirect = null): void
    {
        if ($errorRedirect !== null) {
            LocalRedirect(SITE_DIR . $errorRedirect);
        } else {
            $this->show404();
        }
    }
}
