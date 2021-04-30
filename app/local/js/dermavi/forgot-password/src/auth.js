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
         v-bind:style="{display:forgot_password_display}">
        <div class="login-logo__mobile open">
            <a href="#"
               class="login-btn__back">
                <i class="icon icon-arrow_left"></i>
            </a>
            <div class="login-logo__mobile-img-wrap">
                <img src="img/Logo-log_mob.svg"
                     alt=""
                     class="login-logo__mobile-img">
            </div>
        </div>
        <div class="login__top mobile-none">
            <a href="#"
               v-on:click="signin"
               class="login__top-item active">Вход</a>
            <div class="login__top-logo-wrap">
                <img src="/img/logo_footer.svg"
                     alt=""
                     class="login__logo-img">
            </div>
            <a href="#"
               v-on:click="signup"
               class="login__top-item">Регистрация</a>
        </div>
        <div class="login__content">
            <a href="#"
               v-on:click="signin"
               class="login__back">
                <i class="icon icon-arrow_left"></i>Вернуться ко входу
            </a>
            <div class="login__title">Не удалось войти?</div>
            <form method="post"
                  action=""
                  v-on:submit.prevent="submit"/>
            <div class="login__form-group">
                <bx-email-field/>
            </div>
            <div class="login__btn-next">
                <input type="submit"
                       class="btn btn-brown btn-normal btn_center">Далее
                <i class="icon icon-arrow_right"></i>
                </input>
            </div>
            </form>

        </div>
    </div>
               `,
            });
})(window);
