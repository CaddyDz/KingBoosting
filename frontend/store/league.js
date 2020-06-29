export const state = () => ({
	tier: {
		wins: [{
			wins: 4,
			eta: "0-1 day"
		}]
	},
	wins: 4,
});

export const mutations = {
	changeTier(state, payload) {
		state.tier = payload;
	},
	changeNumberOfWins(state, payload) {
		state.wins = payload;
	}
}

export const getters = {
	eta(state) {
		return _.find(state.tier.wins, [
			"wins",
			state.wins
		]).eta;
	},
}
