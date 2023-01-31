export const state = () => ({
	tier: {},
	division: {},
	mmr: {}, // 19-21
	lp: {}, // 21-40
	server: "EU-West",
	mark: "0 / 3 Mark Status",
});

export const mutations = {
	changeTier(state, payload) {
		state.tier = payload;
	},
	changeDivision(state, payload) {
		state.division = payload;
	},
	changeLP(state, payload) {
		state.lp = payload;
	},
	changeMMR(state, payload) {
		state.mmr = payload;
	},
	changeServer(state, payload) {
		state.server = payload;
	},
	changeMark(state, payload) {
		state.mark = payload;
	}
}
