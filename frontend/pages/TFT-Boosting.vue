<template>
	<div>
		<div
			class="slider"
			fluid
			:style="{background: 'url(/img/services/league-boosting-top-bg.png) top/cover'}"
		>
			<v-row justify="center" align="center">
				<v-col md="8" style="text-align: center;">
					<p>TFT Boosting</p>
					<br />TFT Boosting allows the client to get any ranked rewards in Teamfight Tactics, with the help of professional boosters in the form of a high-quality service.
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
	name: "league-boosting",
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
