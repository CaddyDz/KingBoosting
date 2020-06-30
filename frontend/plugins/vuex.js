import VuexPersistence from 'vuex-persist'

export default ({
	store
}) => {
	window.onNuxtReady(() => {
		new VuexPersistence({
			reducer: (state) => ({
				services: state.services
			}),
		}).plugin(store);
	});
}
