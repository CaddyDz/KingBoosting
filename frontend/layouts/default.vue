<template>
	<v-app dark>
		<notification-component></notification-component>
		<v-system-bar lights-out app height="20">
			<a v-for="(social, index) in socials" :key="index" :href="social.link" target="_blank" rel="noopener">
				<v-icon color="white">{{ social.icon }}</v-icon>
			</a>
			<v-spacer></v-spacer>
			<span>
				<v-icon color="white">mdi-cellphone-iphone</v-icon>
				<a href="/pay-with-mobile">Mobile Pay</a>
			</span>
			<span>
				<a href="/contact">Contact</a>
			</span>
			<span>
				<a href="/loyalty-program">Loyalty Program</a>
			</span>
			<span v-if="this.$auth.loggedIn">
				<v-btn @click="logout">Logout</v-btn>
			</span>
		</v-system-bar>
		<app-bar-component />
		<v-main>
			<v-container fluid>
				<Nuxt v-if="!$slots.default" />
				<slot />
			</v-container>
		</v-main>
		<app-footer />
	</v-app>
</template>

<script>
export default {
	name: "Default",
	data: () => ({
		socials: [
			{
				icon: "mdi-facebook",
				link: "https://facebook.com/kingboosting",
			},
			{
				icon: "mdi-instagram",
				link: "https://www.instagram.com/kingboosting_official/",
			},
			{
				icon: "mdi-twitter",
				link: "https://twitter.com/kingboosting",
			},
			{
				icon: "mdi-skype",
				link: "skype:",
			},
		],
	}),
	methods: {
		async logout() {
			await this.$auth.logout();
			this.$root.$emit("logout");
		},
	},
};
</script>

<style scoped>
.container {
	padding: 0px;
}
a {
	text-decoration: none;
	transition: all 500ms ease;
}

a:hover,
i:hover {
	color: #472783 !important;
}

span {
	padding-right: 10px;
}
</style>
