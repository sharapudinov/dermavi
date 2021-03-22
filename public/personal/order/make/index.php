<?
define("HIDE_SIDEBAR", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Заказы");
?><?$APPLICATION->IncludeComponent("dermavi:checkout", "dermavi",[]);?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
