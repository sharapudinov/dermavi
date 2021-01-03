<?php

namespace App\Core\Files;

/**
 * Интерфейс для обработки файлов (сохранение/получение из бд)
 * Interface FilesHandlerInterface
 *
 * @package App\Core\Files
 */
interface FilesHandlerInterface
{
    /**
     * Обрабатывает и получает идентификаторы файлов в бд
     *
     * @param array|mixed[] $files Массив файлов
     *
     * @return array|int[]
     */
    public function getMultipleFilesIds(array $files): array;
}
