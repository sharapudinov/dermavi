this.BX = this.BX || {};
(function (exports) {
    'use strict';

    (function (window) {
      /**
       * Module name
       * Some Component Vue component
       *
       * @package bitrix
       * @subpackage moduleName
       * @copyright 2001-2019 Bitrix
       */

      var BX = window.BX;
      BX.Vue.component('bx-dermavi-authorize', {
        /**
         * @emits 'sendEvent`' {text: string}
         */

        /**
         * @listens 'onModuleNameSomeComponentEvent' {} (global)
         */
        props: {
          doSomethingEventName: {
            default: 'onModuleNameSomeComponentDoSomethingEvent'
          }
        },
        data: function data() {
          return {};
        },
        created: function created() {
          BX.Vue.event.$on('onModuleNameSomeComponentEvent', this.onDoSomething);
        },
        beforeDestroy: function beforeDestroy() {
          BX.Vue.event.$off('onModuleNameSomeComponentEvent', this.onDoSomething);
        },
        methods: {
          onDoSomething: function onDoSomething(event) {}
        },
        computed: {
          localize: function localize(state) {
            return BX.Vue.getFilteredPhrases('BX_MODULE-NAME_COMPONENT-PREFIX_');
          }
        },
        template: "\n                <div class=\"login__right\"\n         v-bind:style=\"{display:signup_display}\">\n        <div class=\"login-logo__mobile\">\n            <a href=\"#\" class=\"login-btn__back mobile-none\">\n                <i class=\"icon icon-arrow_left\"></i>\n            </a>\n            <div class=\"login-logo__mobile-img-wrap\">\n                <img src=\"/img/Logo-log_mob.svg\" alt=\"\"\n                     class=\"login-logo__mobile-img\">\n            </div>\n        </div>\n        <div class=\"login__top\">\n            <a href=\"#\"\n               class=\"login__top-item\"\n               v-on:click.prevent=\"signin\">\u0412\u0445\u043E\u0434</a>\n            <div class=\"login__top-logo-wrap\">\n                <img src=\"/img/logo_footer.svg\"\n                     alt=\"\"\n                     class=\"login__logo-img\"></div>\n            <a href=\"#\"\n               class=\"login__top-item active\">\u0420\u0435\u0433\u0438\u0441\u0442\u0440\u0430\u0446\u0438\u044F</a>\n        </div>\n        <div class=\"login__content registr\">\n            <div class=\"login__title mobile-none\">\u0421\u043E\u0437\u0434\u0430\u043D\u0438\u0435 \u0430\u043A\u043A\u0430\u0443\u043D\u0442\u0430</div>\n            <div class=\"login__form-group\">\n                <form v-on:submit.prevent=\"submit\"\n                      action=\"/api/internal/user/auth/signup/\"\n                      data-login=\"signup_email\"\n                      data-password=\"signup_password\"\n                      data-validate=\"signup_validator\">\n                    <div class=\"login__form-group\">\n                        <div class=\"login__form\">\n                            <label for=\"username\"\n                                   class=\"label label-login\">\u0424\u0430\u043C\u0438\u043B\u0438\u044F, \u0438\u043C\u044F \u0438 \u043E\u0442\u0447\u0435\u0441\u0442\u0432\u043E\n                            </label>\n                            <input v-model=\"signup_user_full_name\"\n                                   name=\"signup_user_full_name\"\n                                   type=\"text\"\n                                   class=\"input input-brown input-normal input-border_bottom-gold input-full_width input-small_padding\"\n                                   placeholder=\"\u0424\u0418\u041E\"\n                                   required/>\n                        </div>\n                        <div class=\"login__form\">\n                            <label for=\"email\" class=\"label label-login\">\u0410\u0434\u0440\u0435\u0441 \u044D\u043B. \u043F\u043E\u0447\u0442\u044B</label>\n                            <input type=\"text\"\n                                   v-model=\"signup_email\"\n                                   name=\"signup_email\"\n                                   class=\"input input-brown input-normal input-border_bottom-gold input-full_width input-small_padding\"\n                                   placeholder=\"Email\"\n                                   pattern='.+@.+\\..+'\n                                   required/>\n                        </div>\n                        <div class=\"login__form-password\">\n                            <div class=\"login__form\">\n                                <label for=\"password\"\n                                       class=\"label label-login\">\u041F\u0430\u0440\u043E\u043B\u044C\n                                </label>\n                                <input v-model=\"signup_password\"\n                                       name=\"signup_password\"\n                                       :type=\"signup_password_input_type\"\n                                       class=\"input input-brown input-normal input-border_bottom-gold input-full_width input-small_padding\"\n                                       required/>\n                                <button class=\"form__btn form__btn-password\"\n                                        type=\"submit\">\n                                    <div class=\"form__icon\">\n                                        <i class=\"icon icon-eye_off\"\n                                           v-on:mousedown=\"show_password\"\n                                           v-on:mouseup=\"hide_password\"\n                                           data-var_name=\"signup_password_input_type\">\n                                        </i>\n                                    </div>\n                                </button>\n                            </div>\n                            <div class=\"login__form\">\n                                <label for=\"password2\"\n                                       class=\"label label-login\">\u041F\u043E\u0432\u0442\u043E\u0440\u0438\u0442\u0435 \u043F\u0430\u0440\u043E\u043B\u044C\n                                </label>\n                                <input v-model=\"signup_confirm_password\"\n                                       :type=\"signup_confirm_password_input_type\"\n                                       class=\"input input-brown input-normal input-border_bottom-gold input-full_width input-small_padding\"\n                                       required/>\n                                <button class=\"form__btn form__btn-password\"\n                                        type=\"submit\">\n                                    <div class=\"form__icon\">\n                                        <i class=\"icon icon-eye_off\"\n                                           v-on:mousedown=\"show_password\"\n                                           v-on:mouseup=\"hide_password\"\n                                           data-var_name=\"signup_confirm_password_input_type\">\n                                        </i>\n                                    </div>\n                                </button>\n                            </div>\n                        </div>\n                        <p v-if=\"errors.length\" class=\"errors\">\n                                <span class=\"label-login errors\">\u041F\u043E\u0436\u0430\u043B\u0443\u0439\u0441\u0442\u0430 \u0438\u0441\u043F\u0440\u0430\u0432\u044C\u0442\u0435 \u0443\u043A\u0430\u0437\u0430\u043D\u043D\u044B\u0435 \u043E\u0448\u0438\u0431\u043A\u0438:\n                                </span>\n                        <ul>\n                            <li v-for=\"error in errors\"\n                                class=\"label-login error\">{{ error }}\n                            </li>\n                        </ul>\n                        </p>\n\n                    </div>\n                    <div class=\"login__checkbox\">\n                        <div class=\"checkbox-wrap reg\">\n                            <input id=\"confirm\"\n                                   class=\"checkbox\"\n                                   type=\"checkbox\"\n                                   required/>\n                            <label for=\"confirm\"><span>\u042F \u043F\u0440\u043E\u0447\u0438\u0442\u0430\u043B \u0438 \u0441\u043E\u0433\u043B\u0430\u0441\u0435\u043D \u0441</span> <span\n                                        class=\"mobile\">\u042F \u0441\u043E\u0433\u043B\u0430\u0441\u0435\u043D \u0441</span> &nbsp;<a href=\"#\">\u041F\u043E\u043B\u0438\u0442\u0438\u043A\u043E\u0439\n                                    \u043A\u043E\u043D\u0444\u0438\u0434\u0435\u043D\u0446\u0438\u0430\u043B\u044C\u043D\u043E\u0441\u0442\u0438</a></label>\n                        </div>\n                    </div>\n                    <div class=\"login__content-bottom\">\n                        <div class=\"login__btn\">\n                            <input type=\"submit\"\n                                   class=\"btn btn-gold_fill2 btn-normal btn_center\"\n                                   value=\"\u0417\u0430\u0440\u0435\u0433\u0438\u0441\u0442\u0440\u0438\u0440\u043E\u0432\u0430\u0442\u044C\u0441\u044F\">\n                        </div>\n                        <a href=\"#\"\n                           class=\"login__link\"\n                           v-on:click.prevent=\"signin\">\u0415\u0441\u0442\u044C \u0430\u043A\u043A\u0430\u0443\u043D\u0442?\n                            <u>\u0412\u043E\u0439\u0434\u0438\u0442\u0435</u>\n                        </a>\n                    </div>\n                </form>\n\n            </div>\n        </div>\n    </div>\n                \n               "
      });
    })(window);

}((this.BX[''] = this.BX[''] || {})));
//# sourceMappingURL=auth.bundle.js.map
