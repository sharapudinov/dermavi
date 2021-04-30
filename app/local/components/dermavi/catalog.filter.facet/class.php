<?php
/** @noinspection AutoloadingIssuesInspection */

namespace App\Local\Component;

use App\Components\BaseComponent;
use App\Core\Currency\Entity\CurrencyEntity;
use App\Core\Product\ProductFacetFilter;
use App\Core\Product\ProductFilterMetaTagGenerator;
use App\Core\Exceptions\CurrencyNotFoundException;
use App\Core\User\Context\UserContext;
use App\Filtration\FilterRequest\ArrayRequest;
use App\Helpers\IBlock\SectionHelper;
use App\Helpers\LanguageHelper;
use App\Helpers\TTL;
use App\Models\Catalog\CatalogSection;
use App\Models\Catalog\Catalog;
use Throwable;

/**
 * Class CatalogMainFilterFacetComponent
 * Компонент фасетного фильтра для каталога бриллиантов
 *
 * @package App\Local\Component
 */
class CatalogMainFilterFacetComponent extends BaseComponent
{
    /** @var int */
    private $sectionId;

    /** @var array|array[] $params - Параметры компонента */
    protected $params = [
        'process_title'               => ['type' => 'bool', 'default' => true],
        'hide_fields'                 => ['type' => 'array', 'default' => []],
        'show_catalog_types_variants' => ['type' => 'bool', 'default' => true],
    ];

    /**
     * Определяет параметры компонента
     *
     * @param array|mixed[] $arParams - Параметры компонента
     * @return array
     */
    public function onPrepareComponentParams($arParams): array
    {
        $arParams['CACHE_TIME'] = $arParams['CACHE_TIME'] ?? TTL::HOUR * 60;

        return parent::onPrepareComponentParams($arParams);
    }

    /**
     * @return int Время кеширования, сек
     */
    protected function getCacheTime(): int
    {
        return (int)($this->arParams['CACHE_TYPE'] === 'N' ? 0 : $this->arParams['CACHE_TIME']);
    }

    /**
     * @return int
     */
    protected function getCacheTimeInMinutes(): int
    {
        return (int)($this->getCacheTime() / 60);
    }

    /**
     * @return string
     */
    public function getLanguageVersion(): string
    {
        return UserContext::getCurrent()->getLanguageVersion();
    }

    /**
     * @return CurrencyEntity
     * @throws CurrencyNotFoundException
     */
    public function getCurrency(): CurrencyEntity
    {
        return UserContext::getCurrent()->getCurrencyInstance();
    }

    /**
     * @return int
     */
    public function getPageNum(): int
    {
        $pageNum = (int)($_REQUEST['p'] ?? 1);

        return $pageNum > 0 ? $pageNum : 1;
    }

    /**
     * @return bool
     */
    public function isLegalEntity(): bool
    {
        return UserContext::getCurrent()->isLegalEntity();
    }

    /**
     * Устанавливает заголовок для страницы
     *
     * @param array $metaTagsData
     * @return static
     */
    private function setMetaTitle(array $metaTagsData)
    {
        if (($metaTagsData['SECTION_PAGE_TITLE'] ?? '') !== '') {
            app()->SetTitle($metaTagsData['SECTION_PAGE_TITLE']);
        }

        return $this;
    }

    /**
     * @param array $metaTagsData
     * @return static
     */
    private function setMetaDescription(array $metaTagsData)
    {
        $sectionDescription = LanguageHelper::getMarkedTextByLang(
            (string)($metaTagsData['SECTION_META_DESCRIPTION'] ?? ''),
            $this->getLanguageVersion()
        );

        app()->SetPageProperty('description', $sectionDescription);

        return $this;
    }

    /**
     * @param array $metaTagsData
     * @return static
     */
    private function setMetaKeywords(array $metaTagsData)
    {
        $sectionKeywords = LanguageHelper::getMarkedTextByLang(
            (string)($metaTagsData['SECTION_META_KEYWORDS'] ?? ''),
            $this->getLanguageVersion()
        );

        app()->SetPageProperty('keywords', $sectionKeywords);

        return $this;
    }

    /**
     * @return string
     */
    protected function getSectionCode(): string
    {
        $sectionCode = CatalogSection::FOR_PHYSIC_PERSONS_SECTION_CODE;

        return $sectionCode;
    }

    /**
     * @return ArrayRequest
     */
    protected function getFilterRequest(): ArrayRequest
    {
        return new ArrayRequest($_GET);
    }

    /**
     * @return int
     */
    protected function getIBlockId(): int
    {
        return Catalog::iblockID();
    }

    /**
     * @return int
     */
    protected function getSectionId(): int
    {
        if ($this->sectionId === null) {
            $this->sectionId = SectionHelper::getSectionId($this->getSectionCode(), $this->getIBlockId());
        }

        return $this->sectionId;
    }

    /**
     * @return array
     * @throws Throwable
     */
    private function getFilterInfo(): array
    {
        $filterDataResult = (new ProductFacetFilter($this->getSectionId()))
            ->getFilterDataResultCollection(
                $this->getFilterRequest()
            );

        $result = [];
        $result['filterFields'] = [];
        foreach ($filterDataResult as $resultItem) {
            $fieldDto = $resultItem->getFieldDto();
            if (!$fieldDto) {
                continue;
            }
            $result['filterFields'][$fieldDto->getCode()] = $fieldDto;
        }

        return $result;
    }

    /**
     * Запускаем компонент
     *
     * @return void
     * @throws CurrencyNotFoundException
     */
    public function executeComponent(): void
    {
        try {
            $filterInfo = $this->getFilterInfo();
        } catch (Throwable $exception) {
            logger()->error(
                'Component catalog:main.filter.facet exception: ' . $exception->getMessage(),
                [
                    '$_GET' => $_GET,
                ]
            );

            return;
        }

        $this->arResult = [
            'isB2C'        => $this->arParams['IS_B2C'],
            'currency'     => $this->getCurrency(),
            'sectionId'    => $this->getSectionId(),
            'filterFields' => $filterInfo['filterFields'],
        ];

        // Общие параметры для отправки в ajax-запросе обновления фильтра (см. CatalogFilterController::filterDiamonds())
        $this->arResult['filterBaseParams'] = [
            'sectionId'    => $this->arResult['sectionId'],
            'currencyCode' => $this->arResult['currency']->getSymCode(),
            'language'     => $this->getLanguageVersion(),
            'legalEntity'  => $this->isLegalEntity() ? 'Y' : 'N',
        ];

        if ($this->arParams['process_title']) {
            $metaTagsGenerator = new ProductFilterMetaTagGenerator(
                $this->getSectionId(),
                $this->getLanguageVersion()
            );
            $metaTagsData = $metaTagsGenerator->getMetaTags($filterInfo['filterFields'], $this->getPageNum());

            $this->setMetaTitle($metaTagsData)
                ->setMetaDescription($metaTagsData)
                ->setMetaKeywords($metaTagsData);

            $this->arResult['titleTemplate'] = $metaTagsData['~SECTION_PAGE_TITLE'] ?? '';
        }

        $this->includeComponentTemplate();
    }
}
