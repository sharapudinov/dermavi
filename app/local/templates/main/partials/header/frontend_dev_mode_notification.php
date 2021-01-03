<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? if (frontend()->isInDevMode()): ?>
    <form action="<?= $APPLICATION->GetCurPage() ?>" id="frontend_build_form" style="border: 2px solid red;">
        <input type="hidden" name="frontend_build" value="prod" >
        <div class="container">
            <p style="margin: 15px 0">
                Используется dev сборка фронтэнда.
                Чтобы вернуться на production сборку нажмите <a href="#" onclick='document.forms["frontend_build_form"].submit();'>сюда</a>
            </p>
        </div>
    </form>
<? endif ?>
