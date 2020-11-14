export const state = () => ({
	tier: 3,
	division: 12,
	server: "EU-West",
	wins: 4,
	mode: "Solo/Duo",
	basePrice: 3.9,
	percent: 0,
	nickname: '',
	booster: null,
	comments: '',
	offline: false
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
	changeNumberOfWins(state, wins) {
		state.wins = wins;
	},
	changeGameMode(state, mode) {
		state.mode = mode;
	},
	changeBasePrice(state, price) {
		state.basePrice = price;
	},
	changeNickname(state, payload) {
		state.nickname = payload;
	},
	changeBooster(state, payload) {
		state.booster = payload;
	},
	changeComment(state, payload) {
		state.comments = payload;
	},
	changeChatMode(state, payload) {
		state.offline = payload;
	}
}
