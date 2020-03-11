<template>
	<div>
		<div class="slider" fluid :style="{background: `url(${service.bg_img}) top/cover`}">
			<v-row justify="center" align="center">
				<v-col md="8" style="text-align: center;">
					<p>{{ service.name }}</p>
					<br />
					{{ service.description }}
				</v-col>
			</v-row>
			<services-slider :service="service" />
		</div>
		<v-container>
			<v-row>
				<v-col md="8">
					<stepper />
				</v-col>
				<v-col md="4"></v-col>
			</v-row>
		</v-container>
		<how-to-buy />
	</div>
</template>

<script>
import Stepper from "~/components/Stepper";

export default {
	name: "service",
	components: {
		Stepper
	},
	data() {
		return {
			slug: "",
			service: {}
		};
	},
	methods: {
		slideChange(e) {
			this.slug = "/" + e;
			this.getService();
		},
		getService() {
			this.$axios
				.get(`/services${this.slug}`)
				.then(response => {
					this.service = response.data;
				})
				.catch(error => {
					this.$store.commit("notification/open", {
						text: this.$i18n.t("Something went wrong"),
						mode: "error"
					});
				});
		}
	},
	mounted() {
		this.slug = this.$route.path;
		this.getService();
	}
};
</script>
