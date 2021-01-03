<?php

namespace App\Models\Auxiliary;

use App\Helpers\LanguageHelper;
use Arrilot\BitrixModels\Models\EloquentModel;
use Illuminate\Support\Collection;

/**
 * Класс-модель для сущности "Страна"
 * Class Country
 * @package App\Models\Auxiliary
 */
class Country extends EloquentModel
{
    /** @var bool $timestamps */
    public $timestamps = false;
    /** @var string $table */
    protected $table = 'country';

    /**
     * Получаем коллекцию всех стран с названиями на нужном языке
     *
     * @return Collection
     */
    public static function baseQuery(): Collection
    {
        $language = LanguageHelper::getLanguageVersion();
        return self::query()->select('id', 'name_' . $language)->orderBy('name_' . $language)->get();
    }

    /**
     * Получаем идентификатор страны в бд
     *
     * @return int
     */
    public function getId(): int
    {
        return $this['id'];
    }

    /**
     * Получаем название страны на нужном языке
     *
     * @return string
     */
    public function getName(): string
    {
        return (string)$this['name_' . LanguageHelper::getLanguageVersion()];
    }
}
