<?php

namespace App\Core\User\General\Entity;

use App\Core\Files\FilesGetter;
use App\Helpers\FileHelper;
use App\Models\HL\Document as DocumentModel;
use CFile;

/**
 * Класс для описания сущности "Документ"
 * Class Document
 * @package App\Core\User\LegalPerson\Entity
 */
class Document
{
    /** @var string|null $ID - ID CRM */
    private $ID;

    /** @var string|null $DocumentKind - Вид документа */
    private $DocumentKind;

    /** @var array $Data - Массив байтов, описывающий файл */
    private $Data;

    /** @var string|null $ValidDate - Дата актуальности */
    private $ValidDate;

    /** @var string|null $Comment - Комментарий */
    private $Comment;

    /** @var string $FileName Название файла */
    private $FileName;

    /**
     * Записывает данные на основе документа в БД
     *
     * @param DocumentModel $document Документ из БД
     * @param int $fileId Идентификатор файла
     *
     * @return Document
     */
    public function setFromDatabaseDocument(DocumentModel $document, int $fileId): self
    {
        $this->DocumentKind = $document->documentType->getCode();
        $this->Data = FileHelper::createByteArray(CFile::GetPath($fileId));
        $this->ValidDate = $document->getUpdatedDate('Y-m-d') ?? $document->getUploadDate('Y-m-d');
        $this->Comment = '';
        $this->FileName = FilesGetter::getFileInfo($fileId)['ORIGINAL_NAME'];

        return $this;
    }

    /**
     * Получаем ID CRM
     *
     * @return null|string
     */
    public function getID(): ?string
    {
        return $this->ID;
    }

    /**
     * Получаем вид документа
     *
     * @return string|null
     */
    public function getDocumentKind(): ?string
    {
        return $this->DocumentKind;
    }

    /**
     * Записывает файл
     *
     * @param string $data - Файл
     * @return Document
     */
    public function setData(string $data): self
    {
        $this->Data = $data;
        return $this;
    }

    /**
     * Получаем файл
     *
     * @return array
     */
    public function getData(): array
    {
        return $this->Data;
    }

    /**
     * Получаем дату активности документа
     *
     * @return string|null
     */
    public function getValidDate(): ?string
    {
        return $this->ValidDate;
    }

    /**
     * Получаем комментарий
     *
     * @return string|null
     */
    public function getComment(): ?string
    {
        return $this->Comment;
    }
}
