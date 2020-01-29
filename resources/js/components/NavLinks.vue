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
				<a href="/regular-boosting" v-on="on" class="link">Boosting</a>
			</template>
			<v-row no-gutters class="dark">
				<v-col md="3"></v-col>
				<v-col md="3">
					<v-row>
						<a href="/regular-boosting" class="title">
							<img src="/images/tft.png" alt="Regular Boosting" class="hvr-glow" />
							<span>Regular Boosting</span>
						</a>
					</v-row>
				</v-col>
				<v-col md="3">
					<v-row>
						<a href="/tft-boosting" class="title">
							<img src="/images/tft.png" alt="TFT Boosting" class="hvr-glow" />
							<span>TFT Boosting</span>
						</a>
					</v-row>
				</v-col>
				<v-col md="3"></v-col>
			</v-row>
		</v-menu>
		<a href="#" title="Coaching" class="link">Coaching</a>
		<a href="#" title="Lol Accounts" class="link">Lol Accounts</a>
		<a href="#" title="Boosters" class="link">Boosters</a>
		<a href="#" title="More*" class="link">More*</a>
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
img {
	cursor: pointer;
}

img:hover {
	background-image: url("/img/service_nav_bg.png");
	background-position: center bottom;
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

.link {
	text-decoration: none;
	color: white;
	padding-bottom: 6px;
	border-bottom: 3px solid currentColor;
	margin-right: 0.8rem;
	text-align: center;
}

.title {
	text-align: center;
	text-decoration: none !important;
}

span {
	position: relative;
	bottom: 40px;
}
</style>
