export default {
	extendRoutes(nuxtRoutes) {
		nuxtRoutes.map(route => {
			route.path = route.path.replace('/services', '');
			route.name = route.name.replace('services-', '');

			return route;
		});
	},
}
