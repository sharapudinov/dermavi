<?php

namespace App\Models\Auxiliary\CRM;

use App\Helpers\TTL;
use Arrilot\BitrixModels\Models\EloquentModel;

/**
 * Класс-модель для описания сущности "Тип документа"
 * Class IdentityDocumentType
 *
 * @package App\Models\Auxiliary\CRM
 */
class IdentityDocumentType extends EloquentModel
{
    /** @var string $table - Символьный код таблицы */
    protected $table = 'identity_document_type';

    /** @var array|string[] $fillable Массив полей, допущенных до заполнения */
    protected $fillable = ['code', 'name'];

    /** @var bool $timestamps - Флаг использования полей с датой/временем */
    public $timestamps = false;

    /**
     * Возвращает модель типа "Паспорт РФ"
     *
     * @return IdentityDocumentType
     */
    public static function getRussianPassportType(): self
    {
        return cache(
            get_class_name_without_namespace(self::class) . '_russian_passport',
            TTL::DAY,
            function () {
                return self::where('code', '13685a4e-adaa-4cca-8b58-9862ad06e874')->first();
            }
        );
    }

    /**
     * Возвращает модель типа "Паспорт иностранного гражданина"
     *
     * @return IdentityDocumentType
     */
    public static function getForeignPassportType(): self
    {
        return cache(
            get_class_name_without_namespace(self::class) . '_foreign_passport',
            TTL::DAY,
            function () {
                return self::where('code', 'dc6c7979-e8f9-408e-81d5-41d124492d36')->first();
            }
        );
    }

    /**
     * Возвращает идентификатор типа документа
     *
     * @return int
     */
    public function getId(): int
    {
        return $this['id'];
    }

    /**
     * Возвращает символьный код
     *
     * @return string
     */
    public function getXmlId(): string
    {
        return $this['code'];
    }
}
