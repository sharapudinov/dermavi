<h1 class="office__title head-1 padding-80">Личный кабинет</h1>

<div class="office-wrap" id="personal">
    <div class="office-wrap__left">
        <div class="office">
            <bx-office-tabs v-on:personal="personal"
                            v-on:bonus="bonus"
                            v-on:favorite="favorite"заказы
                            v-on:orders="orders"></bx-office-tabs>
            <bx-personal-tab v-show="state=='personal'"
                             name="Шамиль"
                             surname="Шарапудинов"
                             patronymic="Идрисович"></bx-personal-tab>
            <bx-bonus-tab v-show="state=='bonus'"></bx-bonus-tab>
            <bx-favorite-tab v-show="state=='favorite'"></bx-favorite-tab>
            <bx-orders-tab v-show="state=='orders'"></bx-orders-tab>
        </div>
    </div>
    <div class="office-wrap__right">
        <div class="office-notice__wrap">
            <div class="office-notice">
                <div class="office-notice__title">Заказ №000234</div>
                <div class="office-notice__text">Ваш заказ №000234 сейчас находится в обработке. Наш менеджер свяжется с
                    вами для уточнения деталей.
                </div>
                <div class="office-notice__btn"><a href="#" class="btn btn-small btn_center btn_full-width btn-gray">Просмотреть
                        заказ</a></div>
            </div>
            <div class="office-notice">
                <div class="office-notice__title">Успейте использовать ваши бонусы!</div>
                <div class="office-notice__text">На вашем бонусном счёте есть неиспользованные бонусы, которые сгорают
                    по истечение этого месяца
                </div>
                <div class="office-notice__btn"><a href="#" class="btn btn-small btn_center btn_full-width btn-gray">Смотреть
                        бонусный счёт</a></div>
            </div>
        </div>
    </div>
</div>
