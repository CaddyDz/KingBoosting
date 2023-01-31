export default (context, inject) => {
	inject('notify', (message, mode, errors) => {
		// Get store from context
		context.store.commit('notification/open', {
			text: message,
			mode: mode,
			errors: errors
		});
	});
}
