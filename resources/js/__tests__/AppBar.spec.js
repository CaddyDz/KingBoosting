import {
    shallowMount
} from '@vue/test-utils';

import AppBar from '../components/AppBarComponent.vue';

describe('AppBar', () => {
    it('renders', () => {
        let wrapper = shallowMount(AppBar, {
            stubs: ['nav-links', 'LoginComponent']
        });
        expect(wrapper).toMatchSnapshot();
    });
});
