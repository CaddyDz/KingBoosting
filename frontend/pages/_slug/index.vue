<template>
	<div>
		<v-container fluid :style="{background: `url(${service.bg_img}) top/cover`}">
			<div id="particles"></div>
			<v-row justify="center" align="center">
				<v-col md="8" style="text-align: center;">
					{{ kind }}
					<br />
					<p class="display-2 font-weight-bold">{{ service.name }}</p>
					<br />
					{{ service.description }}
				</v-col>
			</v-row>
			<services-slider :service="service"></services-slider>
		</v-container>
		<div class="checkout">
			<v-container>
				<v-row>
					<v-col md="9">
						<simple :service="service"></simple>
						<!-- <checkout :service="service"></checkout> -->
					</v-col>
					<v-col md="3">Verified Customer Reviews</v-col>
				</v-row>
			</v-container>
		</div>
	</div>
</template>

<script>
export default {
	validate({ params }) {
		// this.slug = params.slug;
		// TODO: Make an Ajax request to API to validate the existing of the service
		// see https://nuxtjs.org/api/pages-validate
		return true;
		// Must be a number
		// validateParam = await this.currentCategory.some(
		// category => category === params.category
		// );
		// axios
		// 	.get(`https://kingboosting.dev/${params.slug}`)
		// 	.then(response => resolve())
		// 	.catch(false);
	},
	data() {
		return {
			kind: "",
			service: {},
			slug: ""
		};
	},
	methods: {
		async getService() {
			const service = await this.$axios.$get(`/services${this.slug}`);
			this.service = service;
		}
	},
	mounted() {
		this.slug = location.pathname;
		// Get the service from the API
		this.getService();
		// axios.get(`/services/${this.slug}`);
		// // particlesJS.load("particles", "/particlesjs-config.json");
		// axios
		// 	.get(`/services/${this.service.kind_id}/kind`)
		// 	.then(response => (this.kind = response.data));
	}
};
</script>
