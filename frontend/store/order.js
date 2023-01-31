export const state = () => ({
	nickname: '',
	booster: '',
	comment: '',
	offline: false,
});

export const mutations = {
	changeNickname(state, payload) {
		state.nickname = payload;
	},
	changeBooster(state, payload) {
		state.booster = payload;
	},
	changeComment(state, payload) {
		state.comment = payload;
	},
	setOffline(state, payload) {
		state.offline = payload;
	}
}
