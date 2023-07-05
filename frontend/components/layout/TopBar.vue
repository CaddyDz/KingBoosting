<template>
	<v-system-bar app height="25">
		<a v-for="(social, index) in socials" :key="index" :href="social.link" target="_blank" rel="noopener">
			<v-icon color="white" size="x-large">{{ social.icon }}</v-icon>
		</a>
		<v-spacer />
		<nuxt-link to="/contact">Contact</nuxt-link>
		<span v-if="this.$auth.loggedIn">
			<v-btn @click="logout">Logout</v-btn>
		</span>
	</v-system-bar>
</template>

<script>
export default {
	name: "TopBar",
	data: () => ({
		socials: [
			{
				icon: "mdi-facebook",
				link: "https://facebook.com/kingboosting",
			},
			{
				icon: "mdi-instagram",
				link: "https://www.instagram.com/kingboosting_official",
			},
			{
				icon: "mdi-twitter",
				link: "https://twitter.com/KingBoostingCom",
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
.v-system-bar {
	font-weight: 500;
	font-size: 16px;
	z-index: 4;
}
a {
	transition: all 500ms ease;
}
a:hover,
i:hover {
	color: #472783 !important;
}
</style>
