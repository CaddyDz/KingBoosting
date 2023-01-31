export const state = () => ({
	amount: 1,
	queue: "Solo/Duo",
})

export const mutations = {
	changeAmount(state, payload) {
		state.amount = payload;
	},
	changeQueue(state, payload) {
		state.queue = payload;
	},
}
