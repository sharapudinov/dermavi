Vue = BX.Vue;

Vue.component('bx-email-field',
        {
            props:
                    {
                        name: {default: 'email'},
                        placeholder: {default: 'EMAIL'},
                        label: {default: 'Адрес эл. почты'},
                        show_label: {default: true},
                    },
            data() {
                return {
                    value: '',
                };
            },

            model : {
                prop: 'value',
                event: 'change'
            },
            template: `<div class="login__form">
                        <label  v-if="show_label"
                                :for="name"
                               class="label label-login">{{label}}</label>
                        <input type="text"
                               :name="name"
                               v-bind:value="value"
                               v-on:change="$emit('change', $event.target.value)"
                               class="input input-brown input-normal input-border_bottom-gold input-full_width input-small_padding"
                               :placeholder="placeholder"
                               pattern='.+@.+\\..+'
                               required/>
                    </div>`,
        });

Vue.component('bx-password-field',
        {
            props: {
                name: {default: 'password'},
                placeholder: {default: 'Пароль'},
                label: {default: 'Пароль'},
            },
            data() {
                return {
                    type: 'password',
                };
            },
            methods:
                    {
                        show_password(event) {
                            this.type = 'text';
                        },
                        hide_password(event) {
                            this.type = 'password';
                        },
                    },
            template: `
                    <div class="login__form">
                        <label for="name"
                               class="label label-login">{{label}}
                        </label>
                        <input  :id="name"
                               :name="name"
                               :type="type"
                               :placeholder="placeholder"
                                class="input input-brown input-normal input-border_bottom-gold input-full_width input-small_padding"
                                required/>
                        <div class="form__btn form__btn-password">
                            <div class="form__icon">
                                <i class="icon icon-eye_off"
                                   v-on:mousedown="show_password"
                                   v-on:mouseup="hide_password"></i>
                            </div>
                        </div>
                    </div>
`,
        });

Vue.component('bx-sign-in',
        {
            /**
             * @emits 'sendEvent`' {text: string}
             */

            /**
             * @listens 'onModuleNameSomeComponentEvent' {} (global)
             */

            props:
                    {

                        doSomethingEventName: {default: 'onModuleNameSomeComponentDoSomethingEvent'},
                    },

            data() {
                return {};
            },

            methods:
                    {
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
                                success: function() {
                                    let back_url = (new URL(document.location)).searchParams.get('back_url') ?? '';
                                    window.location.href = '/' + back_url + '?' + 'auth=true';
                                },
                                error: function(response) {
                                    alert(response.responseJSON.error.message);
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
                        signup(event) {
                            this.$emit('signup');
                        },
                        forgot_password() {
                            this.$emit('forgot_password');
                        },
                    },

            template: `
                <div class="login__right">
        <div class=" login-logo__mobile">
            <a href="#"
               class="login-btn__back mobile-none">
                <i class="icon icon-arrow_left"></i>
            </a>
            <div class="login-logo__mobile-img-wrap">
                <img src="/img/Logo-log_mob.svg"
                     alt=""
                     class="login-logo__mobile-img">
            </div>
        </div>
        <div class="login__top">
            <a href="#" class="login__top-item active">Вход</a>
            <div class="login__top-logo-wrap">
                <img src="/img/logo_footer.svg"
                     alt=""
                     class="login__logo-img">
            </div>
            <a href="#"
               class="login__top-item"
               v-on:click.prevent="signup">Регистрация
            </a>
        </div>
        <div class="login__content">
            <div class="login__title mobile-none">Вход в аккаунт</div>
            <form v-on:submit.prevent="submit" id="signin"
                  action="/api/internal/user/auth/signin/">
                <div class="login__form-group">
                    <bx-email-field  name="signin_email"/>
                    <bx-password-field name="signin_password"/>
                </div>
                <div class="login__btn">
                    <input type="submit"
                           class="btn btn-gold_fill2 btn-normal btn_full-width btn_center"
                           value="Войти">
                </div>
            </form>
            <div class="login__links">
                <a v-on:click.prevent="forgot_password"
                   class="login__forget">
                    <u>Забыли пароль?</u>
                </a>
                <a href="#"
                   class="login__new-user"
                   v-on:click.prevent="signup">Нет аккаунта?
                    <u>Зарегистрируйтесь</u>
                </a>
            </div>
        </div>
    </div>
               `,

        });

Vue.component('bx-sign-up',
        {
            /**
             * @emits 'sendEvent`' {text: string}
             */

            /**
             * @listens 'onModuleNameSomeComponentEvent' {} (global)
             */

            props:
                    {

                        doSomethingEventName: {default: 'onModuleNameSomeComponentDoSomethingEvent'},
                    },

            data() {
                return {
                    errors: [],
                };
            },

            methods:
                    {
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
                                success: function() {
                                    /*let back_url = (new URL(document.location)).searchParams.get('back_url') ?? '';
                                    window.location.href = '/' + back_url + '?' + 'auth=true';*/
                                },
                                error: function(response) {
                                    alert(response.responseJSON.error.message);
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
                        signin(event) {
                            this.$emit('signin');
                        },
                    },

            template: `
                <div class="login__right">
        <div class="login-logo__mobile">
            <a href="#" class="login-btn__back mobile-none">
                <i class="icon icon-arrow_left"></i>
            </a>
            <div class="login-logo__mobile-img-wrap">
                <img src="/img/Logo-log_mob.svg" alt=""
                     class="login-logo__mobile-img">
            </div>
        </div>
        <div class="login__top">
            <a href="#"
               class="login__top-item"
               v-on:click.prevent="signin">Вход</a>
            <div class="login__top-logo-wrap">
                <img src="/img/logo_footer.svg"
                     alt=""
                     class="login__logo-img"></div>
            <a href="#"
               class="login__top-item active">Регистрация</a>
        </div>
        <div class="login__content registr">
            <div class="login__title mobile-none">Создание аккаунта</div>
            <div class="login__form-group">
                <form v-on:submit.prevent="submit"
                      action="/api/internal/user/auth/signup/"
                      data-login="signup_email">
                    <div class="login__form-group">
                        <div class="login__form">
                            <label for="signup_username"
                                   class="label label-login">Фамилия, имя и отчество
                            </label>
                            <input name="signup_username"
                                   type="text"
                                   class="input input-brown input-normal input-border_bottom-gold input-full_width input-small_padding"
                                   placeholder="ФИО"
                                   required/>
                        </div>
                        <bx-email-field name="signup_email"></bx-email-field>
                        <div class="login__form-password">
                            <bx-password-field name="signup_password"/>
                            <bx-password-field 
                                            name="signup_confirm_password" 
                                            label="Повторите пароль"/>
                        </div>
                        <p v-if="errors.length" class="errors">
                                <span class="label-login errors">Пожалуйста исправьте указанные ошибки:
                                </span>
                        <ul>
                            <li v-for="error in errors"
                                class="label-login error">{{ error }}
                            </li>
                        </ul>
                        </p>

                    </div>
                    <div class="login__checkbox">
                        <div class="checkbox-wrap reg">
                            <input id="confirm"
                                   class="checkbox"
                                   type="checkbox"
                                   required/>
                            <label for="confirm"><span>Я прочитал и согласен с</span> <span
                                        class="mobile">Я согласен с</span> &nbsp;<a href="#">Политикой
                                    конфиденциальности</a></label>
                        </div>
                    </div>
                    <div class="login__content-bottom">
                        <div class="login__btn">
                            <input type="submit"
                                   class="btn btn-gold_fill2 btn-normal btn_center"
                                   value="Зарегистрироваться">
                        </div>
                        <a href="#"
                           class="login__link"
                           v-on:click.prevent="signin">Есть аккаунт?
                            <u>Войдите</u>
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>
                
               `,

        });
Vue.component('bx-forgot-password',
        {
            /**
             * @emits 'sendEvent`' {text: string}
             */

            /**
             * @listens 'onModuleNameSomeComponentEvent' {} (global)
             */

            props:
                    {

                        doSomethingEventName: {default: 'onModuleNameSomeComponentDoSomethingEvent'},
                    },

            data() {
                return {
                    errors: [],
                    step: 1,
                    email: '',
                };
            },

            methods:
                    {
                        submit: function(e) {
                            if (!this.validate(e))
                                return;
                            var data = new FormData(e.target);
                            var self=this;
                            $.ajax({
                                url: e.target.action,
                                method: 'POST',
                                data: data,
                                processData: false,  // Сообщить jQuery не передавать эти данные
                                contentType: false,  // Сообщить jQuery не передавать тип контента
                                success: function() {
                                    self.next();
                                },
                                error: function(response) {
                                    alert(response.responseJSON.error.message);
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
                        signin(event) {
                            this.$emit('signin');
                        },
                        signup(event) {
                            this.$emit('signup');
                        },
                        next() {
                            this.step++;
                        },
                    },

            template: `<div class="login__right">
                <div class="login-logo__mobile open">
                    <a href="#"
                        class="login-btn__back"><i class="icon icon-arrow_left"></i></a>
                    <div class="login-logo__mobile-img-wrap">
                        <img src="img/Logo-log_mob.svg" 
                            alt=""
                            class="login-logo__mobile-img">
                    </div>
                </div>
                <div class="login__top mobile-none">
                    <a v-on:click.prevent="signup" 
                       class="login__top-item">Регистрация</a>
                </div>
                <div class="login__content"
                     v-if="step==1">
                    <a  v-on:click.prevent="signin"
                        href="#" 
                        class="login__back">
                       <i class="icon icon-arrow_left"></i>Вернуться ко входу</a>
                    <div class="login__title">Не удалось войти?</div>
                    <form v-on:submit.prevent="submit"
                          action="/api/internal/user/auth/send-restore-link/" 
                          method="method" 
                          name="name">
                        <div class="login__form-group">
                            <bx-email-field v-model="email"></bx-email-field>
                        </div>
                        <div class="login__btn-next">
                            <input  type="submit"    
                                    class="btn btn-brown btn-normal btn_center"
                                    value="Далее"> 
                                <i class="icon icon-arrow_right"></i>
                            </input>
                    </div>
                    </form>
                </div>
                <div class="login__content"
                     v-if="step==2">
                    <div class="login__title2">Письмо для восстановления</div>
                    <div class="login__title send">Отправлено</div>
                    <div class="login__text">Мы отправили на адрес <b>{{email}}</b> письмо для восстановления пароля. Проверьте почту и следуйте инструкциям в письме.</div>
                    <div class="login__btn">
                        <button v-on:click="signin"
                                class="btn btn-gold_fill2 btn-normal btn_center">Вернуться ко входу</button>
                    </div>
                </div*>
            </div>`,

        });

BX.ready(function() {
    Vue.create({
        el: document.getElementById('auth'),
        data: {
            step: 'signin',
        },
        methods: {
            signup() {
                this.step = 'signup';
            },
            signin() {
                this.step = 'signin';
            },
            forgot_password() {
                this.step = 'forgot_password';
            },
        },

    });
});



