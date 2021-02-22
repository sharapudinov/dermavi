<?


use Bitrix\Main\UI\Extension;

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

Extension::load(["dermavi.sign-in"]);
mail('sharapudinov@mail.ru','test','test')
?>
<div id="placeholder1"></div>
<script>
    BX.Vue.component('bx-test',
            {
                prop: {},
    template: '<div>test</div>'
            }
    );
    BX.Vue.create({
        el: document.getElementById('placeholder1'),
        template: `<div >
            <bx-sign-in/>



        </div>`,
    });


</script>

<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
