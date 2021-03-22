Vue = BX.Vue;

Vue.component('bx-office-tabs',
        {
            props: {
                tabs: {
                    default: function() {
                        return [
                            {
                                title: 'Мои данные',
                                mobile_title: 'Данные',
                                event: 'personal',
                            },
                            {
                                title: 'Бонусный счёт',
                                mobile_title: 'Бонусы',
                                event: 'bonus',
                            },
                            {
                                title: 'Избранные товары',
                                mobile_title: 'Избранное',
                                event: 'favorite',
                            },
                            {
                                title: 'Мои заказы',
                                mobile_title: 'Заказы',
                                event: 'orders',
                            },
                        ];
                    },
                },
            },
            data() {
                return {
                    value: '',
                };
            },

            model: {},

            methods: {
                click(event) {
                    this.$emit(event.currentTarget.dataset.event);
                },
            },
            template: `<div class="office-tabs">
                <a  v-for="tab in tabs"
                    v-on:click="click"
                    :data-event="tab.event"
                   class="office-tab"><span>{{tab.title}}</span>
                    <div class="mobile"><i class="icon icon-profile"></i>{{tab.mobile_title}}</div>
                </a>
            </div>`,
        });
Vue.component('bx-sorter',
        {
            props: {},
            data() {
                return {
                    value: '',
                };
            },

            model: {},

            methods: {
                click(event) {
                    this.$emit(event.currentTarget.dataset.event);
                },
            },
            template: `<div class="cart-list__office-sort">
                                <div class="select-sort-wrap">
                                    <div class="select-sort__label">
                                        <i class="icon icon-list"></i>
                                        Сортировать по
                                    </div>
                                    <div class="select-sort__value-wrap js-open-wrap">
                                        <div class="select-sort__value js-select js-open">рекомендации<span
                                                class="icon icon-arrow_down"></span></div>
                                        <div class="select-sort-drop js-drop">
                                            <div class="drop-city__content">
                                                <div data-value="1" data-name="рекомендации"
                                                     class="drop-city__link js-select-item">рекомендации
                                                </div>
                                                <div data-value="2" data-name="сначала последние"
                                                     class="drop-city__link js-select-item selected">сначала последние
                                                </div>
                                                <div data-value="3" data-name="популярные"
                                                     class="drop-city__link js-select-item">популярные
                                                </div>
                                                <input type="hidden" name="drop-city__link-input" value="1">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>`,
        });
Vue.component('bx-pagination',
        {
            props: {
                pages: {
                    default: function() {
                        return [
                            {
                                text: '01',
                                link: '/',
                                active: 'active',
                            },
                            {

                                text: '02',
                                link: '/',
                            },
                            {

                                text: '03',
                                link: '/',
                            },
                            {

                                text: '04',
                                link: '/',
                            },
                        ];
                    },
                },
            },
            data() {
                return {
                    value: '',
                };
            },

            model: {},

            methods: {
                click(event) {
                    this.$emit(event.currentTarget.dataset.event);
                },
            },
            template: `<div class="pagination">
                        <div class="pagination-list">
                            <a v-for="page in pages"
                             :href="page.link" 
                             :class="'pagination-item '+page.active">{{page.text}}</a>
                        </div>
                    </div>`,
        });
Vue.component('bx-personal-tab',
        {
            props:
                    {
                        name: {default: ''},
                        surname: {default: ''},
                        patronymic: {default: ''},
                        email: {default: ''},
                        phone: {default: ''},
                        region: {default: ''},
                        city: {default: ''},
                        street: {default: ''},
                        address: {default: ''},
                    },
            data() {
                return {
                    value: '',
                };
            },

            model: {},
            methods: {
                submit: function(e) {
                    if (!this.validate(e))
                        return;
                    var data = new FormData(e.target);
                    $.ajax({
                        url: e.target.action,
                        method: 'POST',
                        data: data,
                        processData: false,  // Сообщить jQuery не передавать эти данные
                        contentType: false,  // Сообщить jQuery не передавать тип контента
                        success: function(response) {
                            console.log(response);
                        },
                        error: function(response) {
                            console.log(response.responseText);
                        },
                    });
                    e.stopPropagation();
                },
                validate: function(e) {
                    this.errors = [];

                    if (!this.errors.length) {
                        return true;
                    }
                    e.preventDefault();
                    e.stopPropagation();
                },
            },
            template: `<form id="personal-data"
                             v-on:submit.prevent="submit"
                             action="/api/internal/user/profile/update-profile/">
                <div class="office-user-info offer-border mb-80">
                    <div class="office-user-info__top">
                        <div class="office-user-info__title">Личная информация</div>
                        <div class="office-user-info__edit"><i class="icon icon-edit"></i>Редактировать</div>
                    </div>
                    <div class="ordering__fio-group">
                        <div class="ordering__fio-form">
                            <label for="name" class="label label-order">Имя</label>
                            <input v-model="name"
                                   id="name" 
                                   name="name" 
                                   type="text"
                                   class="input input-border input-normal input-bgr_grey input-full_width input-small_padding"
                                   placeholder="Имя">
                        </div>
                        <div class="ordering__fio-form">
                            <label for="surname" 
                                   class="label label-order">Фамилия</label>
                            <input v-model="surname"
                                   id="surname" 
                                   name="surname"                                    
                                   type="text"
                                   class="input input-border input-normal input-bgr_grey input-full_width input-small_padding"
                                   placeholder="Фамилия" >
                        </div>
                        <div class="ordering__fio-form">
                            <label for="patronymic" class="label label-order">Отчество</label>
                            <input v-model="patronymic"
                                   id="patronymic" 
                                   name="patronymic"
                                   type="text"
                                   class="input input-border input-normal input-bgr_grey input-full_width input-small_padding"
                                   placeholder="Отчество">
                        </div>
                    </div>
                    <div class="ordering__contact-group">
                        <div class="ordering__contact-form">
                            <label for="phone" class="label label-order">Номер телефона</label>
                            <input v-model="phone"
                                   id="phone" 
                                   name="phone"
                                   type="text"
                                   class="input input-border input-normal input-bgr_grey input-small_padding"
                                   placeholder="+7 (321) 325 66 88" value="+7 (321) 325 66 88">
                        </div>
                        <div class="ordering__contact-form">
                            <label for="email" class="label label-order">Адрес эл. почты</label>
                            <input v-model="email"
                                   id="email" 
                                   name="email"
                                   type="text"
                                   class="input input-border input-normal input-bgr_grey input-small_padding"
                                   placeholder="email">
                        </div>
                    </div>
                </div>
                <div class="office-address offer-border mb-80">
                    <div class="office-user-info__top">
                        <div class="office-user-info__title">Адрес</div>
                        <div class="office-user-info__edit"><i class="icon icon-edit"></i>Редактировать</div>
                    </div>
                    <div class="ordering__delivery-group">
                        <div class="ordering__delivery-form">
                            <label for="region" class="label label-order">Регион</label>
                            <input v-model="region"
                                   id="region"
                                   type="text"
                                   class="input input-border input-normal input-bgr_grey input-full_width input-small_padding"
                                   placeholder="">
                        </div>
                        <div class="ordering__delivery-form">
                            <label for="city" 
                                   class="label label-order">Город / Населенный пункт</label>
                            <input v-model="city"
                                   id="city" 
                                   type="text"
                                   class="input input-border input-normal input-bgr_grey input-full_width input-small_padding"
                                   placeholder="">
                        </div>
                    </div>
                    <div class="ordering__delivery-group">
                        <div class="ordering__delivery-form street">
                            <label for="street" class="label label-order">Улица</label>
                            <input v-model="street"
                                   id="street" 
                                   type="text"
                                   class="input input-border input-normal input-bgr_grey input-full_width input-small_padding"
                                   placeholder="">
                        </div>
                        <div class="ordering__delivery-group__mini-wrap">
                            <div class="ordering__delivery-form mini">
                                <label for="house" class="label label-order">Дом</label>
                                <input id="house" type="text"
                                       class="input input-border input-normal input-bgr_grey input-small_padding input-full_width"
                                       placeholder="">
                            </div>
                            <div class="ordering__delivery-form mini">
                                <label for="corpus" class="label label-order">Корпус</label>
                                <input id="corpus" type="text"
                                       class="input input-border input-normal input-bgr_grey input-small_padding input-full_width"
                                       placeholder="">
                            </div>
                            <div class="ordering__delivery-form mini">
                                <label for="flat" class="label label-order">Квартира</label>
                                <input id="flat" type="text"
                                       class="input input-border input-normal input-bgr_grey input-small_padding input-full_width"
                                       placeholder="">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="office-password">
                    <div class="office-password__group">
                        <div class="office-password-form form wrong focus">
                            <label for="current-password" class="label label-order">Текущий пароль</label>
                            <input id="current-password" type="password"
                                   class="input input-border input-normal input-bgr_grey input-full_width input-small_padding"
                                   placeholder="">
                            <button class="form__btn form__btn-password" type="submit">
                                <div class="form__icon"><i class="icon icon-eye_off"></i></div>
                            </button>
                            <div class="help">Неверный пароль! Повторите попытку</div>
                        </div>
                    </div>
                    <div class="office-password__group">
                        <div class="office-password-form form">
                            <label for="new-password" class="label label-order">Город / Населенный пункт</label>
                            <input id="new-password" type="text"
                                   class="input input-border input-normal input-bgr_grey input-full_width input-small_padding"
                                   placeholder="">
                            <button class="form__btn form__btn-password" type="submit">
                                <div class="form__icon"><i class="icon icon-eye_off"></i></div>
                            </button>
                        </div>
                        <div class="office-password-form form ">
                            <label for="new-password2" class="label label-order">Регион</label>
                            <input id="new-password2" type="text"
                                   class="input input-border input-normal input-bgr_grey input-full_width input-small_padding"
                                   placeholder="">
                            <button class="form__btn form__btn-password" type="submit">
                                <div class="form__icon"><i class="icon icon-eye_off"></i></div>
                            </button>
                        </div>
                    </div>
                    <div class="office-password__bottom">
                        <div class="office-password__btn">
                            <input type="submit" class="btn btn-black btn-normal btn-small-padding">Сменить пароль
                            </input>
                        </div>
                        <div class="help-password"><i class="icon icon-check_circle"></i>Пароль успешно изменен</div>
                    </div>
                </div>
            </form>`,
        });
Vue.component('bx-bonus-tab',
        {
            props:
                    {},
            data() {
                return {
                    value: '',
                };
            },

            model: {},
            template: `<div class="bonus">
                        <div class="bonus-count">
                            <div class="bonus-count__title">Бонусный счёт</div>
                            <div class="bonus-count__value">2 992 ББ</div>
                        </div>
                        <div class="bonus__card-title">Бонусная карта №00003921</div>
                        <div class="bonus__text mb24">До аннулирования бонусного счёта осталось 304 дн.</div>
                        <div class="bonus__text">* Всякая психическая функция в культурном развитии ребенка появляется на сцену дважды, в двух планах,— сперва социальном, потом — психологическом, следовательно предмет деятельности отражает напряженный контраст. Бессознательное, иcходя из того, что мгновенно. Можно предположить, что восприятие дает кризис.</div>
                    </div>`,
        });
Vue.component('bx-favorite-tab',
        {
            props:
                    {
                        empty: {default: false},
                    },
            data() {
                return {
                    value: '',
                };
            },

            model: {},
            template: `<div>
                        <div class="cart-list__office fav">
                            <div class="cart-list__office-title">Избранные товары</div>
                            <bx-sorter></bx-sorter>
                            
                        </div>
                        <div class="cart-list-wrap">
                            <div class="cart-list__top">
                                <div class="cart-list__name">Наименование товара</div>
                                <div class="cart-list__top-wrap">
                                    <div class="cart-list__price">Цена</div>
                                    <div class="cart-list__incart">В корзину</div>
                                    <div class="cart-list__delete">Удалить</div>
                                </div>
                            </div>
                            <div class="cart-list">

                                <div class="cart fav">
                                    <div class="cart__img-wrap"><img src="img/image_cart.jpg" alt="" class="cart__img">
                                    </div>
                                    <div class="cart__wrapper">
                                        <div class="cart__content">
                                            <a href="#" class="cart__title">Facial Lotion</a>
                                            <div class="cart__sticker">
                                                <div class="sticker-hit">hit</div>
                                            </div>
                                            <div class="cart__sticker">
                                                <div class="sticker-new">new</div>
                                            </div>
                                        </div>
                                        <div class="cart-wrap">
                                            <div class="cart__price-wrap mla mb-mobile">
                                                <div class="cart__price">3500 ₽</div>
                                                <div class="cart__price-old">1620 ₽</div>
                                            </div>
                                            <button type="button" class="cart__add btn"><i class="icon icon-cart"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <a href="#" class="cart__fav-btn"><i class="icon icon-heart1"></i></a>
                                    <a href="#" class="cart__delete fav"><i class="icon icon-close"></i></a>

                                </div>
                                <div class="cart fav">
                                    <div class="cart__img-wrap"><img src="img/image_cart.jpg" alt="" class="cart__img">
                                    </div>
                                    <div class="cart__wrapper">
                                        <div class="cart__content">
                                            <a href="#" class="cart__title">Facial Lotion Premium</a>
                                            <div class="cart__sticker">
                                                <div class="sticker-hit">hit</div>
                                            </div>
                                            <div class="cart__sticker">
                                                <div class="sticker-new">new</div>
                                            </div>
                                        </div>
                                        <div class="cart-wrap">
                                            <div class="cart__price-wrap mla mb-mobile">
                                                <div class="cart__price">1 890 ₽</div>
                                                <div class="cart__price-old"></div>
                                            </div>
                                            <button type="button" class="cart__add btn"><i class="icon icon-cart"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <a href="#" class="cart__fav-btn"><i class="icon icon-heart1"></i></a>
                                    <a href="#" class="cart__delete fav"><i class="icon icon-close"></i></a>

                                </div>
                                <div class="cart fav">
                                    <div class="cart__img-wrap"><img src="img/image_cart.jpg" alt="" class="cart__img">
                                    </div>
                                    <div class="cart__wrapper">
                                        <div class="cart__content">
                                            <a href="#" class="cart__title">Facial Lotion</a>
                                            <div class="cart__sticker">
                                                <div class="sticker-hit">hit</div>
                                            </div>
                                            <div class="cart__sticker">
                                                <div class="sticker-new">new</div>
                                            </div>
                                        </div>
                                        <div class="cart-wrap">
                                            <div class="cart__price-wrap mla mb-mobile">
                                                <div class="cart__price">61 010 ₽</div>
                                                <div class="cart__price-old"></div>
                                            </div>
                                            <button type="button" class="cart__add btn">+ 1</button>
                                        </div>
                                    </div>

                                    <a href="#" class="cart__fav-btn"><i class="icon icon-heart"></i></a>
                                    <a href="#" class="cart__delete fav"><i class="icon icon-close"></i></a>

                                </div>
                                <div class="cart fav">
                                    <div class="cart__img-wrap"><img src="img/image_cart.jpg" alt="" class="cart__img">
                                    </div>
                                    <div class="cart__wrapper">
                                        <div class="cart__content">
                                            <a href="#" class="cart__title">Facial Lotion</a>
                                            <div class="cart__sticker">
                                                <div class="sticker-hit">hit</div>
                                            </div>
                                            <div class="cart__sticker">
                                                <div class="sticker-new">new</div>
                                            </div>
                                        </div>
                                        <div class="cart-wrap">
                                            <div class="cart__price-wrap mla mb-mobile">
                                                <div class="cart__price">3500 ₽</div>
                                                <div class="cart__price-old"></div>
                                            </div>
                                            <button type="button" class="cart__add btn"><i class="icon icon-cart"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <a href="#" class="cart__fav-btn"><i class="icon icon-heart1"></i></a>
                                    <a href="#" class="cart__delete fav"><i class="icon icon-close"></i></a>

                                </div>
                            </div>
                        </div>

                        <bx-pagination></bx-pagination>
                        <div>
                            <div class="cart-list__office fav">
                                <div class="cart-list__office-title">Избранные товары</div>
                                <div class="cart-list__office-sort">
                                    <div class="select-sort-wrap">
                                        <div class="select-sort__label">
                                            <i class="icon icon-list"></i>
                                            Сортировать по
                                        </div>
                                        <div class="select-sort__value-wrap js-open-wrap">
                                            <div class="select-sort__value js-select js-open">рекомендации<span
                                                    class="icon icon-arrow_down"></span></div>
                                            <div class="select-sort-drop js-drop">
                                                <div class="drop-city__content">
                                                    <div data-value="1" data-name="рекомендации"
                                                         class="drop-city__link js-select-item">рекомендации
                                                    </div>
                                                    <div data-value="2" data-name="сначала последние"
                                                         class="drop-city__link js-select-item selected">сначала
                                                        последние
                                                    </div>
                                                    <div data-value="3" data-name="популярные"
                                                         class="drop-city__link js-select-item">популярные
                                                    </div>
                                                    <input type="hidden" name="drop-city__link-input" value="1">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="cart-list-wrap">
                                <div class="cart-list__top">
                                    <div class="cart-list__name">Наименование товара</div>
                                    <div class="cart-list__top-wrap">
                                        <div class="cart-list__price">Цена</div>
                                        <div class="cart-list__incart">В корзину</div>
                                        <div class="cart-list__delete">Удалить</div>
                                    </div>
                                </div>
                                <div class="cart-list">

                                    <div class="cart fav">
                                        <div class="cart__img-wrap"><img src="img/image_cart.jpg" alt=""
                                                                         class="cart__img"></div>
                                        <div class="cart__wrapper">
                                            <div class="cart__content">
                                                <a href="#" class="cart__title">Facial Lotion</a>
                                                <div class="cart__sticker">
                                                    <div class="sticker-hit">hit</div>
                                                </div>
                                                <div class="cart__sticker">
                                                    <div class="sticker-new">new</div>
                                                </div>
                                            </div>
                                            <div class="cart-wrap">
                                                <div class="cart__price-wrap mla mb-mobile">
                                                    <div class="cart__price">3500 ₽</div>
                                                    <div class="cart__price-old">1620 ₽</div>
                                                </div>
                                                <button type="button" class="cart__add btn"><i
                                                        class="icon icon-cart"></i></button>
                                            </div>
                                        </div>

                                        <a href="#" class="cart__fav-btn"><i class="icon icon-heart1"></i></a>
                                        <a href="#" class="cart__delete fav"><i class="icon icon-close"></i></a>

                                    </div>
                                    <div class="cart fav">
                                        <div class="cart__img-wrap"><img src="img/image_cart.jpg" alt=""
                                                                         class="cart__img"></div>
                                        <div class="cart__wrapper">
                                            <div class="cart__content">
                                                <a href="#" class="cart__title">Facial Lotion Premium</a>
                                                <div class="cart__sticker">
                                                    <div class="sticker-hit">hit</div>
                                                </div>
                                                <div class="cart__sticker">
                                                    <div class="sticker-new">new</div>
                                                </div>
                                            </div>
                                            <div class="cart-wrap">
                                                <div class="cart__price-wrap mla mb-mobile">
                                                    <div class="cart__price">1 890 ₽</div>
                                                    <div class="cart__price-old"></div>
                                                </div>
                                                <button type="button" class="cart__add btn"><i
                                                        class="icon icon-cart"></i></button>
                                            </div>
                                        </div>

                                        <a href="#" class="cart__fav-btn"><i class="icon icon-heart1"></i></a>
                                        <a href="#" class="cart__delete fav"><i class="icon icon-close"></i></a>

                                    </div>
                                    <div class="cart fav">
                                        <div class="cart__img-wrap"><img src="img/image_cart.jpg" alt=""
                                                                         class="cart__img"></div>
                                        <div class="cart__wrapper">
                                            <div class="cart__content">
                                                <a href="#" class="cart__title">Facial Lotion</a>
                                                <div class="cart__sticker">
                                                    <div class="sticker-hit">hit</div>
                                                </div>
                                                <div class="cart__sticker">
                                                    <div class="sticker-new">new</div>
                                                </div>
                                            </div>
                                            <div class="cart-wrap">
                                                <div class="cart__price-wrap mla mb-mobile">
                                                    <div class="cart__price">61 010 ₽</div>
                                                    <div class="cart__price-old"></div>
                                                </div>
                                                <button type="button" class="cart__add btn">+ 1</button>
                                            </div>
                                        </div>

                                        <a href="#" class="cart__fav-btn"><i class="icon icon-heart"></i></a>
                                        <a href="#" class="cart__delete fav"><i class="icon icon-close"></i></a>

                                    </div>
                                    <div class="cart fav">
                                        <div class="cart__img-wrap"><img src="img/image_cart.jpg" alt=""
                                                                         class="cart__img"></div>
                                        <div class="cart__wrapper">
                                            <div class="cart__content">
                                                <a href="#" class="cart__title">Facial Lotion</a>
                                                <div class="cart__sticker">
                                                    <div class="sticker-hit">hit</div>
                                                </div>
                                                <div class="cart__sticker">
                                                    <div class="sticker-new">new</div>
                                                </div>
                                            </div>
                                            <div class="cart-wrap">
                                                <div class="cart__price-wrap mla mb-mobile">
                                                    <div class="cart__price">3500 ₽</div>
                                                    <div class="cart__price-old"></div>
                                                </div>
                                                <button type="button" class="cart__add btn"><i
                                                        class="icon icon-cart"></i></button>
                                            </div>
                                        </div>

                                        <a href="#" class="cart__fav-btn"><i class="icon icon-heart1"></i></a>
                                        <a href="#" class="cart__delete fav"><i class="icon icon-close"></i></a>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-if="empty" class="favorite-empty">
                            <div class="favorite-empty__img"><i class="icon icon-heart"></i></div>
                            <div class="favorite-empty__title">Список избранных товаров пуст</div>
                            <div class="favorite-empty__text">Добавьте товары в Избранное и поделитесь ими</div>
                        </div>
                    </div>`,
        });
Vue.component('bx-orders-tab',
        {
            props: {},
            data() {
                return {
                    orders: [],
                };
            },

            model: {},

            methods: {
                sendRequest: function() {
                    self = this;
                    BX.ajax({
                        url: '/api/internal/component/dermavi:order.history/dermavi',
                        method: 'GET',
                        dataType: 'json',
                        onsuccess: function(data) {
                            self.orders = data;
                        },
                    });
                },
            },
            created() {
                this.sendRequest();
            },
            updated() {
                $('.js-history-item').on('click', function() {
                    openRaw = $(this).siblings('.js-history-item__drop');
                    if ($(this).hasClass('open')) {
                        $(this).removeClass('open');
                        openRaw.slideUp();
                    } else {
                        $(this).addClass('open');
                        openRaw.slideDown();
                    }
                });
            },
            template: `<div class="order-history">
                            <div class="cart-list__office">
                                <div class="icon icon-list mobile"></div>
                                <div class="cart-list__office-title">История заказов</div>
                                <bx-sorter></bx-sorter>
                            </div>
                            <div v-if="orders.length>0"
                                 class="orders-history">
                                <div class="history-top">
                                    <div class="history-top__item number">Номер заказа</div>
                                    <div class="history-top__item total-price">Итоговая сумма</div>
                                    <div class="history-top__item count">Позиций в заказе</div>
                                    <div class="history-top__item date">Дата доставки</div>
                                    <div class="history-top__item status">Статус заказа</div>
                                    <div class="history-top__item repeat">Повторить заказ</div>
                                </div>
                                <div class="history-list">
                                    <div v-for="order in orders" class="history-item">
                                        <div class="history-item__raw js-history-item">
                                            <div class="history-item__number">{{order.id}}</div>
                                            <div class="history-item__total-price">{{order.total_price}} ₽</div>
                                            <div class="history-item__quantity">{{order.position_count}}</div>
                                            <div class="history-item__date">{{order.date}}</div>
                                            <div class="history-item__status">{{order.status}}</div>
                                            <div class="history-item__repeat"><i class="icon icon-repeat"></i></div>
                                        </div>
                                        <div class="history-item__drop js-history-item__drop">
                                            <div class="history-item__drop-top">
                                                <div class="history-item__drop-item">Наименование товара</div>
                                                <div class="history-item__drop-item quantity">Количество</div>
                                                <div class="history-item__drop-item price">Сумма</div>
                                            </div>
                                            
                                            <div class="history-item__drop-list">
                                                <div v-for="item in order.items"
                                                    class="history-item__drop-raw">
                                                    <div class="history-item__drop-img-wrap"><img :src="item.picture" alt="" class="history-item__drop-img"></div>
                                                    <div class="history-item__drop-title">{{item.NAME}}</div>
                                                    <div class="history-item__drop-quantity">{{item.QUANTITY}}</div>
                                                    <div class="history-item__drop-price">{{item.PRICE}} ₽</div>
                                                </div>
                                            </div>
                                            <div class="history-item__drop-bottom">
                                                <div class="history-item__drop-total">
                                                    <div class="history-item__drop-total_label">Итого:</div>
                                                    <div class="history-item__drop-total_value">{{order.total_price}} ₽</div>
                                                </div>
                                                <div class="history-item__drop-btn"><div class="btn btn-micro btn-black ">Повторить заказ</div></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div  v-for="order in orders"
                                    class="history-list__mobile">
                                    <div class="history-item__mobile">
                                        <div class="history-item__mobile-wrap">
                                            <div class="history-item__mobile-raw">
                                                <div class="history-item__number">Заказ №{{order.id}}</div>
                                                <div class="history-item__date">22/10/2020</div>
                                            </div>
                                            <div class="history-item__mobile-raw">
                                                <div class="history-item__mobile-raw__label">Позиций в заказе</div>
                                                <div class="history-item__quantity">order.position_count</div>
                                            </div>
                                            <div class="history-item__mobile-raw">
                                                <div class="history-item__mobile-raw__label">Статус заказа</div>
                                                <div class="history-item__status">order.status</div>
                                            </div>
                                            <div class="history-item__mobile-raw">
                                                <div class="history-item__mobile-raw__label">Итого</div>
                                                <div class="history-item__total-price">order.total_price ₽</div>
                                            </div>
        
                                            <div class="history-item__detail-wrap js-history-item-mobile-wrap">
                                                <div class="history-item__detail js-history-item-mobile"><span>Детали заказа</span><i class="icon icon-arrow_down"></i></div>
                                                <div class="history-item__drop-mobile js-history-item__drop-mobile">
                                                    <div class="history-item__drop-list">
                                                        <div v-for="item in order.items"
                                                            class="history-item__drop-raw">
                                                            <div class="history-item__drop-img-wrap"><img :src="item.picture" alt="" class="history-item__drop-img"></div>
                                                            <div class="history-item__drop-raw-wrap">
                                                                <div class="history-item__drop-title">{{item.NAME}}</div>
                                                                <div class="history-item__drop-raw-bottom">
                                                                    <div class="history-item__drop-price">{{item.PRICE}} ₽</div>
                                                                    <div class="history-item__drop-quantity">{{item.QUANTITY}} шт</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="history-item__drop-raw">
                                                            <div class="history-item__drop-img-wrap"><img src="img/drop.jpg" alt="" class="history-item__drop-img"></div>
                                                            <div class="history-item__drop-raw-wrap">
                                                                <div class="history-item__drop-title">Facial Lotion</div>
                                                                <div class="history-item__drop-raw-bottom">
                                                                    <div class="history-item__drop-price">1 890 ₽</div>
                                                                    <div class="history-item__drop-quantity">1 шт</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <button class="history-item__repeat btn btn-normal btn-black btn_full-width btn_center"><i class="icon icon-repeat"></i>Повторить заказ</button>
                                        </div>
                                    </div>
                                  
                                </div>
                            </div>
                            <div v-if="orders.length==0"
                                 class="favorite-empty">
                                <div class="favorite-empty__img"><i class="icon icon-heart"></i></div>
                                <div class="favorite-empty__title">Список заказов пуст</div>
                            </div>
                            ,
                       </div>`,
        });

BX.ready(function() {
    Vue.create({
        el: document.getElementById('personal'),
        data: {
            state: 'personal',
        },
        methods: {
            personal() {
                this.state = 'personal';
            },
            bonus() {
                this.state = 'bonus';
            },
            favorite() {
                this.state = 'favorite';
            },
            orders() {
                this.state = 'orders';
            },
        },


    });
});
