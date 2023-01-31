<template>
	<v-row>
		<v-col cols="12" sm="6" md="7">
			<v-card raised>
				<v-container>
					<v-row>
						<v-col md="3">1. Champion</v-col>
						<v-col md="3">2. Current tier</v-col>
						<v-col md="3">3. Desired tier</v-col>
						<v-col md="3">4. Token remaining</v-col>
					</v-row>
					<v-row justify="center" align="center">
						<v-col>
							<v-avatar size="128">
								<v-img :src="`https://ddragon.leagueoflegends.com/cdn/8.18.2/img/champion/${champion}.png`"></v-img>
							</v-avatar>
						</v-col>
						<v-col>
							<v-avatar size="128">
								<v-img :src="`/img/tiers/${currentTier}.png`"></v-img>
							</v-avatar>
						</v-col>
						<v-col>
							<v-avatar size="128">
								<v-img :src="`/img/tiers/${desiredTier}.png`"></v-img>
							</v-avatar>
						</v-col>
						<v-col v-if="currentTier >= 5">
							<span v-text="token" class="text-h1 purple--text"></span>
						</v-col>
					</v-row>
					<v-row>
						<v-col>
							<v-select v-model="champion" :items="champions" item-text="name" item-value="name"></v-select>
						</v-col>
						<v-col>
							<v-select v-model="currentTier" :items="[0,1,2,3,4,5,6]"></v-select>
						</v-col>
						<v-col>
							<v-select v-model="desiredTier" :items="tiers" item-value="level" item-text="level"></v-select>
						</v-col>
						<v-col v-if="currentTier >= 5">
							<v-select v-model="token" :items="tokens"></v-select>
						</v-col>
					</v-row>
				</v-container>
			</v-card>
		</v-col>
		<v-col cols="6" md="5">
			<checkout :options="options" :showTitle="false" />
			<boost-me />
		</v-col>
	</v-row>
</template>

<script>
import champions from "~/assets/js/champions";
import tiers from "~/assets/js/champion-mastery/tiers";
import options from "~/assets/js/champion-mastery/options";

export default {
	layout: "order",
	data: () => ({
		champions: champions,
		champion: "Aatrox",
		currentTier: 5,
		desiredTier: 7,
		token: 5,
		maxTokens: 5,
		tiers: tiers,
		options: options,
	}),
	computed: {
		tokens() {
			// + 1 because lodash range behaves that way
			if (this.currentTier == 5 && this.desiredTier == 7) {
				return _.range(4, 6);
			}
			return _.range(this.startingRange, this.maxTokens + 1);
		},
	},
	watch: {
		currentTier() {
			this.changeMaxTokensRemainingAvailable();
			this.changePrice();
		},
		desiredTier() {
			this.changeMaxTokensRemainingAvailable();
			this.changePrice();
		},
		token() {
			this.changePrice();
		},
	},
	methods: {
		changeMaxTokensRemainingAvailable() {
			if (this.currentTier == 5 && this.desiredTier == 6) {
				this.maxTokens = 2;
				this.token = 2;
			} else if (this.currentTier == 6 && this.desiredTier == 7) {
				this.maxTokens = 3;
				this.token = 3;
			} else if (this.currentTier == 5 && this.desiredTier == 7) {
				// We need to change the tokens array to only include either 4 or 5
				this.token = 5;
			} else {
				this.token = 1;
			}
		},
		changePrice() {
			let filteredTiers = _.filter(this.tiers, (tier) =>
				// This is a whereIn replacement
				_.range(this.desiredTier, this.currentTier).includes(tier.level)
			);
			// Sum up their prices using JS reduce
			let total = filteredTiers.reduce((sum, tier) => {
				return sum + tier.price;
			}, 0);
			if (this.currentTier == 5 && this.desiredTier == 7) {
				total = 10;
			}
			// Load price of (get it from currently selected desired division)
			this.$store.commit("price/changePrice", total * this.token);
		},
		sendOrder(token) {
			// Construct the purchase string
			// Purchase here is "current (tier & division) to desired (tier & division)"
			let purchase = `Tier ${this.currentTier} to Tier ${this.desiredTier}`;
			// Push the selected champion to the order's checkout options
			this.$store.commit("checkout/addOption", this.champion);
			// Get all data from store and post them to DB
			this.$axios
				.post("orders", {
					purchase,
					service: "Champion Mastery Boosting",
					server: this.$store.state.league.server,
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
					}).then((value) => {
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
		this.changePrice();
		this.$root.$on("sendOrder", (token) => this.sendOrder(token));
	},
	beforeDestroy() {
		this.$root.$off("sendOrder");
	},
};
</script>
