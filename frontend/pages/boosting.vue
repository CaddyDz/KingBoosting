<template>
    <div>
        <services-slider :service="service" @slideChange="slug = $event"/>
        <v-container>
            <nuxt-child :slug="slug"></nuxt-child>
        </v-container>
    </div>
</template>


<script>

export default {
    data () {
        return {
            slug:"",
            service: {},
        }
    },
    methods: {
		async getService() {
			this.$axios.get(`/services/${this.slug}`).then(Response => {
                this.service = Response;
            })
            .catch(error =>{
                console.log(error)
            })
		}
	},
    mounted(){
        this.slug = this.$route.params.service;
        this.getService();
    }
}
</script>