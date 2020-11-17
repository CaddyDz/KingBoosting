export const state = () => ({
	services: [],
})

export const mutations = {
	populate(state, services) {
		state.services = services
	}
}
