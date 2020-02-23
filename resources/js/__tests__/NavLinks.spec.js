import {
    shallowMount
} from '@vue/test-utils';

import NavLinks from '../components/NavLinks.vue';


describe('NavLinks', () => {
    it('renders', () => {
        let wrapper = shallowMount(NavLinks);
        expect(wrapper.text()).toContain('Boosting');
        expect(wrapper).toMatchSnapshot();
    });
});
