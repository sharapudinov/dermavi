/**
 * Module name
 * Some Component Vue component
 *
 * @package bitrix
 * @subpackage moduleName
 * @copyright 2001-2019 Bitrix
 */
import {Vue} from 'ui.vue';

Vue.component('bx-email-field',
        {
            /**
             * @emits 'sendEvent`' {text: string}
             */

            /**
             * @listens 'onModuleNameSomeComponentEvent' {} (global)
             */

            props:
                    {
                        name: {default: 'email'},
                        placeholder: {default: 'EMAIL'},
                        label: {default: 'Адрес эл. почты'},
                    },

            data() {
                return {
                    value: '',
                };
            },

            mounted() {

            },

            beforeDestroy() {
                // BX.Vue.event.$off('onModuleNameSomeComponentEvent', this.onDoSomething);

            },

            methods:
                    {
                    },


            computed:
                    {
                        localize(state) {
                            // return BX.Vue.getFilteredPhrases('BX_MODULE-NAME_COMPONENT-PREFIX_');
                        },
                    },

            template: `<div class="login__form">
                        <label :for="name"
                               class="label label-login">{{label}}</label>
                        <input type="text"
                               :name="name"
                               class="input input-brown input-normal input-border_bottom-gold input-full_width input-small_padding"
                               :placeholder="placeholder"
                               pattern='.+@.+\\..+'
                               required/>
                    </div>
`,
        });

