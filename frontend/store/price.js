export const state = () => ({
	price: 3.9,
});


export const getters = {
	price(state, getters, rootState) {
		let wins = rootState.wins.wins;
		let percent = rootState.checkout.percent;
		let price = state.price * wins;
		price += (price * percent) / 100;
		return price;
	}
};
