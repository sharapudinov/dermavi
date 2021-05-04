<div class="footer__menu-list js-drop">
    <?
    foreach ($arResult as $menuitem): ?>
        <a href="<?=$menuitem['LINK']?>" class="footer__menu-item footer-link"><?=$menuitem['TEXT']?></a>

    <?
    endforeach; ?>
</div>
