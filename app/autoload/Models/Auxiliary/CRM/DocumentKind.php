<?php

namespace App\Models\Auxiliary\CRM;

use Arrilot\BitrixModels\Models\EloquentModel;

/**
 * Класс-модель для описания сущности "Тип документа в CRM"
 * Class DocumentKind
 * @package App\Models\Auxiliary\CRM
 */
class DocumentKind extends EloquentModel
{
    /** @var string $table - Символьный код таблицы */
    protected $table = 'app_crm_document_kind';

    /** @var bool $timestamps - Флаг использования полей с датой/временем */
    public $timestamps = false;

    /**
     * Возвращает код типа
     *
     * @return string
     */
    public function getCode(): string
    {
        return $this['code'];
    }

    /**
     * Возвращает название типа
     *
     * @return string
     */
    public function getName(): string
    {
        return $this['name'];
    }
}
