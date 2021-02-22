<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class AddTryOnPage20201130135945203739 extends BitrixMigration
{
    private $iblockCode = 'info_pages';

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $iblockId = iblock_id($this->iblockCode);

        $detailText = '<p class="page-for-customers__text">
	 Вам понравилось ювелирное украшение, но вы не уверены, подойдёт ли оно вам на 100%?
</p>

<ul class="ul-list page-for-customers__ul">
	<li><a href="/jewelry/" class="link link--btn">ВЫБРАТЬ УКРАШЕНИЕ</a></li>
	<li><a href="/cart/" class="link link--btn">ПЕРЕЙТИ В КОРЗИНУ</a></li>
</ul>

<p class="page-for-customers__text">
	 Воспользуйтесь услугой примерки. Для этого добавьте в корзину от 1 до 3 ювелирных украшений на общую сумму, не превышающую 200 000 рублей.
</p>

<h2 class="page-for-customers__sub-title js-accordion-head">
	 ВАЖНО!
</h2>

<p class="page-for-customers__text">
	 При примерке доставленного украшения вы должны сохранить целостность ярлыка, размещённого на ювелирном изделии, а также само крепление ярлыка к нему, включая пломбу. В противном случае вы не сможете вернуть товар и будете обязаны заплатить его полную стоимость курьеру.
</p>

<h2 class="page-for-customers__sub-title js-accordion-head">
	 ОПЛАТА ПРИ ПОЛУЧЕНИИ
</h2>

<p class="page-for-customers__text">
	 Возможно два варианта:
</p>

<ul class="ul-list page-for-customers__ul">
	<li>расчёт наличными средствами <br>до 100&nbsp;000 рублей;</li>
	<li>расчёт банковской картой <br>до 200&nbsp;000 руб.</li>
</ul>

<h2 class="page-for-customers__sub-title js-accordion-head">
	 ВРЕМЯ ПРИМЕРКИ
</h2>

<p class="page-for-customers__text">
	 Время примерки всего комплекта ювелирных украшений составляет не более 15 минут, с момента получения посылки.
</p>

<p class="page-for-customers__text">
	 Помните, что по окончании примерки вы обязываетесь заплатить курьеру стоимость выбранной ювелирной продукции и подписать отгрузочные документы: акт приёма-передачи или акт возврата отправлений.
</p>

<p class="page-for-customers__text">
	 Если вы решаете не выкупать ни одного ювелирного украшения, вы должны передать все товары курьеру и подписать возвратные документы.
</p>

<p class="page-for-customers__text" style="text-align: center">
	 Блестящих покупок!
</p>';
        $previewText = 'Клиентская служба';

        $el = new \CIBlockElement;

        $arLoadProductArray = [
            'IBLOCK_SECTION_ID' => false,
            'IBLOCK_ID'         => $iblockId,
            'NAME'              => 'Примерка',
            'CODE'              => 'try-on',
            'XML_ID'            => 'try-on',
            'ACTIVE'            => 'Y',
            'DETAIL_TEXT'       => $detailText,
            'DETAIL_TEXT_TYPE'  => 'html',
            'PREVIEW_TEXT'      => $previewText,
        ];

        if ($PRODUCT_ID = $el->Add($arLoadProductArray)) {
            echo 'Создан элемент Примерка для Информационных страниц' . PHP_EOL;
        } else {
            echo 'AddWarrantyPage Error: ' . $el->LAST_ERROR . PHP_EOL;
        }
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        //
    }
}
