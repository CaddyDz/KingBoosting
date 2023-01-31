export const state = () => ({
	isOpen: false,
	text: "",
	mode: 'info',
	errors: null,
})

export const mutations = {
	open(state, payload) {
		state.isOpen = true;
		state.text = payload.text;
		state.mode = payload.mode;
		state.errors = payload.errors || null;
	},
	close(state) {
		state.isOpen = false;
	},
}
