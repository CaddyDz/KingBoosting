import VuexPersistence from 'vuex-persist'

export default ({
	store
}) => {
	window.onNuxtReady(() => {
		new VuexPersistence({
			storage: window.localStorage,
			reducer: (state) => ({
				services: state.services,
				isLoggedIn: state.authentication.isLoggedIn
			}),
		}).plugin(store);
	});
}
