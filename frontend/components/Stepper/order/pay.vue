<template>
	<v-stepper-content key="3-content" step="3">
		<v-card class="mb-12" color="grey lighten-1" height="200px"></v-card>
		<v-btn color="primary" @click="sendOrder">Continue</v-btn>
		<v-btn text @click="cancel">Cancel</v-btn>
	</v-stepper-content>
</template>

<script>
export default {
	methods: {
		sendOrder() {
			// Send current service, get slug from url (pure JS)
			let service = document.location.pathname.replace("/", "");
			console.log(service);
			// Send current tier and division
			let tier = this.$store.state.league.tier.name;
			console.log(tier);
			let division = this.$store.state.league.division.name;
			console.log(division);
			// Send selected server
			let server = this.$store.state.league.server;
			console.log(server);
			// Send number of wins
			let wins = this.$store.state.league.wins;
			console.log(wins);
			// Send game mode (solo/duo)
			let mode = this.$store.state.league.mode;
			console.log(mode);
			// Send extra features
			let extraFeatures = this.$store.state.league.options;
			console.log(extraFeatures);
			// Send discount code
			let discountCode = this.$store.state.league.discountCode;
			console.log(discountCode);
			// Send in-game-nickname
			let nickname = this.$store.state.league.nickname;
			console.log(nickname);
			// Send selected booster
			let booster = this.$store.state.league.booster;
			console.log(booster);
			// Send Comments
			let comments = this.$store.state.league.comments;
			console.log(comments);
			// Send (appear offline in chat)
			let offline = this.$store.state.league.offline;
			console.log(offline);
			// Get all data from store and post them to DB
		},
		cancel() {
			this.$emit("cancel");
		}
	}
};
</script>
