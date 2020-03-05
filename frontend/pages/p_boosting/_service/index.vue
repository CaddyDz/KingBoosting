<template>
    <div>
        <services-slider :service="service"/>
        <v-container>
            <nuxt-child >
                <stepper :service="service"/>
            </nuxt-child>
        </v-container>
    </div>
</template>


<script>
import ServicesSlider from '~/components/ServicesSlider.vue'
import Stepper from '~/components/Stepper'

export default {
    data () {
        return {
            slug:"",
            service: {},
        }
    },
    components:{
        Stepper,
        ServicesSlider
    },
    methods: {
		async getService() {
			const service = await this.$axios.$get(`/services${this.slug}`);
			this.service = service;
		}
	},
    mounted(){
        this.slug = location.pathname;
        this.getService();
    }
}
</script>