<template>
	<v-slider
		min="1"
		max="10"
		thumb-label="always"
		:color="'purple'"
		v-model="number_of_wins"
		thumb-size="50"
	>
		<template v-slot:thumb-label :style="{top: '50px'}">{{ plural(number_of_wins, "Win") }}</template>
	</v-slider>
</template>

<script>
export default {
	props: {
		wins: {
			type: Number,
			required: true
		}
	},
	data() {
		return {
			number_of_wins: this.wins
		};
	},
	computed: {
		plural(count, noun) {
			return (count, noun) => `${count} ${noun}${count !== 1 ? "s" : ""}`;
		}
	}
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
