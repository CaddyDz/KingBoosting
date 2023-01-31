<template>
	<default-layout>
		<v-layout column full fill-height class="page">
			<v-container>
				<v-row class="flex-center">
					<v-img src="/img/logo/logo.png" max-width="700" />
				</v-row>
				<games-slider />
				<game-services :services="gameServices" />
				<v-row class="flex-center order-form">
					<v-col>
						<v-form ref="order">
							<Nuxt :nuxt-child-key="key" />
						</v-form>
					</v-col>
				</v-row>
			</v-container>
			<why-us />
			<customers-voice />
		</v-layout>
	</default-layout>
</template>

<script>
import services from "~/assets/js/services";
import DefaultLayout from "~/layouts/default.vue";

export default {
	head: () => ({
		link: [
			{
				rel: "stylesheet",
				href: "/css/order.css",
			},
		],
	}),
	name: "Order",
	transition: "home",
	components: {
		DefaultLayout,
	},
	data: () => ({
		// We need all services in order to grab the first one of each game
		// Then we use that to define the link of the first service in a game
		services: services,
	}),
	computed: {
		gameServices() {
			return _.filter(this.services, [
				"game",
				_.find(this.services, ["slug", this.$route.name]).game,
			]);
		},
		key() {
			this.$route.fullPath;
		},
	},
};
</script>

<style scoped>
.page {
	background: url(/img/backgrounds/order.jpg);
	background-position: top;
	background-size: cover;
	background-repeat: no-repeat;
}

.flex-center {
	display: flex;
	justify-content: center;
	align-items: center;
}

.home-enter-active,
.home-leave-active {
	transition: opacity 0.5s;
}

.order-form {
	padding: 10px;
}

.home-enter,
.home-leave-active {
	opacity: 0;
}
</style>
