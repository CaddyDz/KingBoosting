export const state = () => ({
	maxNumberOfWins: 10
})

export const mutations = {
	changeMaxNumberOfWins(state, payload) {
		state.maxNumberOfWins = payload.maxNumberOfWins;
	}
}
