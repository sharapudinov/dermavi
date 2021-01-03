<?php

namespace App\Core\Token;

use App\Core\Interfaces\MemoryCachingInterface;
use App\Core\Memcache\MemcacheInitializer;

/**
 * Class SimpleTokenizer
 * Простой токенизатор с хранилищем в memcache
 *
 * @package App\Core\Token
 */
class SimpleTokenizer
{
    /** @var string */
    private $entityName;

    /** @var MemoryCachingInterface */
    private $storageInstance;

    /**
     * @param string $entityName Имя сущности для которой генерируется токен
     */
    public function __construct(string $entityName)
    {
        $this->entityName = $entityName;
    }

    /**
     * @return MemoryCachingInterface|null
     */
    protected function getStorageInstance(): ?MemoryCachingInterface
    {
        if ($this->storageInstance === null) {
            $this->storageInstance = MemcacheInitializer::getInstance();
        }

        return $this->storageInstance;
    }

    /**
     * Имя сущности для которой генерируется токен
     *
     * @return string
     */
    public function getEntityName(): string
    {
        return $this->entityName;
    }

    /**
     * Генерация токена
     *
     * @param mixed $checkValue Контрольное значение для проверки по токену
     * @param int $ttlSec Время жизни токена
     * @return string
     */
    public function generate($checkValue, int $ttlSec = 60): string
    {
        $storage = $this->getStorageInstance();
        if (!$storage) {
            return '';
        }

        $token = $this->genToken();
        $storage->set(
            $this->getKey($token),
            $checkValue,
            null,
            $ttlSec
        );

        return $token;
    }

    /**
     * Проверка значения токена
     *
     * @param string $token
     * @param mixed $checkValue
     * @return bool
     */
    public function check(string $token, $checkValue): bool
    {
        /** @noinspection TypeUnsafeComparisonInspection */
        return $this->getValue($token) == $checkValue;
    }

    /**
     * Сброс токена
     *
     * @param string $token
     * @return static
     */
    public function flush(string $token)
    {
        if ($storage = $this->getStorageInstance()) {
            $storage->delete($this->getKey($token));
        }

        return $this;
    }

    /**
     * Получение значения по токену
     *
     * @param string $token
     * @return array|mixed|string|null
     */
    public function getValue(string $token)
    {
        $storage = $this->getStorageInstance();
        if (!$storage) {
            return null;
        }

        return $storage->get($this->getKey($token));
    }

    /**
     * @return string
     */
    protected function genToken(): string
    {
        return uniqid('tok', true);
    }

    /**
     * @param string $primary
     * @return string
     */
    protected function getKey(string $primary): string
    {
        return 'TOKENIZER:' . $this->getEntityName() . ':' . $primary;
    }
}
