<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class AddPersonalizationPage20201030161330190770 extends BitrixMigration
{
    private $iblockCode = 'info_pages';

    /**
     * Run the migration.
     *
     * @throws \Exception
     * @return mixed
     */
    public function up()
    {
        $iblockId = iblock_id($this->iblockCode);

        $el = new \CIBlockElement;

        $detailText = '<h2 class="page-for-customers__sub-title">ВОЗМОЖНОСТИ ПЕРСОНАЛИЗАЦИИ</h2>

<div class="page-for-customers__posibilities">
    <div class="carousel carousel--posibilities  mobile-carousel" id="carousel-posibilities" data-pagination="bullets" data-center-mode="" data-space-between="20">
        <div class="swiper-container">
            <ul class="swiper-wrapper">
                <li class="swiper-slide page-for-customers__posibility">
                    <div class="page-for-customers__icon">
                        <svg class="icon icon--pasport">
                            <use xlink:href="#icon-pasport"></use>
                        </svg>
                    </div>
                    <h5 class="page-for-customers__posibility-title">Персональный<br>паспорт</h5>
                    <p class="page-for-customers__posibility-text">
                        История Вашего бриллианта, собранная в уникальный паспорт, может быть дополнена именем бриллианта и личным обращением к близкому человеку, которому предназначен подарок.
                    </p>
                </li>
                <li class="swiper-slide page-for-customers__posibility">
                    <div class="page-for-customers__icon">
                        <svg class="icon icon--engrave_1">
                            <use xlink:href="#icon-engrave_1"></use>
                        </svg>
                    </div>
                    <h5 class="page-for-customers__posibility-title">Гравировка</h5>
                    <p class="page-for-customers__posibility-text">
                        По Вашему желанию на рундист — боковую часть бриллианта, к которой сходятся грани камня — может быть сделано нанесение инициалов, имени или важной даты. Надпись выполняется с помощью лазера и будет видна под микроскопом. В процессе нанесения гравировки ни цвет, ни чистота бриллианта не изменяются.
                    </p>
                </li>
                <li class="swiper-slide page-for-customers__posibility">
                    <div class="page-for-customers__icon">
                        <svg class="icon icon--pack">
                            <use xlink:href="#icon-pack"></use>
                        </svg>
                    </div>
                    <h5 class="page-for-customers__posibility-title">Индивидуальная упаковка</h5>
                    <p class="page-for-customers__posibility-text">
                        Бриллиант будет передан Вам в подарочной упаковке вместе со всеми необходимыми документами: сертификатом и паспортом.
                    </p>
                </li>

            </ul>

            <ul class="swiper-pagination"></ul>

        </div>
    </div>
</div>

<h2 class="page-for-customers__sub-title js-accordion-head">Упаковка</h2>
<div class="page-for-customers__text-box">
    <p class="page-for-customers__text">Сертифицированные в российской лаборатории бриллианты помещаются в прозрачную пластиковую упаковку – блистер. Он имеет просмотровое окно для изучения бриллианта без искажений, а также защищен от вскрытия во избежание подмены сертифицированного бриллианта. </p><p>К дополнительным системам защиты относятся:</p>
    <ul class="ul-list page-for-customers__ul">
        <li>оттиск знака соответствия Системы сертификации ограненных драгоценных камней;</li>
        <li>микротекст на оборотной стороне блистера;</li>
        <li>голограмма, которая разрушается при вскрытии защитной упаковки.</li>
    </ul>
    <p></p>
    <div class="page-for-customers__img-box">
        <picture class=" page-for-customers__pic">
            
            <img data-sizes="auto" data-srcset="/upload/iblock/0a7/0a7ed449084c0caebc17f422a4cb77aa.jpg" class="lazyautosizes lazyloaded" alt="" sizes="287px" srcset="/upload/iblock/0a7/0a7ed449084c0caebc17f422a4cb77aa.jpg">
        </picture>

        <p class="page-for-customers__pic-title">ПРОЗРАЧНЫЙ ПЛАСТИКОВЫЙ БЛИСТЕР</p>
    </div>
    <p class="page-for-customers__text">Внутрь упаковки помещается этикетка с названием геммологической лаборатории, знаком соответствия Системы, основными характеристиками бриллианта и номером сертификата.</p>
</div>';

        $previewText = 'Клиентская служба';

        $arLoadProductArray = [
            'IBLOCK_SECTION_ID' => false,
            'IBLOCK_ID'         => $iblockId,
            'NAME'              => 'Персонализация',
            'CODE'              => 'personalization',
            'XML_ID'            => 'personalization',
            'ACTIVE'            => 'Y',
            'DETAIL_TEXT'       => $detailText,
            'PREVIEW_TEXT'      => $previewText,
            'DETAIL_TEXT_TYPE'  => 'html',
        ];

        if ($PRODUCT_ID = $el->Add($arLoadProductArray)) {
            echo 'Создан элемент Персонализация для Информационных страниц' . PHP_EOL;
        } else {
            echo 'AddWarrantyPage Error: ' . $el->LAST_ERROR . PHP_EOL;
        }
    }

    /**
     * Reverse the migration.
     *
     * @throws \Exception
     * @return mixed
     */
    public function down()
    {
        //
    }
}