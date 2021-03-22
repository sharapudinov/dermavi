<?
use Bitrix\Main\UI\Extension;
define('NEED_AUTH',true);
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

$APPLICATION->IncludeComponent('dermavi:order.history','dermavi')
?>
<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
