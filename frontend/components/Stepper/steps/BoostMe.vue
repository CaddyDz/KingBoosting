<template>
	<v-card class="boost-me-card">
		<v-container class="boost-me">
			<div class="align-center">
				<v-icon size="50">mdi-currency-eur</v-icon>
				<h2>{{ price }} (${{ priceUSD }})</h2>
			</div>
			<v-dialog v-model="dialog" width="940">
				<template v-slot:activator="{ on }">
					<v-btn class="ma-2" outlined @click="dialog = true" v-on="on">
						<v-icon left>mdi-shopping-outline</v-icon>Boost Me
					</v-btn>
				</template>
				<order-dialog @closeDialog="dialog = false" />
			</v-dialog>
		</v-container>
	</v-card>
</template>

<script>
export default {
	data: () => ( {
			dialog: false,
			// Default exchange rate, to be changed by API call
			exchangeRate: 1.1003
	}),
	computed: {
		price() {
			return this.$store.getters['price/price'].toFixed(2);
		},
		priceUSD() {
			return (this.price * this.exchangeRate).toFixed(2);
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
