export const state = () => ({
	amount: 2,
})

export const mutations = {
	changeAmount(state, payload) {
		state.amount = payload;
	},
}
