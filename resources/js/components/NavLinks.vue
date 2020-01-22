<template>
	<div class="text-center">
		<v-menu
			transition="slide-y-transition"
			bottom
			offset-y
			open-on-hover
			open-delay="200"
			close-delay="200"
			min-width="100%"
			nudge-bottom="28"
			internal-activator
			dark
		>
			<template v-slot:activator="{ on }">
				<a href="/regular-boosting" v-on="on">Boosting</a>
			</template>
			<v-row no-gutters class="dark">
				<v-col md="3"></v-col>
				<v-col md="3">
					<v-row>
						<img src="/images/tft.png" alt="Regular Boosting" class="hvr-glow" />
					</v-row>
					<v-row class="title">
						<a href="/regular-boosting">Regular Boosting</a>
					</v-row>
				</v-col>
				<v-col md="3">
					<v-row>
						<img src="/images/tft.png" alt="TFT Boosting" class="hvr-glow" />
					</v-row>
					<v-row class="title">
						<a href="/tft-boosting">TFT Boosting</a>
					</v-row>
				</v-col>
				<v-col md="3"></v-col>
			</v-row>
		</v-menu>
		<a href="#" title="Coaching">Coaching</a>
		<a href="#" title="Lol Accounts">Lol Accounts</a>
		<a href="#" title="Boosters">Boosters</a>
		<a href="#" title="More*">More*</a>
	</div>
</template>

<script>
export default {
	data: () => ({
		boostings: [{ title: "Regular Boosting" }, { title: "TFT Boosting" }],
		accounts: [
			{ title: "Unranked Smurf Accounts" },
			{ title: "Ranked Accounts" }
		]
	})
};
</script>

<style scoped>
.title {
	justify-content: center;
}
img {
	cursor: pointer;
	margin-top: -20%;
	margin-bottom: -20%;
}
img:hover {
	box-shadow: inset 0 -10px 0 0 #400042;
}
.dark {
	background-color: #000;
}
.col > .row {
	margin-left: 10%;
	margin-right: 10%;
}
.col {
	padding: 25%;
}
a:not(.row) {
	text-decoration: none;
	color: white;
	padding-bottom: 6px;
	border-bottom: 3px solid currentColor;
	margin-right: 0.8rem;
	text-align: center;
}
</style>
