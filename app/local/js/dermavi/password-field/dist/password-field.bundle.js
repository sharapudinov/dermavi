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
        ui_vue.Vue.component('bx-password-field', {
          /**
           * @emits 'sendEvent`' {text: string}
           */

          /**
           * @listens 'onModuleNameSomeComponentEvent' {} (global)
           */
          props: {
            name: {
              default: 'password'
            },
            placeholder: {
              default: 'Пароль'
            },
            label: {
              default: 'Пароль'
            }
          },
          data: function data() {
            return {
              type: 'password'
            };
          },
          created: function created() {// BX.Vue.event.$on('onModuleNameSomeComponentEvent', this.onDoSomething);
          },
          beforeDestroy: function beforeDestroy() {// BX.Vue.event.$off('onModuleNameSomeComponentEvent', this.onDoSomething);
          },
          methods: {
            show_password: function show_password(event) {
              this.type = 'text';
            },
            hide_password: function hide_password(event) {
              this.type = 'password';
            }
          },
          computed: {
            localize: function localize(state) {// return BX.Vue.getFilteredPhrases('BX_MODULE-NAME_COMPONENT-PREFIX_');
            }
          },
          template: "\n                    <div class=\"login__form\">\n                        <label for=\"name\"\n                               class=\"label label-login\">{{label}}\n                        </label>\n                        <input  :id=\"name\"\n                               :name=\"name\"\n                               :type=\"type\"\n                               :placeholder=\"placeholder\"\n                                class=\"input input-brown input-normal input-border_bottom-gold input-full_width input-small_padding\"\n                                required/>\n                        <div class=\"form__btn form__btn-password\">\n                            <div class=\"form__icon\">\n                                <i class=\"icon icon-eye_off\"\n                                   v-on:mousedown=\"show_password\"\n                                   v-on:mouseup=\"hide_password\"></i>\n                            </div>\n                        </div>\n                    </div>\n"
        });

}((this.BX[''] = this.BX[''] || {}),BX));
//# sourceMappingURL=password-field.bundle.js.map
