<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class AddCertificationPage20201030150050210081 extends BitrixMigration
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

        $detailText = '<h2 class="page-for-customers__sub-title">СЕРТИФИКАЦИЯ</h2>
<p class="page-for-customers__text">Согласно требованиям российского законодательства, сертификация бриллианта в российской геммологической лаборатории обязательна для осуществления продажи его&nbsp;физическому&nbsp;лицу.</p>
<p class="page-for-customers__text">Сертификат на бриллиант содержит информацию о его основных параметрах, таких как:</p>
<ul class="ul-list page-for-customers__ul">
    <li>форма огранки</li>
    <li>масса</li>
    <li>цвет</li>
    <li>чистота</li>
    <li>флуоресценция</li>
</ul>
<p class="page-for-customers__text">Кроме того, подлежат определению основные размеры бриллиантов, которыми являются:</p>
<ul class="ul-list page-for-customers__ul">
    <li>минимальный диаметр;</li>
    <li>максимальный диаметр;</li>
    <li>длина и ширина (для бриллиантов фантазийных форм огранки);</li>
    <li>общая высота;</li>
    <li>размер площадки;</li>
    <li>угол короны и глубина павильона;</li>
    <li>толщина рундиста;</li>
    <li>размер калетты;</li>
    <li>качество симметрии и полировки.</li>
</ul>

<h2 class="page-for-customers__sub-title js-accordion-head">РОССИЙСКИЙ СЕРТИФИКАТ</h2>
<div class="page-for-customers__text-box">
    <div class="page-for-customers__img-box">
        <picture class=" page-for-customers__pic">
            
            <img data-sizes="auto" data-srcset="/upload/iblock/ef2/ef21472dea80b756e08d87277a21373c.jpg" class="lazyautosizes lazyloaded" alt="Российский сертификат" sizes="460px" srcset="/upload/iblock/ef2/ef21472dea80b756e08d87277a21373c.jpg">
        </picture>

        <p class="page-for-customers__pic-title">Сертификат соответствия бриллианта</p>
    </div>
    <p class="page-for-customers__text">Бланк сертификата напечатан на гербовой бумаге формата А4 водяными знаками и другими дополнительными элементами защиты. Заполненный бланк сертификата ламинируется. В сертификате также приведен детальный эскиз бриллианта с указанием всех его внутренних и внешних дефектов.</p>
</div>

<h2 class="page-for-customers__sub-title js-accordion-head">СЕРТИФИКАТ GIA</h2>
<div class="page-for-customers__text-box page-for-customers__text-box--border mb-0">
    <p class="page-for-customers__text"> В дополнение к обязательной сертификации по российским стандартам мы можем по вашему желанию предоставить услугу по сертификации в международной лаборатории GIA – Геммологического института Америки. Сертификаты, выданные на один и тот же бриллиант разными сертификационными центрами мира, могут отличаться. Это связано с особенностями систем и критериев оценки бриллиантов разных геммологических лабораторий.</p>
    <div class="page-for-customers__img-box">
        <picture class=" page-for-customers__pic">
            
            <img data-sizes="auto" data-srcset=" /upload/iblock/458/458e4ec5795210ca892b17c14ea506be.jpg" class="lazyautosizes lazyloaded" alt="Сертификат GIA" sizes="699px" srcset=" /upload/iblock/458/458e4ec5795210ca892b17c14ea506be.jpg">
        </picture>

        <p class="page-for-customers__pic-title mb-0">СЕРТИФИКАТ GIA</p>
    </div>
</div>';

        $previewText = 'Клиентская служба';

        $arLoadProductArray = [
            'IBLOCK_SECTION_ID' => false,
            'IBLOCK_ID'         => $iblockId,
            'NAME'              => 'Сертификация',
            'CODE'              => 'certification',
            'XML_ID'            => 'certification',
            'ACTIVE'            => 'Y',
            'DETAIL_TEXT'       => $detailText,
            'PREVIEW_TEXT'      => $previewText,
            'DETAIL_TEXT_TYPE'  => 'html',
        ];

        if ($PRODUCT_ID = $el->Add($arLoadProductArray)) {
            echo 'Создан элемент Сертификация для Информационных страниц' . PHP_EOL;
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