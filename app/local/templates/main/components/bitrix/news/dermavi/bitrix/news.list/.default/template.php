<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>
<div class="slider-main blog padding-80">
    <div class="slider-main__top">
        <div class="slider-main__title">Блог</div>
        <div class="slider-main__menu">
            <a href="#" class="slider-main__menu-item active">все</a>
            <a href="#" class="slider-main__menu-item">Новинки</a>
            <a href="#" class="slider-main__menu-item">Акции</a>
            <a href="#" class="slider-main__menu-item">тренды</a>
        </div>
        <div class="select-sort-wrap blog">
            <div class="select-sort__label">
                <i class="icon icon-list"></i>
                Сортировать по
            </div>
            <div class="select-sort__value-wrap js-open-wrap">
                <div class="icon icon-list mobile"></div>
                <div class="select-sort__value js-select js-open">рекомендации<span
                            class="icon icon-arrow_down"></span></div>
                <div class="select-sort-drop js-drop">
                    <div class="drop-city__content">
                        <div data-value="1" data-name="рекомендации" class="drop-city__link js-select-item">
                            рекомендации
                        </div>
                        <div data-value="2" data-name="сначала последние"
                             class="drop-city__link js-select-item selected">сначала последние
                        </div>
                        <div data-value="3" data-name="популярные" class="drop-city__link js-select-item">
                            популярные
                        </div>
                        <input type="hidden" name="drop-city__link-input" value="1">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="main-season_content">
        <?foreach ($arResult['ITEMS'] as $item) :?>
        <div class="main-season__item">
            <div class="main-season__item-img-wrap">
                <img src="<?=$item['PREVIEW_PICTURE']?>" alt="" class="main-season__item-img">
            </div>
            <div class="main-season__item-title"><?=$item['NAME']?></div>
            <div class="main-season__item-text"><?=$item['PREVIEW_TEXT']?>
            </div>
            <a href="#" class="main-season__item-btn">
                <a href="<?=$item['DETAIL_PAGE_URL']?>" class="btn btn-white btn-normal btn-small-padding">Подробнее <i
                            class="icon icon-arrow_right"></i>
                </a>
            </a>
        </div>
        <?endforeach;?>
    </div>
</div>


