<template>
	<section class="features-section">
		<v-row class="section-title flex-center mb-10">
			<h1>Features</h1>
		</v-row>
		<v-row v-for="(feature, index) in features" :key="index" class="mb-6">
			<v-col class="feature" offset="3">
				<v-card :class="index % 2 ? 'bg-dark' : 'bg-white'" elevation="24">
					<v-row>
						<v-col xs="1" sm="2" class="icon-container" align-self="center">
							<v-icon v-if="!(index % 2)">{{ feature.icon }}</v-icon>
						</v-col>
						<v-col xs="10" sm="8">
							<p class="feature-title">{{ feature.title }}</p>
							<p class="feature-desc">{{ feature.description }}</p>
						</v-col>
						<v-col xs="1" sm="2" class="icon-container" align-self="center">
							<v-icon v-if="index % 2">{{ feature.icon }}</v-icon>
						</v-col>
					</v-row>
				</v-card>
			</v-col>
		</v-row>
	</section>
</template>

<script>
export default {
	data: () => ({
		features: [
			{
				icon: "mdi-shield-account",
				title: "VPN Protection",
				description:
					"To offer the safest boosting service on the market, we here at KingBoosting make extensive use of VPN's to mask our IP's whenever a booster logs into the account of a customer, this helps prevent detecting logins from different locations, making sure that your account will be safe and sound after a satisfying boost order.",
			},
			{
				icon: "mdi-incognito",
				title: "Privacy",
				description:
					'We at KingBoosting value the privacy of our customer and thus offer an "Appear offline" tool to make sure that whenever the booster is completing your order, he won\'t appear online during the LoL rank boost.',
			},
			{
				icon: "mdi-timer",
				title: "Finish in time guarantee",
				description:
					"KingBoosting provides strict deadlines for our boosters on all ELO/Rank boost orders, to make sure that whenever you place an order, it will always be done before the set deadline. The maximum deadline of an order can vary between details of the order and difficulty, length of time to complete will always be displayed at the end of a purchase.",
			},
			{
				icon: "mdi-target-account",
				title: "Boost tracking and status",
				description:
					"Kingboosting offers a detailed order tracking system built into our website at the member's area for any rank elo boosts you might have taken. This system offers you a variety of data to use to see how your boost is going, from live charts of your match history and a spectate button to see how your booster is playing, all included in your order!",
			},
			{
				icon: "mdi-hours-24",
				title: "24/7 live customer support",
				description:
					"We at king boosting offer a 24/7 customer line for any complication you might have with a boost or account order, making sure that the customer always comes first.",
			},
			{
				icon: "mdi-seal",
				title: "Choose your champions & Roles for FREE",
				description:
					"We here at KingBoosting allow our customers to make special requests within their boosts, varying from what champion pool the booster should play and in what role. Making sure that any order you make, is done with your specifications!",
			},
		],
	}),
};
</script>

<style scoped>
.flex-center {
	display: flex;
	justify-content: center;
	align-items: center;
}
section {
	position: relative;
	width: 100%;
	box-sizing: border-box;
}
.section-title {
	position: relative;
	font-size: 24px;
	margin-top: 7%;
	z-index: 3;
}
.feature {
	position: relative;
	z-index: 3;
	padding: 0 !important;
}
.bg-white {
	background: rgba(255, 255, 255, 0.2) !important;
}
.bg-dark {
	background: rgba(0, 0, 0, 0.6) !important;
}
.feature-desc {
	font-size: 14px !important;
	line-height: 18px !important;
}
.icon-container {
	display: flex;
	justify-content: center;
	align-items: center;
}
.v-icon {
	font-size: 80px !important;
}
@media only screen and (max-width: 1600px) {
	section {
		margin-top: -80px;
	}
}
</style>
