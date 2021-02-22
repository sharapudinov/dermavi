this.BX = this.BX || {};
(function (exports,ui_vue) {
    'use strict';

    /**
     * Module name
     * Some Component Vue component
     *
     * @package bitrix
     * @subpackage moduleName
     * @copyright 2001-2019 Bitrix
     */
    ui_vue.Vue.component('bx-sign-in', {
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
      template: "\n                <div class=\"login__right\"\n         v-bind:style=\"{display:signin_display}\">\n        <div class=\" login-logo__mobile\">\n            <a href=\"#\"\n               class=\"login-btn__back mobile-none\">\n                <i class=\"icon icon-arrow_left\"></i>\n            </a>\n            <div class=\"login-logo__mobile-img-wrap\">\n                <img src=\"/img/Logo-log_mob.svg\"\n                     alt=\"\"\n                     class=\"login-logo__mobile-img\">\n            </div>\n        </div>\n        <div class=\"login__top\">\n            <a href=\"#\" class=\"login__top-item active\">\u0412\u0445\u043E\u0434</a>\n            <div class=\"login__top-logo-wrap\">\n                <img src=\"/img/logo_footer.svg\"\n                     alt=\"\"\n                     class=\"login__logo-img\">\n            </div>\n            <a href=\"#\"\n               class=\"login__top-item\"\n               v-on:click.prevent=\"signup\">\u0420\u0435\u0433\u0438\u0441\u0442\u0440\u0430\u0446\u0438\u044F\n            </a>\n        </div>\n        <div class=\"login__content\">\n            <div class=\"login__title mobile-none\">\u0412\u0445\u043E\u0434 \u0432 \u0430\u043A\u043A\u0430\u0443\u043D\u0442</div>\n            <form v-on:submit.prevent=\"submit\" id=\"signin\"\n                  action=\"/api/internal/user/auth/signin/\"\n                  data-login=\"signin_email\"\n                  data-password=\"signin_password\">\n                <div class=\"login__form-group\">\n                  <bx-email-field/>\n                <bx-password-field/>\n                </div>\n                <div class=\"login__btn\">\n                    <input type=\"submit\"\n                           class=\"btn btn-gold_fill2 btn-normal btn_full-width btn_center\"\n                           value=\"\u0412\u043E\u0439\u0442\u0438\">\n                </div>\n            </form>\n            <div class=\"login__links\">\n                <a v-on:click.prevent=\"forgot_password\"\n                   class=\"login__forget\">\n                    <u>\u0417\u0430\u0431\u044B\u043B\u0438 \u043F\u0430\u0440\u043E\u043B\u044C?</u>\n                </a>\n                <a href=\"#\"\n                   class=\"login__new-user\"\n                   v-on:click.prevent=\"signup\">\u041D\u0435\u0442 \u0430\u043A\u043A\u0430\u0443\u043D\u0442\u0430?\n                    <u>\u0417\u0430\u0440\u0435\u0433\u0438\u0441\u0442\u0440\u0438\u0440\u0443\u0439\u0442\u0435\u0441\u044C</u>\n                </a>\n            </div>\n        </div>\n    </div>\n               "
    });

}((this.BX[''] = this.BX[''] || {}),BX));
//# sourceMappingURL=sign-in.bundle.js.map
