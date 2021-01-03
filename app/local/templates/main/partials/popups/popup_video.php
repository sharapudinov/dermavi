<?php
/**
 * Шаблон для попапа видео истории бриллианта
 * @var \App\Models\About\DiamondStoryVideo $video - Видео
 */
if (!$video) {
    return;
}
?>
<div id="popup-video" class="popup popup--fullscreen popup-video" data-fullscreen="true"
     data-wrap-class="fancybox-wrap--fullscreen" data-animation="zoom" style="display: none;">
    <div class="popup__body popup-video__body">
        <video id="diamond-story-video" width="100%" height="100%" controls playinsline>
            <source src="<?= $video->getVideo() ?>" type="video/mp4">
        </video>
        <button class="popup__close" data-popup="close" type="button" title="Закрыть">
            <svg class="icon icon--cross">
                <use xlink:href="<?=SPRITE_SVG?>#icon-cross"></use>
            </svg>
        </button>
    </div>
</div>
