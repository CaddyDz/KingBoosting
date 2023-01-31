<template>
	<v-row>
		<v-col cols="12" sm="6" md="7">
			<slider title="Select Your Number Of Games" :showQueue="false" :image="image" :max="30" @amountChange="changePrice" />
		</v-col>
		<v-col cols="6" md="5">
			<checkout :options="options" />
			<boost-me />
		</v-col>
	</v-row>
</template>

<script>
import options from "~/assets/js/unrated-matches/options";

export default {
	layout: "order",
	data: () => ({
		image: "/img/divisions/valorant/unranked.png",
		options: options,
	}),
	computed: {
		matches() {
			// Get the amount of matches selected
			return this.$store.state.slider.amount;
		},
	},
	methods: {
		changePrice() {
			// Static price for unrated matches
			this.$store.commit(
				"price/changePrice",
				4 - (4 * this.matches) / 100
			);
		},
		sendOrder(token) {
			// Construct the purchase string
			// Purchase here is "current (rank & division) to desired (rank & division)"
			let purchase = `${this.matches} Unrated Matches`;
			// Get all data from store and post them to DB
			this.$axios
				.post("orders", {
					purchase,
					service: "Valorant Unrated Matches",
					server: "EU-West",
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
		// Default amount of matches selected
		this.$store.commit("slider/changeAmount", 5);
		this.changePrice();
		this.$root.$on("sendOrder", (token) => this.sendOrder(token));
	},
	beforeDestroy() {
		// So price doesn't get multiplied, because there might be no wins in other services
		this.$store.commit("slider/changeAmount", 1);
		this.$root.$off("sendOrder");
	},
};
</script>
