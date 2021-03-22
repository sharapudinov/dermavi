Vue = BX.Vue;

Vue.component('bx-cart-list',
        {
            props: {
                initial_items: {
                    type: Array,
                },
            },
            data() {
                return {
                    items: this.initial_items,
                };
            },
            model: {},

            methods: {
                click(event) {
                    this.$emit(event.currentTarget.dataset.event);
                },
                delete_item: function(id) {
                    for (let index = 0; index < this.items.length; index++) {

                        if (this.items[index].ID == id) {
                            this.items.splice(index,1);
                            break;
                        }
                    }
                    this.emit_change();
                },
                emit_change_event() {
                    this.$emit('change');
                },
            },
            template: `<div class="cart-wrap__left">
                            <div class="cart-list">
                                <div class="cart-list__top">
                                    <div class="cart-list__name">Наименование товара</div>
                                    <div class="cart-list__top-wrap">
                                        <div class="cart-list__count">Количество</div>
                                        <div class="cart-list__price">Цена</div>
                                        <div class="cart-list__delete">Удалить</div>
                                    </div>
                    
                                </div>
                                <bx-cart-item v-for="item in items" 
                                              v-bind="item"
                                              v-bind:key="item.ID"
                                              v-on:delete="delete_item"
                                              v-on:change="emit_change_event"></bx-cart-item>
                            </div>
                    
                            <div class="cart-promo__wrap">
                                <div class="cart-promo">
                                    <div class="cart-promo__offer"><i class="icon icon-info"></i><span>Совершите покупку ещё на 12 322 ₽, чтобы получить  &nbsp;<b>бесплатную доставку</b></span> </div>
                                    <div class="cart-promo__delivery"><i class="icon icon-box"></i><span>Доставим ваш заказ от 17 октября в &nbsp;<u>Махачкалу</u></span></div>
                                </div>
                            </div>
                       </div>`,
        });

Vue.component('bx-cart-item',
        {
            props: {
                ID: {
                    type: String,
                    required: true,
                },
                PRODUCT_ID: {
                    type: String,
                    required: true,
                },
                NAME: {
                    type: String,
                    required: true,
                },
                PRICE: {
                    type: String,
                },
                QUANTITY: {
                    type: String,
                },

                DISCOUNT_VALUE: {
                    type: String,
                    default: null,
                },
                DISCOUNT_PRICE: {
                    type: String,
                    default: null,
                },
                STICKER_HIT: {
                    default: false,
                },
                STICKER_NEW: {
                    default: false,
                },
                properties: {
                    type: Array,
                    default: [],
                },
                maxQuantity: {
                    type: Number,
                    default: null,
                },
            },
            data() {
                return {
                    quantity: parseFloat(this.QUANTITY),
                    max_quantity: !!this.maxQuantity ? parseFloat(this.maxQuantity) : 0,
                };
            },
            computed: {
                cost: function() {
                    return this.quantity * this.PRICE;
                },
                border_bottom: function() {
                    return this.quantity < 1;
                },
                border_top: function() {
                    return this.quantity > this.max_quantity;
                },
            },
            watch: {
                border_bottom: function() {
                    this.quantity = 1;
                },
                border_top: function() {
                    this.quantity = this.max_quantity;
                },
                quantity: function(newQuantity, oldQuantity) {
                    if (this.is_valid_quantity(newQuantity)) {
                        this.setNewQuantity();
                    }
                },
            },
            methods: {
                is_valid_quantity(quantity) {
                    return quantity > 0 && (this.max_quantity && quantity <= this.max_quantity || !this.max_quantity);
                },
                increment() {
                    this.quantity++;
                },
                decrement() {
                    this.quantity--;
                },
                setNewQuantity() {
                    $.ajax({
                        url: '/api/internal/sale/cart/add/',
                        data: {
                            productId: this.PRODUCT_ID,
                            quantity: this.quantity,
                            properties: this.properties.map(function(item) {
                                return {
                                    CODE: item.CODE,
                                    VALUE: item.VALUE,
                                };
                            }),
                        },
                        method: 'POST',
                        dataType: 'json',
                        success: this.emit_change_event(),
                    });
                },
                emit_delete_event() {
                    this.$emit('delete', this.ID);
                },
                emit_change_event() {
                    this.$emit('change');
                },
                deleteItem() {
                    BX.ajax({
                        url: '/api/internal/sale/cart/remove/',
                        data: {
                            productId: this.PRODUCT_ID,
                            quantity: this.quantity,
                        },
                        method: 'POST',
                        dataType: 'json',
                        onsuccess: this.emit_delete_event,
                    });
                },
            },
            created() {
            },
            template:
                    `<div class="cart">
                        <div class="cart__img-wrap"><img src="img/image_cart.jpg" alt="" class="cart__img"></div>
                        <div class="cart__wrapper">
                            <div class="cart__content">
                                <div class="cart__title">{{NAME}}</div>
                                <div class="cart__sticker" 
                                     v-if="STICKER_HIT"><div class="sticker-hit">hit</div></div>
                                <div class="cart__sticker"  
                                     v-if="STICKER_NEW"><div class="sticker-new">new</div></div>
                            </div>
                            <div class="cart-wrap">
                                <div class="counter cart__counter">
                                    <button  v-on:click="increment"
                                             class="up_count btn counter__btn" 
                                             title="Up"><i class="icon icon-plus"></i></button>
                                    <input class="input counter__value" 
                                           type="number" 
                                           v-model="quantity" 
                                           minlength="1">
                                    <button v-on:click="decrement"
                                            class="down_count btn counter__btn" 
                                            title="Down"><i class="icon icon-minus"></i></button>
                                </div>
                                <div class="cart__price-wrap">
                                    <div class="cart__price">{{cost}} ₽   
                                        <span v-if="PRICE==0">подарок</span></div>
                                    <div class="cart__price-old"
                                         v-if="!!DISCOUNT_VALUE">{{DISCOUNT_PRICE}} ₽</div>
                                </div>
                            </div>
                        </div>
                        <div v-on:click="deleteItem"
                             class="cart__delete">
                            <i class="icon icon-close"></i>
                        </div>
                    </div>`,
        });

Vue.component('bx-cart',
        {
            props: {
                items_data: String,
                summary_data: String,
            },
            data() {
                return {
                    summary: JSON.parse(this.summary_data),
                    items: JSON.parse(this.items_data),
                };
            },
            computed: {
                cart_item_cost: function() {
                    return this.summary.cartItemCost;
                },
                cart_item_discount: function() {
                    return this.summary.cartItemDiscount;
                },
                delivery_cost: function() {
                    return this.summary.deliveryCost;
                },
                promocode_discount: function() {
                    return this.summary.promocodeDiscount;
                },
                cart_cost: function() {
                    return this.summary.cartCost;
                }
            },
            model: {},
            methods: {
                refresh() {
                    BX.ajax({
                        url: '/api/internal/component/dermavi:cart/json',
                        data: {},
                        method: 'GET',
                        dataType: 'json',
                        onsuccess: this.update,
                    });
                },
                update(data) {
                    this.summary=data.summary
                },
            },
            template: `<div id="cart" 
                            class="cart-page-wrap padding-80 mb200">
                            <bx-cart-list :initial_items='items'
                                           v-on:change="refresh"></bx-cart-list>
                            <div class="cart-wrap__right">
                                <div class="cart-total">
                                    <div class="cart-total__title">Ваш заказ</div>
                                        <div class="cart-total__raw">
                                            <div class="cart-total__raw-label">Сумма заказа</div>
                                            <div class="cart-total__raw-value">{{cart_item_cost}} ₽</div>
                                         </div>
                                    <div class="cart-total__raw"
                                         v-if="!!cart_item_discount">
                                        <div class="cart-total__raw-label">Общая скидка</div>
                                        <div class="cart-total__raw-value">{{cart_item_discount}} ₽</div>
                                    </div>
                                    <div class="cart-total__raw" v-if="delivery_cost">
                                        <div class="cart-total__raw-label">Доставка</div>
                                        <div class="cart-total__raw-value">{{delivery_cost}} ₽</div>
                                    </div>
                                    <div class="cart-total__raw">
                                        <div class="cart-total__raw-label">Скидка по промокоду</div>
                                        <div class="cart-total__raw-value">{{promocode_discount}} ₽</div>
                                    </div>
                                    <div class="cart-total__promo-input">
                                        <input type="text" 
                                               class="input input-normal input-full_width input-border input-bgr_grey"
                                               placeholder="Введите промокод">
                                        <button class="form__btn cart-total__search " 
                                                type="submit">
                                            <div class="form__icon"><i class="icon icon-arrow_right-long"></i></div>
                                        </button>
                                    </div>
                                    <div class="cart-total__price-total mb">
                                        <div class="cart-total__price-total-label">Итого</div>
                                        <div class="cart-total__price-total-value">{{cart_cost}} ₽</div>
                                    </div>
                                    <div class="cart-total__delivery-info">
                                        <div class="cart-total__delivery-info__title">
                                            Доставка
                                        </div>
                                        <div class="cart-total__delivery-info__btn">
                                            <i class="icon icon-plus_circle"></i>
                                        </div>
                                    </div>
                                    <a href="/personal/order/make/" class="cart-total__btn btn btn_full-width btn_center btn-normal btn-black btn-small-padding">Перейти
                                        к оформлению</a>
                                </div>
                            </div>
                        </div>`,
        });

BX.ready(function() {
    Vue.create({
        el: document.getElementById('cart'),
        data: {},
        methods: {},
    });
});
