<?
define("NEED_AUTH", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

$APPLICATION->SetTitle("Авторизация");
?>
<p>Вы зарегистрированы и успешно авторизовались.</p>

<p><a href="<?=SITE_DIR?>">Вернуться на главную страницу</a></p>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
