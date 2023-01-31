<template>
	<v-row>
		<v-col class="card" v-for="(game, index) in games" :key="index" :class="{selectedGame: (game.name == currentGame), disabled: game.disabled}">
			<nuxt-link :to="game.slug || '/'" :event="game.disabled ? '' : 'click'" :class="{disabled: game.disabled}">
				<img :src="game.image" width="100%" />
			</nuxt-link>
		</v-col>
	</v-row>
</template>

<script>
import games from "~/assets/js/games";
import services from "~/assets/js/services";

export default {
	data: () => ({
		games: games,
		// We need all services in order to grab the first one of each game
		// Then we use that to define the link of the first service in a game
		services: services,
	}),
	computed: {
		currentGame() {
			let service = _.find(this.services, ["slug", this.$route.name]);
			return service.game;
		},
	},
};
</script>

<style scoped>
.card {
	transition-duration: 0.3s;
	transition-timing-function: cubic-bezier(0.175, 0.885, 0.32, 1.275);
	filter: grayscale(100%);
	padding-bottom: 0;
	line-height: 0;
}

.card:hover {
	transform: translateY(-30px);
	cursor: pointer;
	filter: grayscale(0%);
}

.disabled:hover {
	transform: none;
	cursor: not-allowed;
}

.selectedGame {
	filter: grayscale(0%);
}

.selectedGame:hover {
	transform: none;
}
</style>
