Vue = BX.Vue;

Vue.component('bx-order-make-tabs',
        {
            props: {
                tabs: {
                    default: function() {
                        return [
                            {
                                title: 'Ваши данные',
                                event: 'personal',
                            },
                            {
                                title: 'Доставка',
                                event: 'delivery',
                            },
                            {
                                title: 'Оплата',
                                event: 'payment',
                            },
                        ];
                    },
                },
                parent_state: {default: 'personal'},
            },
            computed: {
                state: function() {
                    return this.parent_state;
                },
            },

            methods: {
                click(event) {
                    this.$emit(event.currentTarget.dataset.event);
                },
                classObject: function(state) {
                    return {
                        active: state == this.state,
                    };
                },
            },
            template: `<div class="ordering__tabs data">
                            <div  v-for="tab in tabs"
                                v-on:click="click"
                                :data-event="tab.event"
                               class="ordering__tab" v-bind:class="classObject(tab.event)"><i class="icon icon-check_circle"></i>{{tab.title}}
                            </div>
                       </div>`,
        });

Vue.component('bx-checkout-validable-field',
        {
            props: {
                id: {required: true},
                name: {required: true},
                value: {default: ''},
                placeholder: {default: ''},
                label: {default: ''},
                validator: {
                    default: function() {
                        return function(value) {
                            return !!value;
                        };
                    },
                },
            },
            computed: {
                classObject: function() {
                    return {
                        wrong: !this.isValid(),
                    };
                },
            },
            methods: {
                isValid: function() {
                    return this.validator(this.value);
                },
                onInput: function(event) {
                    this.$emit('input', event.target.value)
                }
            },
            template: `<div v-bind:class="classObject">
                                    <label v-if="!!label"
                                            :for="id" 
                                           class="label label-order">{{label}}</label>
                                    <input :id="id"
                                           type="text"
                                           :name="name" 
                                           class="input input-border input-normal input-full_width input-small_padding"
                                           :placeholder="placeholder"
                                           v-bind:value="value"
                                           v-on:input="onInput">
                                </div>`,
        });

Vue.component('bx-dadata-suggest-address-field',
        {
            props: {
                id: {required: true},
                name: {required: true},
                value: {default: ''},
                placeholder: {default: ''},
                label: {default: ''},
                validator: {
                    default: function() {
                        return function(value) {
                            return !!value;
                        };
                    },
                },
            },
            data() {
                return {
                    suggestions: [],
                };
            },
            computed: {
                classObject: function() {
                    return {
                        wrong: !this.isValid(),
                    };
                },
            },
            methods: {
                isValid: function() {
                    return this.validator(this.value);
                },
                onInput: function(event) {
                    this.$emit('input', event.target.value);
                    this.debounced_refresh_suggestions()
                },
                refresh_suggestions: function() {
                    var url = 'https://suggestions.dadata.ru/suggestions/api/4_1/rs/suggest/address';
                    var token = '10cbeb9fc651f8c5e9f7dbefc9e6e8d6ffbd53aa';

                    var options = {
                        method: 'POST',
                        mode: 'cors',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'Authorization': 'Token ' + token,
                        },
                        body: JSON.stringify({query: this.value}),
                    };
                    fetch(url, options)
                    .then(response => response.json())
                    .then(result => this.suggestions = result.suggestions)
                    .catch(error => console.log('error', error));

                },
            },
            created: function() {
                this.debounced_refresh_suggestions = _.debounce(this.refresh_suggestions, 300);
            },
            template: `<div v-bind:class="classObject">
                                    <label v-if="!!label"
                                            :for="id" 
                                           class="label label-order">{{label}}</label>
                                    <input :id="id"
                                           type="text"
                                           :name="name" 
                                           class="input input-border input-normal input-full_width input-small_padding"
                                           :placeholder="placeholder"
                                           v-bind:value="value"
                                           v-on:input="onInput">
                                    <div v-for="suggestion in suggestions"
                                         class="suggestion">{{suggestion.value}}</div>
                                </div>`,
        });

Vue.component('bx-order-personal-data-tab',
        {
            props: {
                initial_name: {default: ''},
                surname: {default: ''},
                patronymic: {default: ''},
                email: {default: ''},
                phone: {default: ''},
            },
            data: function() {
                return {
                    name: this.initial_name,
                };
            },
            computed: {
                state: function() {
                    return this.parent_state;
                },

            },

            model: {},
            methods: {
                validate: function() {
                    for (field in this.$refs) {
                        if (!this.$refs[field].isValid()) {
                            return false;
                        }
                    }
                    return true;
                },
                delivery: function() {
                    if (this.validate()) {
                        this.$emit('delivery');
                    }
                },
            },
            template: `<div id="userdata">
                            <div class="ordering__fio-group">
                                <bx-checkout-validable-field name="DELIVERY_FIRST_NAME"
                                                             id="name"
                                                             ref="name"
                                                             v-model="name"
                                                             placeholder="Имя"
                                                             class="ordering__fio-form form"
                                                             label="Имя">
                                                                
                                </bx-checkout-validable-field>
                                <bx-checkout-validable-field id="surname"
                                                             name="DELIVERY_LAST_NAME"
                                                             ref="surname"
                                                             v-model="surname"
                                                             placeholder="Фамилия"
                                                             label="Фамилия"
                                                             class="ordering__fio-form form">
                                                                    
                                </bx-checkout-validable-field>
                                <bx-checkout-validable-field id="patronymic"
                                                             name="DELIVERY_SECOND_NAME"
                                                             v-model="patronymic"
                                                             ref="patronymic"
                                                             placeholder="Отчество"
                                                             label="Отчество"
                                                             class="ordering__fio-form form">
                                </bx-checkout-validable-field>
                            </div>
                            <div class="ordering__contact-group offer-border">
                                <bx-checkout-validable-field id="phone"
                                                             name="DELIVERY_PHONE"
                                                             v-model="phone"
                                                             ref="phone"
                                                             placeholder="+7 (321) 325 66 88"
                                                             label="Номер телефона"
                                                             class="ordering__contact-form form">
                                </bx-checkout-validable-field>
                                <bx-checkout-validable-field id="email"
                                                             name="ORDER_EMAIL"
                                                             v-model="email"
                                                             ref="email"
                                                             placeholder="+7 (321) 325 66 88"
                                                             label="Адрес эл. почты"
                                                             class="ordering__contact-form form">
                                </bx-checkout-validable-field>
                            </div>
                            <div class="ordering__bottom">
                                <div class="ordering__require"><i class="icon icon-info"></i>Все поля обязательны для заполнения
                                </div>
                                <div class="ordering__buttons">
                                    <div class="ordering__btn">
                                    <a href="/personal/cart/" 
                                       class="btn btn-gray btn-normal btn-small-padding">Вернуться
                                            в корзину</a></div>
                                    <div class="ordering__btn">
                                        <a class="btn btn-black btn-normal btn-small-padding"
                                                v-on:click="delivery">Далее
                                                    <i class="icon icon-arrow_right-long"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>`,
        });

Vue.component('bx-order-delivery-data-tab',
        {
            props: {
                ip: {default: null},
                initial_zip: {default: '367000'},
                initial_region: {default: ''},
                initial_city: {default: ''},
                street: {default: ''},
                house: {default: ''},
                corpus: {default: ''},
                flat: {default: ''},
                initial_address: {default:''},
                serialized_delivery_service_list: String,
                default_delivery_service_id: {default: '2'},
            },
            computed: {
                delivery_service_list: function() {
                    return JSON.parse(this.serialized_delivery_service_list);
                },
            },
            data: function() {
                return {
                    zip: this.initial_zip,
                    region: this.initial_region,
                    city: this.initial_city,
                    address: this.initial_address,
                    delivery_service_id: this.s,
                };
            },
            methods: {
                validate: function() {
                    for (field in this.$refs) {
                        if (!this.$refs[field].isValid()) {
                            return false;
                        }
                    }
                    return true;
                },
                payment: function() {
                    if (this.validate()) {
                        this.$emit('payment');
                    }
                },
                personal: function() {
                    this.$emit('personal');
                },
                geolocation: function() {
                    var url = 'https://suggestions.dadata.ru/suggestions/api/4_1/rs/iplocate/address?ip=' + this.ip;
                    var token = '10cbeb9fc651f8c5e9f7dbefc9e6e8d6ffbd53aa';

                    var options = {
                        method: 'POST',
                        mode: 'cors',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'Authorization': 'Token ' + token,
                        },
                        body: JSON.stringify({query: this.value}),
                    };
                    fetch(url, options).then(response => response.json()).then(result => this.set_location(result.location)).catch(error => console.log('error', error));
                },
                set_location: function(location) {
                    this.zip=location.data.postal_code;
                    this.region = location.data.region_with_type;
                    this.city = location.data.city;
                },
            },
            template: `<div id="delivery">
                            <div class="ordering__delivery">
                                <div class="ordering__delivery-title">Адрес доставки</div>
                                <a href="#" 
                                   class="ordering__delivery-location"
                                   v-on:click.prevent="geolocation"\>
                                    <i class="icon icon-navigation"></i>
                                    <u>Определить мое местоположение</u>
                                </a>
                            </div>
                            <div class="ordering__delivery-group">
                                <bx-checkout-validable-field  id="zip"
                                                             name="DELIVERY_ZIP"
                                                             v-model="zip"
                                                             ref="zip"
                                                             label="Индекс"
                                                             class="ordering__delivery-form form">
                                </bx-checkout-validable-field>
                                <bx-checkout-validable-field id="region"
                                                             name="DELIVERY_REGION"
                                                             v-model="region"
                                                             ref="region"
                                                             label="Регион"
                                                             class="ordering__delivery-form form">
                                </bx-checkout-validable-field>
                                 <bx-checkout-validable-field id="city"
                                                             name="DELIVERY_CITY"
                                                             v-model="city"
                                                             ref="city"
                                                             label="Город / Населенный пункт"
                                                             class="ordering__delivery-form form">
                                </bx-checkout-validable-field>
                            </div>
                            <div class="ordering__delivery-group">
                                <bx-dadata-suggest-address-field  id="address"
                                                             name="DELIVERY_ADDRESS"
                                                             v-model="address"
                                                             ref="address"
                                                             label="Адрес"
                                                             class="ordering__delivery-form form">
                                </bx-dadata-suggest-address-field>
                            </div>
                            <div class="ordering__delivery-type">
                                <div class="delivery-type__title">Способ доставки</div>
                                <div class="delivery-tabs">
                                    <div class="delivery-tab">
                                        <div id="orderform-delivery" 
                                             class="order-form__checkbox-wrap" 
                                             data-toggle="buttons"
                                             role="radiogroup" 
                                             aria-required="true" 
                                             aria-invalid="false">
                                            <div v-for="delivery_service in delivery_service_list"
                                            class="tabs__item order-form__checkbox-element js-change-delivery">
                                                <div class="checkbox-type_type-2">
                                                    <input name="DELIVERY_SERVICE_ID" 
                                                           type="radio"
                                                           :id="'searchform-delivery'+delivery_service.ID" 
                                                           :value="delivery_service.ID" 
                                                           :checked="delivery_service.ID==default_delivery_service_id"
                                                           :data-price="default_delivery_service_id">
                                                    <label :for="'searchform-delivery'+delivery_service.ID"
                                                           class="checkbox-label-type_type-2 btn_center">
                                                        <div class="tab-check-box"></div>
                                                        {{delivery_service.NAME}}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="delivery-promo offer-border">
                                <div class="cart-promo">
                                    <div class="cart-promo__offer big-icon">
                                        <i class="icon icon-box"></i>
                                        <div class="cart-promo__offer-text">
                                            <div class="cart-promo__offer-text-wrap"><b>Ближайшая дата доставки заказа по
                                                    вашему адресу - 23 октября.</b>&nbsp;
                                            </div>
                                            <div class="cart-promo__offer-text-wrap">Более подробную информацию о доставке
                                                читайте &nbsp;<u>здесь</u>.
                                            </div>
        
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="ordering__bottom">
                                <div class="ordering__require"><i class="icon icon-info"></i>Все поля обязательны для заполнения
                                </div>
                                <div class="ordering__buttons">
                                    <div class="ordering__btn">
                                        <a v-on:click="personal"
                                           class="btn btn-gray btn-normal btn-small-padding">Вернуться назад</a>
                                    </div>
                                    <div class="ordering__btn">
                                        <a class="btn btn-black btn-normal btn-small-padding"
                                                v-on:click="payment">Далее                                               
                                                    <i class="icon icon-arrow_right-long"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>`,
        });

Vue.component('bx-order-payment-data-tab',
        {
            props:
                    {
                        serialized_pay_system_list: String,
                        default_payment_system_id: {default: '1'},
                        amount_to_pay: {
                            required: true,
                        },
                        bonus_amount: {
                            default: 0,
                        },
                    },
            computed: {
                pay_system_list: function() {
                    return JSON.parse(this.serialized_pay_system_list);
                },
            },
            data: function() {
                return {
                    bonus_amount_to_pay: 0,
                };
            },
            methods: {
                validate: function(e) {
                    this.errors = [];

                    if (!this.errors.length) {
                        return true;
                    }
                    e.preventDefault();
                    e.stopPropagation();
                },
                delivery: function() {
                    this.$emit('delivery');
                },
            },
            template: `<div id="order-payment">
                            <div class="ordering__delivery-type">
                                <div class="delivery-type__title">Способ оплаты</div>
                                <div class="delivery-tabs">
                                    <div class="delivery-tab">
                                        <div id="orderform-payment" 
                                             class="order-form__checkbox-wrap" 
                                             data-toggle="buttons"
                                             role="radiogroup" 
                                             aria-required="true" 
                                             aria-invalid="false">
                                            <div v-for="pay_system in pay_system_list"
                                                 class="tabs__item order-form__checkbox-element js-change-delivery">
                                                <div class="checkbox-type_type-2">
                                                    <input name="PAY_SYSTEM_ID" 
                                                           type="radio" 
                                                           :id="'searchform-payment'+pay_system.ID"
                                                           :checked="pay_system.ID==default_payment_system_id"
                                                           :value="pay_system.ID"
                                                           required> 
                                                    <label :for="'searchform-payment'+pay_system.ID"
                                                           class="checkbox-label-type_type-2 btn_center">
                                                        <div class="tab-check-box"></div>
                                                        {{pay_system.NAME}}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="ordering__bonus-group">
                                <div class="ordering__bonus-title">Оплата бонусами</div>
                                <div class="ordering__bonus-total">
                                    <div class="ordering__label">Сумма к оплате</div>
                                    <div class="ordering__value">{{amount_to_pay}} ₽</div>
                                </div>
                                <div class="ordering__bonus-count">
                                    <div class="ordering__label">Бонусный счёт</div>
                                    <div class="ordering__value">{{bonus_amount}} ББ</div>
                                </div>
                                <div class="ordering__bonus-form-wrap">
                                    <div class="ordering__bonus-form">
                                        <label for="bonus" 
                                               class="label label-order">Бонусы для списания</label>
                                        <input id="bonus" 
                                               type="text"
                                               class="input input-border input-normal input-small_padding input-full_width"
                                               placeholder="" 
                                               v-model="bonus_amount_to_pay">
                                    </div>
                                    <div class="ordering__bonus-btn">
                                        <a class="btn btn-micro btn-gold_fill">Списать</a>
                                    </div>
                                </div>
                            </div>
                            <div class="ordering__bottom">
                                <div class="ordering__require"></div>
                                <div class="ordering__buttons">
                                    <div class="ordering__btn"><a href="#"
                                                                  class="btn btn-gray btn-normal btn-small-padding"
                                                                  v-on:click="delivery">Вернуться назад</a>
                                    </div>
                                    <div class="ordering__btn">
                                        <input type="submit" 
                                               value="Завершить оформление"
                                               class="btn btn-black btn-normal btn-small-padding">
                                        </input>
                                    </div>
                                </div>
                            </div>
                        </div>`,
        });

BX.ready(function() {
    Vue.create({
        el: document.getElementById('order-make'),
        data: {
            state: 'personal',
            order_number: null
        },
        methods: {
            personal() {
                this.state = 'personal';
            },
            delivery() {
                if (this.$refs.personal.validate()) {
                    this.state = 'delivery';
                }
            },
            payment() {
                if (this.$refs.delivery.validate()) {
                    this.state = 'payment';
                }
            },
            success() {
                this.state = 'success';
            },
            submit(e) {
                var form_data = new FormData(e.target);
                if (!this.validate(form_data))
                    return;
                self = this;
                $.ajax({
                    url: e.target.action,
                    method: 'POST',
                    data: form_data,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        self.order_number=response.success.message
                        self.success();
                    },
                    error: function(response) {
                        alert(response.responseJSON.error.message);
                    },
                });
            },
            validate() {
                return true;
            },
        },
    });
});
