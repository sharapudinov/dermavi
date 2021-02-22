import M2 from './m2.vue';

export default {
    title: 'M2',
    component: M2,
};

export const Default = (args, {argTypes}) => ({
    props: Object.keys(argTypes),
    components: { M2 },
    template: `<m2 v-bind="$props" />`,
});
