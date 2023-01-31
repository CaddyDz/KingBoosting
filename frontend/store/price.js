export const state = () => ({
	basePrice: 0,
});

export const mutations = {
	changePrice(state, payload) {
		state.basePrice = payload;
	}
};

export const getters = {
	price(state, getters, rootState) {
		let wins = rootState.slider.amount;
		let percent = rootState.checkout.percent;
		let price = state.basePrice * wins;
		price += (price * percent) / 100;
		return price;
	}
};
