export const state = () => ({
	tier: {},
	division: {},
	lp: 0, // Only for Master+
	server: "EU-West",
	queue: "Solo/Duo",
	platform: 'Android',
});

export const mutations = {
	changeTier(state, payload) {
		state.tier = payload;
	},
	changeDivision(state, payload) {
		state.division = payload;
	},
	changeServer(state, payload) {
		state.server = payload;
	},
	changeQueue(state, payload) {
		state.queue = payload;
	},
	changePlatform(state, payload) {
		state.platform = payload;
	}
}
