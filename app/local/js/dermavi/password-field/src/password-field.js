/**
 * Module name
 * Some Component Vue component
 *
 * @package bitrix
 * @subpackage moduleName
 * @copyright 2001-2019 Bitrix
 */
import {Vue} from 'ui.vue';

Vue.component('bx-password-field',
        {
            /**
             * @emits 'sendEvent`' {text: string}
             */

            /**
             * @listens 'onModuleNameSomeComponentEvent' {} (global)
             */

            props:
                    {
                        name: {default: 'password'},
                        placeholder: {default: 'Пароль'},
                        label: {default: 'Пароль'},
                    },

            data() {
                return {
                    type: 'password',
                };
            },

            created() {
                // BX.Vue.event.$on('onModuleNameSomeComponentEvent', this.onDoSomething);
            },

            beforeDestroy() {
                // BX.Vue.event.$off('onModuleNameSomeComponentEvent', this.onDoSomething);

            },

            methods:
                    {
                        show_password(event) {
                            this.type = 'text';

                        },
                        hide_password(event) {
                            this.type = 'password';
                        },

                    },


            computed:
                    {
                        localize(state) {
                            // return BX.Vue.getFilteredPhrases('BX_MODULE-NAME_COMPONENT-PREFIX_');
                        },
                    },

            template: `
                    <div class="login__form">
                        <label for="name"
                               class="label label-login">{{label}}
                        </label>
                        <input  :id="name"
                               :name="name"
                               :type="type"
                               :placeholder="placeholder"
                                class="input input-brown input-normal input-border_bottom-gold input-full_width input-small_padding"
                                required/>
                        <div class="form__btn form__btn-password">
                            <div class="form__icon">
                                <i class="icon icon-eye_off"
                                   v-on:mousedown="show_password"
                                   v-on:mouseup="hide_password"></i>
                            </div>
                        </div>
                    </div>
`,
        });

