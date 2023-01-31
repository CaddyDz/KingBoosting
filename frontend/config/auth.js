export default {
	strategies: {
		'laravelSanctum': {
			provider: 'laravel/sanctum',
			url: process.env.HOST_URL,
		},
	},
	redirect: false
}
