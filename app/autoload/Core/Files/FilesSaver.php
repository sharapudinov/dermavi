<?php

namespace App\Core\Files;

use CFile;

/**
 * Класс, описывающий сохранение файлов в бд
 * Class FilesSaver
 *
 * @package App\Core\Files
 */
class FilesSaver implements FilesHandlerInterface
{
    /** @var string $entity Сущность */
    private $entity;

    /**
     * Записывает в объект сущность
     *
     * @param string $entity Сущность
     *
     * @return FilesSaver
     */
    public function setEntity(string $entity): self
    {
        $this->entity = $entity;
        return $this;
    }

    /**
     * Обрабатывает и получает идентификаторы файлов в бд
     *
     * @param array|mixed[] $files Массив файлов
     *
     * @return array|int[]
     */
    public function getMultipleFilesIds(array $files): array
    {
        /** @var array|int[] $passportScansIds Массив идентификаторов сканов паспортов текущей сущности */
        $passportScansIds = [];
        foreach ($files as $file) {
            $passportScansIds[] = CFile::SaveFile($file, $this->entity);
        }

        return array_map(function (int $passportScansId) {
            return CFile::MakeFileArray($passportScansId);
        }, $passportScansIds);
    }
}
