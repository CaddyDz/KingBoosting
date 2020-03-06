<template>
    <div>
        <div class="slider">
			<v-row justify="center" align="center">
				<v-col md="8" style="text-align: center;">
					<p>{{ service.name }}</p>
					<br />
					{{ service.description }}
				</v-col>
			</v-row>
            <services-slider :service="service" @slideChange="slideChange($event)"/>
        </div>
        <v-container>
            <v-row>
                <v-col md="8">
                    <nuxt-child :slug="service"></nuxt-child>
                </v-col>
                <v-col md="4"></v-col>
            </v-row>
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
        slideChange(e){
            this.slug = e;
            this.getService();
        },
		async getService() {
            // console.log(`i will call with ${this.slug}`)
			this.$axios.get(`/services/${this.slug}`).then(Response => {
                this.service = Response.data;
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

<style scoped>
    .slider{}
</style>