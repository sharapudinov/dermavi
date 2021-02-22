(function(window) {
    'use strict';

    /**
     * Module name
     * Some Component Vue component
     *
     * @package bitrix
     * @subpackage moduleName
     * @copyright 2001-2019 Bitrix
     */

    const BX = window.BX;

    BX.Vue.component('bx-dermavi-authorize',
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

                created() {
                    BX.Vue.event.$on('onModuleNameSomeComponentEvent', this.onDoSomething);
                },

                beforeDestroy() {
                    BX.Vue.event.$off('onModuleNameSomeComponentEvent', this.onDoSomething);

                },

                methods:
                        {
                            onDoSomething(event) {

                            },
                        },


                computed:
                        {
                            localize(state) {
                                return BX.Vue.getFilteredPhrases('BX_MODULE-NAME_COMPONENT-PREFIX_');
                            },
                        },

                template: `
                <div class="login__right"
         v-bind:style="{display:signup_display}">
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
                      data-login="signup_email"
                      data-password="signup_password"
                      data-validate="signup_validator">
                    <div class="login__form-group">
                        <div class="login__form">
                            <label for="username"
                                   class="label label-login">Фамилия, имя и отчество
                            </label>
                            <input v-model="signup_user_full_name"
                                   name="signup_user_full_name"
                                   type="text"
                                   class="input input-brown input-normal input-border_bottom-gold input-full_width input-small_padding"
                                   placeholder="ФИО"
                                   required/>
                        </div>
                        <div class="login__form">
                            <label for="email" class="label label-login">Адрес эл. почты</label>
                            <input type="text"
                                   v-model="signup_email"
                                   name="signup_email"
                                   class="input input-brown input-normal input-border_bottom-gold input-full_width input-small_padding"
                                   placeholder="Email"
                                   pattern='.+@.+\\..+'
                                   required/>
                        </div>
                        <div class="login__form-password">
                            <div class="login__form">
                                <label for="password"
                                       class="label label-login">Пароль
                                </label>
                                <input v-model="signup_password"
                                       name="signup_password"
                                       :type="signup_password_input_type"
                                       class="input input-brown input-normal input-border_bottom-gold input-full_width input-small_padding"
                                       required/>
                                <button class="form__btn form__btn-password"
                                        type="submit">
                                    <div class="form__icon">
                                        <i class="icon icon-eye_off"
                                           v-on:mousedown="show_password"
                                           v-on:mouseup="hide_password"
                                           data-var_name="signup_password_input_type">
                                        </i>
                                    </div>
                                </button>
                            </div>
                            <div class="login__form">
                                <label for="password2"
                                       class="label label-login">Повторите пароль
                                </label>
                                <input v-model="signup_confirm_password"
                                       :type="signup_confirm_password_input_type"
                                       class="input input-brown input-normal input-border_bottom-gold input-full_width input-small_padding"
                                       required/>
                                <button class="form__btn form__btn-password"
                                        type="submit">
                                    <div class="form__icon">
                                        <i class="icon icon-eye_off"
                                           v-on:mousedown="show_password"
                                           v-on:mouseup="hide_password"
                                           data-var_name="signup_confirm_password_input_type">
                                        </i>
                                    </div>
                                </button>
                            </div>
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
})(window);
