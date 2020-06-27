<template>
	<div>
		<div
			class="slider"
			fluid
			:style="{background: 'url(/img/services/regular-boosting-top-bg.png) top/cover'}"
		>
			<v-row justify="center" align="center">
				<v-col md="8" style="text-align: center;">
					<p>Regular Boosting</p>
					<br />Regular Boosting allows our customers to buy the desired number of ranked wins. All you have to do is choose how many ranked wins you would like to have on.
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
	name: "regular-boosting",
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
				.get(`/services${this.slug}`) // this.slug already contains leading /
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
