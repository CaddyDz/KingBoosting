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
				<v-slider v-model="winsCounter" thumb-label="always" min="1" :max="max"></v-slider>
				<v-radio-group class="radio-group" v-model="mode" dark mandatory row>
					<v-radio label="Solo/Duo" value="Solo/Duo"></v-radio>
					<v-radio label="Flex 5v5" value="Flex"></v-radio>
				</v-radio-group>
			</v-container>
		</v-card>
	</div>
</template>

<script>
export default {
	data: () => ( {
			winsCounter: 4,
			mode: "Solo/Duo"
	}),
	computed: {
		max() {
			return this.$store.state.league.tier > 5 ? 7 : 10;
		}
	},
	watch: {
		winsCounter(value) {
			this.$store.commit("wins/changeNumberOfWins", value);
		},
		mode(value) {
			this.$store.commit("wins/changeGameMode", value);
		}
	}
};
</script>

<style scoped>
.container {
	display: flex;
	flex-direction: column;
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
