/**
 * Module name
 * Some Component Vue component
 *
 * @package bitrix
 * @subpackage moduleName
 * @copyright 2001-2019 Bitrix
 */
import {Vue} from 'ui.vue';

Vue.component('bx-auth-form',
        {
            /**
             * @emits 'sendEvent`' {text: string}
             */

            /**
             * @listens 'onModuleNameSomeComponentEvent' {} (global)
             */

            props:
                    {
                        method: {default: 'post'},
                        action: {default: ''},
                    },

            data() {
                return {};
            },

            created() {
                // BX.Vue.event.$on('onModuleNameSomeComponentEvent', this.onDoSomething);
            },

            beforeDestroy() {
                // BX.Vue.event.$off('onModuleNameSomeComponentEvent', this.onDoSomething);

            },

            methods:
                    {
                        post(event) {

                        },
                    },


            computed:
                    {
                        localize(state) {
                            // return Vue.getFilteredPhrases('BX_MODULE-NAME_COMPONENT-PREFIX_');
                        },
                    },

            template: `
            <form :method="post"
                  :action="action"
                  v-on:submit.prevent="submit">
                  
            <div class="login__btn-next">
                <input type="submit"
                       class="btn btn-brown btn-normal btn_center">Далее
                <i class="icon icon-arrow_right"></i>
                </input>
            </div>
            </form>
              `,
        });
