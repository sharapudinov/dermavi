<?
define("HIDE_SIDEBAR", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Корзина");
?><?$APPLICATION->IncludeComponent("dermavi:cart", "dermavi",[]);?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
