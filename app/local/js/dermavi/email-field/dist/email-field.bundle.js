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
        ui_vue.Vue.component('bx-email-field', {
          /**
           * @emits 'sendEvent`' {text: string}
           */

          /**
           * @listens 'onModuleNameSomeComponentEvent' {} (global)
           */
          props: {
            name: {
              default: 'email'
            },
            placeholder: {
              default: 'EMAIL'
            },
            label: {
              default: 'Адрес эл. почты'
            }
          },
          data: function data() {
            return {
              value: ''
            };
          },
          mounted: function mounted() {},
          beforeDestroy: function beforeDestroy() {// BX.Vue.event.$off('onModuleNameSomeComponentEvent', this.onDoSomething);
          },
          methods: {},
          computed: {
            localize: function localize(state) {// return BX.Vue.getFilteredPhrases('BX_MODULE-NAME_COMPONENT-PREFIX_');
            }
          },
          template: "<div class=\"login__form\">\n                        <label :for=\"name\"\n                               class=\"label label-login\">{{label}}</label>\n                        <input type=\"text\"\n                               :name=\"name\"\n                               class=\"input input-brown input-normal input-border_bottom-gold input-full_width input-small_padding\"\n                               :placeholder=\"placeholder\"\n                               pattern='.+@.+\\..+'\n                               required/>\n                    </div>\n"
        });

}((this.BX[''] = this.BX[''] || {}),BX));
//# sourceMappingURL=email-field.bundle.js.map
