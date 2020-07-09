<template>
	<div>
		<div class="title">
			<div class="title-id">
				<h2>2</h2>
			</div>
			<h2 class="title-txt">{{ $t('Select Your Number Of Wins') }}</h2>
		</div>
		<v-card raised class="mb-4">
			<v-container class="container">
				<v-slider v-model="winsCounter" thumb-label="always" min="1" :max="max"></v-slider>
				<v-radio-group class="radio-group" v-model="mode" dark mandatory row>
					<v-radio :label="$t('Solo/Duo')" value="Solo/Duo"></v-radio>
					<v-radio :label="$t('Flex 5v5')" value="Flex"></v-radio>
				</v-radio-group>
			</v-container>
		</v-card>
	</div>
</template>

<script>
export default {
	data() {
		return {
			winsCounter: 4,
			mode: "Solo/Duo"
		};
	},
	computed: {
		max() {
			return this.$store.state.wins.maxNumberOfWins;
		},
		tier() {
			return this.$store.state.league.tier;
		}
	},
	watch: {
		winsCounter(value) {
			this.$store.commit("league/changeNumberOfWins", value);
			let eta = _.find(this.tier.wins, ["wins", value]).eta;
			this.$store.commit("checkout/changeETA", eta);
		},
		mode(value) {
			this.$store.commit("league/changeGameMode", value);
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

<i18n>
{
	"en": {
		"Flex 5v5": "Flex 5v5",
		"Solo/Duo": "Solo/Duo",
		"Select Your Number Of Wins": "Select Your Number Of Wins"
	}
}
</i18n>
