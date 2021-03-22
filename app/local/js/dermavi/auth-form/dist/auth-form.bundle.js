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
        ui_vue.Vue.component('bx-auth-form', {
          /**
           * @emits 'sendEvent`' {text: string}
           */

          /**
           * @listens 'onModuleNameSomeComponentEvent' {} (global)
           */
          props: {
            method: {
              default: 'post'
            },
            action: {
              default: ''
            }
          },
          data: function data() {
            return {};
          },
          created: function created() {// BX.Vue.event.$on('onModuleNameSomeComponentEvent', this.onDoSomething);
          },
          beforeDestroy: function beforeDestroy() {// BX.Vue.event.$off('onModuleNameSomeComponentEvent', this.onDoSomething);
          },
          methods: {
            post: function post(event) {}
          },
          computed: {
            localize: function localize(state) {// return Vue.getFilteredPhrases('BX_MODULE-NAME_COMPONENT-PREFIX_');
            }
          },
          template: "\n            <form :method=\"post\"\n                  :action=\"action\"\n                  v-on:submit.prevent=\"submit\">\n                  \n            <div class=\"login__btn-next\">\n                <input type=\"submit\"\n                       class=\"btn btn-brown btn-normal btn_center\">\u0414\u0430\u043B\u0435\u0435\n                <i class=\"icon icon-arrow_right\"></i>\n                </input>\n            </div>\n            </form>\n              "
        });

}((this.BX[''] = this.BX[''] || {}),BX));
//# sourceMappingURL=auth-form.bundle.js.map
