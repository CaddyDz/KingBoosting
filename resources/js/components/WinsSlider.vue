<template>
	<v-slider
		min="1"
		:max="maxNumberOfWins"
		thumb-label="always"
		color="purple"
		v-model="defaultNumberOfWins"
		thumb-size="50"
	>
		<template v-slot:thumb-label :style="{top: '50px'}">{{ plural(defaultNumberOfWins, "Win") }}</template>
	</v-slider>
</template>

<script>
export default {
	data() {
		return {
			defaultNumberOfWins: 4,
		};
	},
	computed: {
		plural(count, noun) {
			return (count, noun) => `${count} ${noun}${count !== 1 ? "s" : ""}`;
		},
		maxNumberOfWins() {
			return this.$store.getters['tierList/getMaxNumberOfWins'];
		},
	},
	watch: {
		defaultNumberOfWins(value) {
			// UPDATE PRICE BASED ON WANTED WINS
			this.$store.commit("tierList/updatePrice", (this.$store.getters['tierList/getSelectedDivision'].price * value) );
			//console.log(this.$store.getters['tierList/getPrice'])
			// UPDATE DEFAULT NUMBER OF WINS SINCE IT IS THE MODEL OF SLIDER IN LOCAL AND STORE STATE
			this.defaultNumberOfWins = value
			this.$store.commit('tierList/updateSelectedNumberOfWins', value)

			// GET ETA BASED ON NUMBER OF WINS SELECTED BY USER AND STORE IT IN THE BELLOW VAR
			var etaBasedOnSelectedValue = _.find(this.$store.getters['tierList/getSelectedTier'].wins, ["wins", value]).eta

			// Update ETA ON STORE
			this.$store.commit('tierList/updateETA', etaBasedOnSelectedValue )

			// SEND AN EVENT TO THE ROOT COMPONENT WITH CURRENT ETA
			this.$root.$emit("wins_changed", etaBasedOnSelectedValue);
			console.log(this.defaultNumberOfWins)
		},

	},
	mounted() {
		this.defaultNumberOfWins = this.$store.getters['tierList/getDefaultNumberOfWins'];
		setTimeout(()=> {
		console.log(this.$store.getters['tierList/getSelectedTier'])			

		},2000)
	},
};
</script>

<style lang="scss">
$slider-track-border-radius: 3px !default;
$slider-track-border-radius: 10 !default;
$slider-track-width: 2px !default;
// $slider-vertical-min-height: 150px !default;
$chip-group-no-color-opacity: 50 !default;
$slider-horizontal-margin-bottom: 0px !default;
$slider-horizontal-margin-top: 50px !default;

.v-slider--horizontal .v-slider__track-container {
	height: 20px;
}
.v-slider__track-fill {
	border-radius: 50px 0px 0px 50px;
}
.v-slider__track-background {
	border-radius: 0px 50px 50px 0px;
}
.v-input.v-input--is-label-active.v-input--is-dirty.theme--dark.v-input__slider {
	margin-top: 40px !important;
	margin-bottom: -40px !important;
}
</style>
