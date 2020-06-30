<template>
	<v-card class="boost-me-card">
		<v-container class="boost-me">
			<div class="align-center">
				<v-icon size="50">mdi-currency-eur</v-icon>
				<h2>{{ price }} (${{ priceUSD }})</h2>
			</div>
			<v-dialog v-model="dialog" width="940">
				<template v-slot:activator="{ on }">
					<v-btn class="ma-2" outlined @click="order()" v-on="on">
						<v-icon left>mdi-shopping-outline</v-icon>Boost Me
					</v-btn>
				</template>
				<!-- Entire stepper -->
				<post-order @closeDialog="closeDialog" />
			</v-dialog>
			<!-- end of Test -->
		</v-container>
	</v-card>
</template>

<script>
export default {
	data() {
		return {
			dialog: false,
			// Default exchange rate, to be changed by API call
			exchangeRate: 1.1003
		};
	},
	computed: {
		price() {
			return this.$store.getters["league/price"].toFixed(2);
		},
		priceUSD() {
			return (this.price * this.exchangeRate).toFixed(2);
		}
	},
	methods: {
		order() {
			this.dialog = true;
		},
		closeDialog() {
			// this.on=false
		}
	},
	mounted() {
		this.$axios
			.get("https://api.exchangeratesapi.io/latest?symbols=USD")
			.then(response => {
				this.exchangeRate = response.data.rates.USD;
			});
	}
};
</script>

<style scoped>
.boost-me-card {
	background: linear-gradient(to right, #673ab7, #9c27b0);
}

.boost-me {
	display: flex;
	flex-direction: row;
	align-items: center;
	justify-content: space-between;
	flex: 1;
}

.align-center {
	display: flex;
	flex-direction: row;
	justify-content: center;
	align-items: center;
}
</style>
