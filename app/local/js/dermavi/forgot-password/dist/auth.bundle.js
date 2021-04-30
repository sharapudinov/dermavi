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
        template: "\n                <div class=\"login__right\"\n         v-bind:style=\"{display:forgot_password_display}\">\n        <div class=\"login-logo__mobile open\">\n            <a href=\"#\"\n               class=\"login-btn__back\">\n                <i class=\"icon icon-arrow_left\"></i>\n            </a>\n            <div class=\"login-logo__mobile-img-wrap\">\n                <img src=\"img/Logo-log_mob.svg\"\n                     alt=\"\"\n                     class=\"login-logo__mobile-img\">\n            </div>\n        </div>\n        <div class=\"login__top mobile-none\">\n            <a href=\"#\"\n               v-on:click=\"signin\"\n               class=\"login__top-item active\">\u0412\u0445\u043E\u0434</a>\n            <div class=\"login__top-logo-wrap\">\n                <img src=\"/img/logo_footer.svg\"\n                     alt=\"\"\n                     class=\"login__logo-img\">\n            </div>\n            <a href=\"#\"\n               v-on:click=\"signup\"\n               class=\"login__top-item\">\u0420\u0435\u0433\u0438\u0441\u0442\u0440\u0430\u0446\u0438\u044F</a>\n        </div>\n        <div class=\"login__content\">\n            <a href=\"#\"\n               v-on:click=\"signin\"\n               class=\"login__back\">\n                <i class=\"icon icon-arrow_left\"></i>\u0412\u0435\u0440\u043D\u0443\u0442\u044C\u0441\u044F \u043A\u043E \u0432\u0445\u043E\u0434\u0443\n            </a>\n            <div class=\"login__title\">\u041D\u0435 \u0443\u0434\u0430\u043B\u043E\u0441\u044C \u0432\u043E\u0439\u0442\u0438?</div>\n            <form method=\"post\"\n                  action=\"\"\n                  v-on:submit.prevent=\"submit\"/>\n            <div class=\"login__form-group\">\n                <bx-email-field/>\n            </div>\n            <div class=\"login__btn-next\">\n                <input type=\"submit\"\n                       class=\"btn btn-brown btn-normal btn_center\">\u0414\u0430\u043B\u0435\u0435\n                <i class=\"icon icon-arrow_right\"></i>\n                </input>\n            </div>\n            </form>\n\n        </div>\n    </div>\n               "
      });
    })(window);

}((this.BX[''] = this.BX[''] || {})));
//# sourceMappingURL=auth.bundle.js.map
