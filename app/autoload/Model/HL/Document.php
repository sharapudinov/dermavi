<?php

namespace App\Models\HL;

use App\Helpers\DateTimeHelper;
use App\Helpers\LanguageHelper;
use App\Models\Client\PersonalSectionDocumentKind;
use Arrilot\BitrixModels\Models\D7Model;
use Arrilot\BitrixModels\Queries\ElementQuery;
use CFile;
use DateTime;
use Exception;

/**
 * Класс-модель для описания сущности "Документ"
 * Class Document
 *
 * @package App\Models\HL
 *
 * @property-read PersonalSectionDocumentKind $documentType
 */
class Document extends D7Model
{
    /** @var string - Символьный код таблицы */
    const TABLE_CODE = 'app_user_document';

    /**
     * Получает информацию по хлблоку
     *
     * @return array
     */
    public static function tableClass()
    {
        return highloadblock_class(self::TABLE_CODE);
    }

    /**
     * Получает идентификатор записи
     *
     * @return int
     */
    public function getId(): int
    {
        return $this['ID'];
    }

    /**
     * Получает идентификатор пользователя, загрузившего документ
     *
     * @return int
     */
    public function getUserID(): int
    {
        return (int) $this['UF_USER_ID'];
    }

    /**
     * Получает статус проверки документа
     *
     * @return string
     */
    public function getStatus(): string
    {
        return $this['UF_STATUS'];
    }

    /**
     * Получает код документа
     *
     * @return string
     */
    public function getCode(): string
    {
        return (string) $this['UF_CODE'];
    }

    /**
     * Смотрит является ли документ одобренным
     *
     * @return bool
     */
    public function isApproved(): bool
    {
        return $this->getStatus() == 'approved';
    }

    /**
     * Смотрит на рассмотрении ли документ
     *
     * @return bool
     */
    public function inProcess(): bool
    {
        return $this->getStatus() == 'process';
    }

    /**
     * Получает путь до документа
     *
     * @return string
     */
    public function getFilePath(): string
    {
        return CFile::GetPath($this->getFileID());
    }

    /**
     * Возвращает массив идентификаторов документов
     *
     * @return array|int[]
     */
    public function getFilesIds(): array
    {
        return $this['UF_FILES'];
    }

    /**
     * Получает дату загрузки
     *
     * @return string|null
     *
     * @throws Exception
     */
    public function getUploadDateTransformed(): ?string
    {
        return strtolower(date('d', strtotime($this['UF_DATE_CREATE'])) . ' '
            . DateTimeHelper::getMonthInNecessaryLanguage(
                new DateTime($this['UF_DATE_CREATE']),
                LanguageHelper::getLanguageVersion(),
                '',
                null,
                'M'
            ) . ' '
            . date('Y', strtotime($this['UF_DATE_CREATE'])));
    }

    /**
     * Получает дату загрузки
     *
     * @param string $format Формат
     *
     * @return string
     */
    public function getUploadDate($format = 'd.m.Y'): string
    {
        return $this['UF_DATE_CREATE'] ? $this['UF_DATE_CREATE']->format($format) : null;
    }

    /**
     * Получает дату обновления
     *
     * @param string $format Формат
     *
     * @return string|null
     */
    public function getUpdatedDate(string $format = 'd.m.Y'): ?string
    {
        return $this['UF_DATE_UPDATE'] ? $this['UF_DATE_UPDATE']->format($format) : null;
    }

    /**
     * Получает тип документа
     *
     * @return ElementQuery
     */
    public function documentType(): ElementQuery
    {
        return $this->hasOne(PersonalSectionDocumentKind::class, 'ID', 'UF_CODE');
    }
}
