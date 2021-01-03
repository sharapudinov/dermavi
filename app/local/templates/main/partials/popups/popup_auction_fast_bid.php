<?php
    /** Шаблон попапа быстрой множественной ставки на лоты аукциона */
?>

<div id="popup-manage-columns--fastbid" class="manage-columns popup popup--aside" data-catalog="#catalog-popup"
     data-aside="true" data-animation="push-right" data-time="400" data-wrap-class="fancybox-wrap--aside-right"
     style="display: none;">
    <div class="popup__body">
        <h2 class="popup__hl">Manage columns</h2>
        <!-- Этот попап был сделан для теста карточек.
        Полноценно верстается он в задаче каталога. -->
        <p class="label label--sm mb-s">Выберите от 4 до 10 вариантов:</p>
        <div class="manage-columns__checkbox checkbox" data-min="4" data-max="10">

            <input class="manage-columns__checkbox-input checkbox__input" type="checkbox" name="manage-columns-popup"
                   id="manage-columns-popup--lot" value="lot" data-option="lot" checked>
            <label class="manage-columns__checkbox-label checkbox__label" for="manage-columns-popup-lot">Lot №</label>

            <input class="manage-columns__checkbox-input checkbox__input" type="checkbox" name="manage-columns-popup"
                   id="manage-columns-popup--id" value="id" data-option="id" checked>
            <label class="manage-columns__checkbox-label checkbox__label" for="manage-columns-popup-id">Id</label>

            <input class="manage-columns__checkbox-input checkbox__input" type="checkbox" name="manage-columns-popup"
                   id="manage-columns-popup--gia" value="gia" data-option="gia" checked>
            <label class="manage-columns__checkbox-label checkbox__label" for="manage-columns-popup-gia">Gia</label>

            <input class="manage-columns__checkbox-input checkbox__input" type="checkbox" name="manage-columns-popup"
                   id="manage-columns-popup--shape" value="shape" data-option="shape" checked>
            <label class="manage-columns__checkbox-label checkbox__label" for="manage-columns-popup-shape">Shape</label>

            <input class="manage-columns__checkbox-input checkbox__input" type="checkbox" name="manage-columns-popup"
                   id="manage-columns-popup--carat" value="carat" data-option="carat" checked>
            <label class="manage-columns__checkbox-label checkbox__label" for="manage-columns-popup-carat">Carat</label>

            <input class="manage-columns__checkbox-input checkbox__input" type="checkbox" name="manage-columns-popup"
                   id="manage-columns-popup--color" value="color" data-option="color" checked>
            <label class="manage-columns__checkbox-label checkbox__label" for="manage-columns-popup-color">Color</label>

            <input class="manage-columns__checkbox-input checkbox__input" type="checkbox" name="manage-columns-popup"
                   id="manage-columns-popup--clarity" value="clarity" data-option="clarity" checked>
            <label class="manage-columns__checkbox-label checkbox__label"
                   for="manage-columns-popup-clarity">Clarity</label>

            <input class="manage-columns__checkbox-input checkbox__input" type="checkbox" name="manage-columns-popup"
                   id="manage-columns-popup--cut" value="cut" data-option="cut">
            <label class="manage-columns__checkbox-label checkbox__label" for="manage-columns-popup-cut">Cut</label>

            <input class="manage-columns__checkbox-input checkbox__input" type="checkbox" name="manage-columns-popup"
                   id="manage-columns-popup--age" value="age" data-option="age">
            <label class="manage-columns__checkbox-label checkbox__label" for="manage-columns-popup-age">Age</label>

            <input class="manage-columns__checkbox-input checkbox__input" type="checkbox" name="manage-columns-popup"
                   id="manage-columns-popup--origin" value="origin" data-option="origin">
            <label class="manage-columns__checkbox-label checkbox__label"
                   for="manage-columns-popup-origin">Origin</label>

            <input class="manage-columns__checkbox-input checkbox__input" type="checkbox" name="manage-columns-popup"
                   id="manage-columns-popup--year" value="year" data-option="year">
            <label class="manage-columns__checkbox-label checkbox__label" for="manage-columns-popup-year">Year</label>


            <input class="manage-columns__checkbox-input checkbox__input" type="checkbox" name="manage-columns-popup"
                   id="manage-columns-popup--fluorescence" value="fluorescence" data-option="fluorescence" checked>
            <label class="manage-columns__checkbox-label checkbox__label"
                   for="manage-columns-popup-fluorescence">Fluorescence</label>

            <input class="manage-columns__checkbox-input checkbox__input" type="checkbox" name="manage-columns-popup"
                   id="manage-columns-popup--collection" value="collection" data-option="collection">
            <label class="manage-columns__checkbox-label checkbox__label"
                   for="manage-columns-popup-collection">Collection</label>

            <input class="manage-columns__checkbox-input checkbox__input" type="checkbox" name="manage-columns-popup"
                   id="manage-columns-popup--polish" value="polish" data-option="polish" checked>
            <label class="manage-columns__checkbox-label checkbox__label"
                   for="manage-columns-popup-polish">Polish</label>

            <input class="manage-columns__checkbox-input checkbox__input" type="checkbox" name="manage-columns-popup"
                   id="manage-columns-popup--symmetry" value="symmetry" data-option="symmetry">
            <label class="manage-columns__checkbox-label checkbox__label"
                   for="manage-columns-popup-symmetry">Symmetry</label>

            <input class="manage-columns__checkbox-input checkbox__input" type="checkbox" name="manage-columns-popup"
                   id="manage-columns-popup--price" value="price" data-option="price" checked>
            <label class="manage-columns__checkbox-label checkbox__label" for="manage-columns-popup-price">Price</label>

            <input class="manage-columns__checkbox-input checkbox__input" type="checkbox" name="manage-columns-popup"
                   id="manage-columns-popup--table" value="table" data-option="table">
            <label class="manage-columns__checkbox-label checkbox__label" for="manage-columns-popup-table">Table</label>

            <input class="manage-columns__checkbox-input checkbox__input" type="checkbox" name="manage-columns-popup"
                   id="manage-columns-popup--depth" value="depth" data-option="depth">
            <label class="manage-columns__checkbox-label checkbox__label" for="manage-columns-popup-depth">Depth</label>

            <input class="manage-columns__checkbox-input checkbox__input" type="checkbox" name="manage-columns-popup"
                   id="manage-columns-popup--culet" value="culet" data-option="culet">
            <label class="manage-columns__checkbox-label checkbox__label" for="manage-columns-popup-culet">Culet</label>

            <!-- <input class="manage-columns__checkbox-input checkbox__input" type="checkbox" name="manage-columns-popup" id="manage-columns-popup--lab"
                value="lab" data-option="lab">
            <label class="manage-columns__checkbox-label checkbox__label"  for="manage-columns-popup-lab">Lab</label> -->

            <input class="manage-columns__checkbox-input checkbox__input" type="checkbox" name="manage-columns-popup"
                   id="manage-columns-popup--name" value="name" data-option="name">
            <label class="manage-columns__checkbox-label checkbox__label" for="manage-columns-popup-name">Name</label>


        </div>

        <button class="popup__close" data-popup="close" type="button" title="Закрыть">
            <svg class="icon icon--close-lg">
                <use xlink:href="<?=SPRITE_SVG?>#icon-close_lg"></use>
            </svg>
        </button>
    </div>
</div>

<div id="popup-fast-bid" class="popup popup--fullscreen popup-fast-bid" data-animation="zoom" data-fullscreen="true"
    data-wrap-class="fancybox-wrap--fullscreen" style="display: none;">
</div>
