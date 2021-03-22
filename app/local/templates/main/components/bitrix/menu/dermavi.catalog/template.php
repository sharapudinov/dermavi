
<div class="header-nav">
    <?
    foreach ($arResult["RECURCIVE"] as $menuitem): ?>
        <div class="header-nav__item-wrap">
            <a href="<?= $menuitem['LINK'] ?>" class="header-nav__item"><?= $menuitem['TEXT'] ?></a>
            <?
            if (is_array($menuitem['CHILDREN'])): ?>
                <div class="header-drop">
                    <div class="header-drop__content">
                        <div class="header-drop__list-wrap">
                            <div class="header-drop__list-title">Категории</div>
                            <?
                            foreach ($menuitem['CHILDREN'] as $chield) : ?>
                                <div class="header-drop__list-cats">
                                    <a href="<?=$chield['LINK']?>" class="header-drop__item"><?=$chield['TEXT']?></a>
                                </div>
                            <?
                            endforeach; ?>
                        </div>
                        <div class="header-drop__list-wrap">
                            <div class="header-drop__list-title">Бренды</div>
                            <div class="header-drop__list">
                                <a href="#" class="header-drop__item">Babor</a>
                                <a href="#" class="header-drop__item">Clarins</a>
                                <a href="#" class="header-drop__item">Davines</a>
                                <a href="#" class="header-drop__item">Estée Lauder</a>
                                <a href="#" class="header-drop__item">Foreo</a>
                                <a href="#" class="header-drop__item">Kiehl's</a>
                                <a href="#" class="header-drop__item">La Prairie</a>
                                <a href="#" class="header-drop__item">Sensai</a>
                            </div>
                        </div>
                    </div>
                    <div class="header-drop__best">
                        <div class="header-drop__best-title">бестселлеры</div>
                        <div class="header-best__list">
                            <a href="#" class="header-best__item">
                                <div class="header-best__item-img-wrap"><img src="/img/best.jpg" alt=""
                                                                             class="header-best__item-img">
                                </div>
                                <div class="header-best__item-wrap">
                                    <div class="header-best__item-top">
                                        <div class="header-best__item-title">La Mer</div>
                                        <div class="header-best__item-stickers">
                                            <div class="header-best__item-sticker">
                                                <div class="sticker-hit">hit</div>
                                            </div>
                                            <div class="header-best__item-sticker">
                                                <div class="sticker-new">new</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="header-best__item-text">Лифтинг сыворотка Essense of bees</div>
                                    <div class="header-best__item-price">1 890 ₽</div>
                                </div>
                            </a>
                            <a href="#" class="header-best__item">
                                <div class="header-best__item-img-wrap"><img src="/img/best3.jpg" alt=""
                                                                             class="header-best__item-img">
                                </div>
                                <div class="header-best__item-wrap">
                                    <div class="header-best__item-top">
                                        <div class="header-best__item-title">sisley</div>
                                        <div class="header-best__item-stickers">
                                        </div>
                                    </div>
                                    <div class="header-best__item-text">Крем для кожи вокруг глаз</div>
                                    <div class="header-best__item-price">44 223 ₽</div>
                                </div>
                            </a>
                            <a href="#" class="header-best__item">
                                <div class="header-best__item-img-wrap"><img src="/img/best3.jpg" alt=""
                                                                             class="header-best__item-img">
                                </div>
                                <div class="header-best__item-wrap">
                                    <div class="header-best__item-top">
                                        <div class="header-best__item-title">La prairie</div>
                                        <div class="header-best__item-stickers">
                                            <div class="header-best__item-sticker">
                                                <div class="sticker-hit">hit</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="header-best__item-text">Лифтинг сыворотка Essense of bees</div>
                                    <div class="header-best__item-price">1 890 ₽</div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

            <?endif; ?>
        </div>
    <?
    endforeach; ?>
</div>
