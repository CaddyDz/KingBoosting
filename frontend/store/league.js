export const state = () => ({
	tier: {
		wins: [{
			wins: 4,
			eta: "0-1 day"
		}]
	},
	basePrice: 3.9,
	wins: 4,
	percent: 0,
});

export const mutations = {
	changeTier(state, payload) {
		state.tier = payload;
	},
	changeNumberOfWins(state, wins) {
		state.wins = wins;
	},
	changeBasePrice(state, price) {
		state.basePrice = price;
	},
	addPercent(state, percent) {
		state.percent += percent;
	},
	substractPercent(state, percent) {
		state.percent -= percent;
	}
}

export const getters = {
	eta(state) {
		return _.find(state.tier.wins, [
			"wins",
			state.wins
		]).eta;
	},
	price(state) {
		let price = state.basePrice * state.wins;
		price += (price * state.percent) / 100;
		return price;
	}
}
