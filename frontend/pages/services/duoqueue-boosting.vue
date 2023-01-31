<template>
	<v-row>
		<v-col cols="12" sm="6" md="7">
			<Current title="Current League" description="Please select your Current Rank and Division" :tiers="tiers" mmrs suffix="Current LP" showPointsSelection lpTooltip="Your current Rank Rating amount." @divisionChanged="changePrice" @lpchanged="changePrice" @mmrchanged="changePrice" />
			<Desired title="Desired League" description="Please select your Desired Rank and Division" :tiers="tiers" @divisionChanged="changePrice" />
		</v-col>
		<v-col cols="6" md="5">
			<checkout :options="options" />
			<boost-me />
		</v-col>
	</v-row>
</template>

<script>
import tiers from "~/assets/js/division-boosting/tiers";
import options from "~/assets/js/duoqueue-boosting/options";

export default {
	layout: "order",
	data: () => ({
		tiers: tiers,
		options: options,
	}),
	computed: {
		currentTier() {
			return this.$store.state.current.tier;
		},
		currentDivision() {
			return this.$store.state.current.division;
		},
		desiredTier() {
			return this.$store.state.desired.tier;
		},
		desiredDivision() {
			return this.$store.state.desired.division;
		},
		mmr() {
			return this.$store.state.current.mmr;
		},
		lp() {
			return this.$store.state.current.lp;
		},
	},
	methods: {
		changePrice() {
			// Flatten and merge all divisions by id and price
			// Add the Master+ tier price as the last one
			let all_divisions_prices = _.flatten(
				_.map(this.tiers, (tier) => {
					if (tier.divisions) {
						return _.map(tier.divisions, (division) => ({
							id: division.id,
							price: division.price,
							mmr: _.find(tier.mmrs, ["range", this.mmr.range])
								.price,
						}));
					} else {
						return { id: 25, price: tier.price };
					}
				})
			);
			let fromId;
			if (this.currentTier.divisions) {
				fromId = this.currentDivision.id;
			} else {
				fromId = 25;
			}
			let toId;
			if (this.desiredTier.divisions) {
				toId = this.desiredDivision.id;
			} else {
				toId = 25;
			}
			let divisions_in_between = _.filter(
				all_divisions_prices,
				(division) =>
					// This is a whereIn replacement
					this.$range(fromId, toId).includes(division.id)
			);
			// Sum up their prices using JS reduce
			let total = divisions_in_between.reduce((sum, division) => {
				return sum + division.price + division.mmr;
			}, 0);
			if (this.lp) {
				// Load price of (get it from currently selected desired division)
				this.$store.commit(
					"price/changePrice",
					(total - this.lp.price) * 1.5
				);
			}
		},
		sendOrder(token) {
			// Gather LP
			this.$store.commit(
				"checkout/addOption",
				// We shouldn't send the value of the LP, but the rank name and suffix it with LP
				this.lp.label + " LP"
			);
			// Gather MMR
			this.$store.commit(
				"checkout/addOption",
				// Same thing goes for MMR as stated above
				`+${this.mmr.range} LP`
			);
			// Construct the purchase string
			// Purchase here is "current (tier & division) to desired (tier & division)"
			let purchase = `${this.currentTier.name} ${this.currentDivision.name} to ${this.desiredTier.name} ${this.desiredDivision.name}`;
			// Gather game queue (solo/duo)
			this.$store.commit(
				"checkout/addOption",
				this.$store.state.desired.queue
			);
			// Get all data from store and post them to DB
			this.$axios
				.post("orders", {
					purchase,
					service: "DuoQueue Division Boosting",
					server: this.$store.state.desired.server,
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
		this.$root.$on("sendOrder", (token) => this.sendOrder(token));
	},
	beforeDestroy() {
		this.$root.$off("sendOrder");
	},
};
</script>
