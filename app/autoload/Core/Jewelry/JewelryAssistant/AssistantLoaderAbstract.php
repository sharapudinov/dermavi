<?php

namespace App\Core\Jewelry\JewelryAssistant;

use App\Models\Jewelry\Dicts\JewelryAssistantStyle;
use Illuminate\Support\Collection;

/**
 * Абстрактный класс, описывающий логику получения данных о ЮБИ из каталога для результатов помощника по стилю
 * Class AssistantLoaderAbstract
 *
 * @package App\Core\Jewelry\JewelryAssistant
 */
abstract class AssistantLoaderAbstract
{
    /** @var int Количество элементов на странице */
    public const PAGE_COUNT = 4;

    /** @var array|mixed[] $arParams Массив параметров компонента страницы результатов помощника по стилю */
    protected $arParams;

    /**
     * AssistantLoaderAbstract constructor.
     *
     * @param array|mixed[] $arParams Массив параметров компонента страницы результатов помощника по стилю
     */
    public function __construct(array $arParams)
    {
        $this->arParams = $arParams;
    }

    /**
     * Возвращает предпочтительные для пользователя стили
     *
     * @return Collection|JewelryAssistantStyle[]
     */
    protected function getPreferredStyles(): Collection
    {
        $countValues = array_count_values($this->arParams['styles']);
        $codes = [];
        foreach ($countValues as $key => $count) {
            $codes[$count][] = $key;
        }

        $assistantStylesCodes = $codes[max(array_keys($codes))];
        return JewelryAssistantStyle::filter(['UF_XML_ID' => $assistantStylesCodes])
            ->with('collections')
            ->getList();
    }

    /**
     * Возвращает данные для компонента
     *
     * @return array|mixed[]
     */
    abstract public function getData(): array;
}
