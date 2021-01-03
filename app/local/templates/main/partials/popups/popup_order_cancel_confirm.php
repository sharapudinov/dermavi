<?php
/** @var string $popupId */
/** @var App\Core\Sale\View\OrderViewModel $order */
if (!is_null($order)) {
    $APPLICATION->IncludeComponent('popup:order.cancel.confirm', '', [
        'popupId' => $popupId,
        'order' => $order
    ]);
}
