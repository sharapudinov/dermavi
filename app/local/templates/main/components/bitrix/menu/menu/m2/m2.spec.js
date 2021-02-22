import { mount } from '@vue/test-utils';
import M2 from './m2';

describe('Spec M2', function() {
    it('mounts', () => {
        const wrapper = mount(M2);
        expect(wrapper)
            .toBeTruthy();
    });
});
