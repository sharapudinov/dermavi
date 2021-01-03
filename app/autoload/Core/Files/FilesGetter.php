<?php

namespace App\Core\Files;

use CFile;

/**
 * Класс, описывающий работу с существующими файлами
 * Class FilesGetter
 *
 * @package App\Core\Files
 */
class FilesGetter implements FilesHandlerInterface
{
    /**
     * Обрабатывает и получает идентификаторы файлов в бд
     *
     * @param array|mixed[] $files Массив файлов
     *
     * @return array|int[]
     */
    public function getMultipleFilesIds(array $files): array
    {
        if (!$files) {
            return [];
        }

        $dbFiles = CFile::GetList([], ['@FILE_NAME' => implode(',', $files)]);
        $passportScansIds = [];
        while ($dbFile = $dbFiles->GetNext()) {
            $passportScansIds[] = $dbFile['ID'];
        }

        return array_map(function (int $passportScansId) {
            return CFile::MakeFileArray($passportScansId);
        }, $passportScansIds);
    }

    /**
     * Возвращает информацию о файле
     *
     * @param int $fileId Идентификатор файла
     *
     * @return array|null
     */
    public static function getFileInfo(int $fileId): ?array
    {
        return db()->query('SELECT * FROM b_file WHERE ID = ' . $fileId)->fetch();
    }
}
