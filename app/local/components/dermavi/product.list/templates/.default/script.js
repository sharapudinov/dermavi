/**
 * Проверяем все ли позиции выбраны, и, если да, то переинициализируем таблицу
 */
$(window).load(function () {
    /** Чекбокс, отмечающий все позиции на странице */
    const selectAllPositionsCheckbox = $('.js-select-all-products');
    if (selectAllPositionsCheckbox.attr('data-checked')) {
        reInitCatalogForm();
        selectAllPositionsCheckbox.click();
    }
});
