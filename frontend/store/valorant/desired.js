export const state = () => ({
	rank: 4,
	division: 12,
	server: "EU-West",
});

export const mutations = {
	changerank(state, payload) {
		state.rank = payload;
	},
	changeDivision(state, payload) {
		state.division = payload;
	},
	changeServer(state, payload) {
		state.server = payload;
	},
}
