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
<div class="blog-open">
    <div class="blog-open__top">
        <div class="blog-open__top-wrap">
            <a href="/blog/" class="blog-open__back"><i class="icon icon-arrow_left"></i>Вернуться в блог</a>
            <div class="blog-open__title">
                <b><i>технология</i></b> <br>
                <b>lux hudrapower</b>
            </div>
            <div class="blog-open__top-bottom">
                <div class="blog-open__date">10 октября 2020</div>
                <div class="blog-open__views"><i class="icon icon-eye"></i>21 922</div>
            </div>
        </div>
        <div class="blog-open__top-img-wrap"><img src="img/banner.jpg" alt="" class="blog-open__top-img"></div>
    </div>
    <div class="blog-open__content">
        <div class="blog-open__content__title"><?=$arResult['NAME']?></div>
        <div class="blog-open__content-text-wrap"><?=$arResult['DETAIL_TEXT']?>

            <a href="/blog/" class="blog-open__link">Показать список <i class="icon icon-arrow_right"></i></a>
            <!--</div>-->
        </div>
    </div>
</div>

