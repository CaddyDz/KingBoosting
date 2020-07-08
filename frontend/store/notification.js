export const state = () => ({
	isOpen: false,
	text: "",
	mode: 'info',
})

export const mutations = {
	open(state, payload) {
		state.isOpen = true;
		state.text = payload.text;
		state.mode = payload.mode;
	},
	close(state) {
		state.isOpen = false;
	},
}
