<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Задайте вопрос");
?>

<div class="contact-wrap ">
    <div class="contact-wrap__left">
        <div class="contact">
            <div class="contact__title">Контакты</div>
            <div class="contact__links">
                <div class="contact__link">
                    <div class="contact__link-label">Позвоните нам!</div>
                    <a href="#" class="contact__link-value"><i class="icon icon-phone"></i>+7 (932) 232 55 55</a>
                </div>
                <div class="contact__link">
                    <div class="contact__link-label">Напишите нам!</div>
                    <a href="#" class="contact__link-value"><i class="icon icon-mail"></i>info@dermavi.com</a>
                </div>
                <div class="contact__link">
                    <div class="contact__link-label">Мы в instagram</div>
                    <a href="#" class="contact__link-value"><i class="icon icon-instagram"></i>dermavi_cosm</a>
                </div>
            </div>
            <div class="contact__text">
                Для приобретения Подарочных карт корпоративными клиентами <br>
                Тел.: +7 495 771-6007 (доб.6255) <br>
                E-mail: corporate@dermavi.ru <br>
                <br>
                Продавец: АО «DERMAVI» ИНН 7707061530, КПП 774901001, ОГРН 1027700292938. <br>
                Адрес местонахождения: Россия, 115184, г. Москва, ул. Пятницкая, д. 74, стр. 1, 3 этаж, комната 13
            </div>
        </div>
    </div>
    <?
    $APPLICATION->IncludeComponent(
        "bitrix:main.feedback",
        "dermavi_contacts_feedback",
        [
            "EMAIL_TO"         => "sale@nyuta.bx",
            "EVENT_MESSAGE_ID" => [],
            "OK_TEXT"          => "Спасибо, ваше сообщение принято.",
            "REQUIRED_FIELDS"  => ["NAME", "EMAIL"],
            "USE_CAPTCHA"      => "Y"
        ]
    ); ?>
</div>



<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php") ?>
