<template>
	<div>
		<div class="title">
			<div class="title-id">
				<h2>2</h2>
			</div>
			<h2 class="title-txt">Select Your Number Of Wins</h2>
		</div>
		<v-card raised class="mb-4">
			<v-container class="container">
				<v-slider
					v-model="winsCounter"
					thumb-label="always"
					min="1"
					:max="max"
					@end="winsHandler($event)"
				></v-slider>
				<v-radio-group
					@change="radioHandler($event)"
					class="radio-group"
					v-model="row"
					dark
					mandatory
					row
				>
					<v-radio label="Solo/Duo" value="Solo/Duo"></v-radio>
					<v-radio label="Flex 5v5" value="Flex"></v-radio>
				</v-radio-group>
			</v-container>
		</v-card>
	</div>
</template>

<script>
export default {
	data() {
		return {
			items: ["Foo", "Bar", "Fizz", "Buzz"],
			hasDivisions: true,
			winsCounter: 4,
			radios: "Solo/Duo",
			row: ""
		};
	},
	computed: {
		max() {
			return this.$store.state.wins.maxNumberOfWins;
		}
	},
	methods: {
		commitToStore(c) {
			this.$store.commit("boosting_order/setSlider", c);
		},
		winsHandler(e) {
			this.winsCounter = e;
			this.sliderChangeHandler();
		},
		radioHandler(e) {
			this.radios = e;
			this.sliderChangeHandler();
		},
		sliderChangeHandler() {
			this.commitToStore({ wins: this.winsCounter, radio: this.radios });
		}
	},
	mounted() {
		this.commitToStore({ wins: this.winsCounter, radio: this.radios });
	}
};
</script>

<style scoped>
.container {
	display: flex;
	flex-direction: column;
	/* align-items: center; */
	justify-content: center;
}

.radio-group {
	align-self: center;
	margin-top: 40px;
}

.title {
	display: flex;
	flex-direction: row;
	align-items: center;
	margin-bottom: 20px;
}

.title-txt {
	margin-left: 20px;
}

.title .title-id {
	color: #181818;
}

.title-id {
	width: 50px;
	height: 50px;
	border-radius: 50%;
	background: linear-gradient(#673ab7, #9c27b0);
	display: flex;
	align-items: center;
	justify-content: center;
}
</style>

<i18n>
{
  "en": {
    "Flex 5v5": "Flex 5v5",
    "Solo/Duo": "Solo/Duo"
  }
}
</i18n>
