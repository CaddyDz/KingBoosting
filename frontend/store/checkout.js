export const state = () => ({
	eta: '0-1 day'
})

export const mutations = {
	changeETA(state, payload) {
		state.eta = payload;
	}
}
