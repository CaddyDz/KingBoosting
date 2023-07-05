<template>
	<v-app-bar app flat color="#1e1e1e" prominent height="88">
		<div id="particles-js"></div>
		<nuxt-link to="/">
			<v-img src="/img/logo/nav.png" alt="Logo" height="88" />
		</nuxt-link>

		<v-tabs centered color="primary" optional slider-size="4">
			<v-tab v-for="link in links" :key="link.title" nuxt :to="link.url">{{ link.title }}</v-tab>
		</v-tabs>
		<div style="height: inherit; border-bottom: white solid 5px">
			<v-btn depressed tile color="primary" :href="url" block x-large class="mr-0" style="height: 100%" v-if="loggedIn">Members Area</v-btn>
			<v-dialog max-width="800px" v-model="dialog" v-else>
				<template v-slot:activator="{ on }">
					<v-btn depressed tile color="primary" v-on="on" block x-large class="mr-0" style="height: 100%">Members Area</v-btn>
				</template>
				<v-card class="px-5" color="#303030">
					<v-container>
						<v-row>
							<v-col cols="7">
								<RegisterForm @close="dialog = false" v-if="register"></RegisterForm>
								<LoginForm @close="dialog = false" redirect v-else></LoginForm>
							</v-col>
							<v-col cols="5">
								<SocialLogin></SocialLogin>
							</v-col>
						</v-row>
					</v-container>
				</v-card>
			</v-dialog>
		</div>
	</v-app-bar>
</template>

<script>
export default {
	data: () => ({
		links: [
			{ title: "Elo Boosting", url: "division-boosting" },
			{ title: "Coaching", url: "coaching" },
		],
		dialog: false,
		register: false,
	}),
	computed: {
		url() {
			return process.env.HOST_URL;
		},
		loggedIn() {
			return this.$auth.loggedIn;
		},
	},
	mounted() {
		this.$root.$on("openRegisterForm", () => {
			this.register = true;
		});
		this.$root.$on("openLoginForm", () => {
			this.register = false;
		});
	},
};
</script>

<style scoped>
.header_logo {
	padding-top: 30px;
}

.v-btn:hover:before {
	content: none;
}

#particles-js canvas {
	display: block;
	transform: scale(1);
	opacity: 1;
	transition: opacity 0.8s ease, transform 1.4s ease;
}

#particles-js {
	width: 100%;
	height: 100%;
	position: fixed;
	z-index: -10;
	top: 0;
	left: 0;
}
.v-tab {
	font-size: 16px;
}
a:hover {
	color: purple !important;
}
</style>
