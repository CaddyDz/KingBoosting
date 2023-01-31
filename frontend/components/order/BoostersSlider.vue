<template>
	<v-card raised class="mb-4">
		<v-slider v-model="amount" thumb-label="always" min="1" max="5" :loader-height="500" :thumb-size="80">
			<template v-slot:thumb-label="{ value }">{{ value + ' BOOSTER(S)' }}</template>
		</v-slider>
	</v-card>
</template>

<script>
export default {
	computed: {
		amount: {
			get() {
				return this.$store.state.boosters_slider.amount;
			},
			set(value) {
				this.$store.commit("boosters_slider/changeAmount", value);
				this.$emit("changeprice");
			},
		},
	},
};
</script>

<style scoped>
.v-slider__track-container {
	height: 20px !important;
}
</style>
