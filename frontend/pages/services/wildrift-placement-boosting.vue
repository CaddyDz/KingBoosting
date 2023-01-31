<template>
	<v-row>
		<v-col cols="12" sm="6" md="7">
			<Current title="Season End Rank" description="Please select your Season End Rank and Division" :tiers="ranks" showServerSelection @divisionChanged="changePrice" />
			<slider title step :showQueue="false" :max="3" />
		</v-col>
		<v-col cols="6" md="5">
			<checkout :options="options" />
			<boost-me />
		</v-col>
	</v-row>
</template>

<script>
import ranks from "~/assets/js/valorant-placement-boosting/ranks";
import options from "~/assets/js/valorant-placement-boosting/options";

export default {
	layout: "order",
	data: () => ({
		ranks: ranks,
		options: options,
	}),
	computed: {
		tier() {
			return this.$store.state.current.tier;
		},
		division() {
			return this.$store.state.current.division;
		},
	},
	methods: {
		changePrice() {
			this.$store.commit("price/changePrice", this.division.price);
		},
		sendOrder(token) {
			// Get selected number of wins
			let games = this.$store.state.slider.amount;
			// Construct the purchase string
			// Purchase here is "2 games in Gold IV"
			let purchase = `${games} Placement matches in ${this.tier.name} ${this.division.name}`;
			// Get all data from store and post them to DB
			this.$axios
				.post("orders", {
					purchase,
					service: "Wild Rift Placement Matches",
					server: this.$store.state.current.server,
					options: this.$store.state.checkout.options,
					price: this.$store.getters["price/price"],
					discountCode: this.$store.state.checkout.discountCode,
					nickname: this.$store.state.order.nickname,
					booster: this.$store.state.order.booster,
					comment: this.$store.state.order.comment,
					token,
				})
				.then((response) => {
					this.$notify(response.data.message, "success");
					this.$alert({
						title: "Order Confirmed!",
						text: response.data.message,
						icon: "success",
						button: "Take me to my order",
					}).then(() => {
						window.location = `${process.env.HOST_URL}/resources/orders/${response.data.order_id}`;
					});
					// Actually just close the dialog, semantics ¯\_(ツ)_/¯
					this.cancel();
				})
				.catch((errors) => {
					this.$notify(
						errors.response.data.message,
						"error",
						errors.response.data.errors
					);
				});
		},
	},
	mounted() {
		this.$store.commit("slider/changeAmount", 3);
		this.changePrice();
		this.$root.$on("sendOrder", (token) => this.sendOrder(token));
	},
	beforeDestroy() {
		this.$store.commit("slider/changeAmount", 1);
		this.$root.$off("sendOrder");
	},
};
</script>
