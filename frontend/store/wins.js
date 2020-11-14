export const state = () => ({
	maxNumberOfWins: 10,
	wins: 4,
	mode: "Solo/Duo",
})

export const mutations = {
	changeMaxNumberOfWins(state, payload) {
		state.maxNumberOfWins = payload;
	},
	changeNumberOfWins(state, payload) {
		state.wins = payload;
	},
	changeGameMode(state, mode) {
		state.mode = mode;
	},
}
