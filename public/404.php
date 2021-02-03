<?
include_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/urlrewrite.php');

CHTTP::SetStatus("404 Not Found");
@define("ERROR_404","Y");
define("HIDE_SIDEBAR", true);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

$APPLICATION->SetTitle("Страница не найдена");?>

    <div class="not-found mb200">
        <div class="not-found__img-wrap"><img src="img/404.jpg" alt="" class="not-found__img"></div>
        <div class="not-found__title">страница не найдена</div>
        <div class="not-found__text">Возможно, запрашиваемая Вами страница была перенесена или удалена. Также возможно, Вы допустили опечатку при вводе адреса</div>
        <div class="not-found__btn"><a href="/" class="btn btn-black btn-small">Перейти на главную</a></div>
    </div>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
