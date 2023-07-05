<template>
	<section class="why-us-section pt-5">
		<v-container>
			<v-row class="section-title flex-center mb-8">
				<h1>Why Us ?</h1>
			</v-row>
			<v-row>
				<v-col sm="12" md="6" class="v-card-container" v-for="(priority, index) in priorities" :key="index">
					<v-card align-self="center" :class="!(index % 3) ? 'bg-dark' : 'bg-white'">
						<v-row v-if="!(index % 2)">
							<v-col sm="10" lg="12">
								<v-card-title class="title flex-center">
									<h2>{{ priority.title }}</h2>
								</v-card-title>
							</v-col>
							<v-col sm="2" lg="2" class="flex-center">
								<v-icon>{{ icons[index] }}</v-icon>
							</v-col>
							<v-col sm="12" lg="10" class=".p0">
								<v-card-text class="p0">
									<p class="desc">{{ priority.description }}</p>
								</v-card-text>
							</v-col>
						</v-row>
						<v-row v-if="index % 2">
							<v-col sm="10" lg="12">
								<v-card-title class="title flex-center">
									<h2>{{ priority.title }}</h2>
								</v-card-title>
							</v-col>
							<v-col sm="2" lg="2" class="flex-center">
								<v-icon>{{ icons[index] }}</v-icon>
							</v-col>
							<v-col sm="12" lg="10" class=".p0">
								<v-card-text class="p0">
									<p class="desc">{{ priority.description }}</p>
								</v-card-text>
							</v-col>
						</v-row>
					</v-card>
				</v-col>
			</v-row>
		</v-container>
	</section>
</template>

<script>
export default {
	data: () => ({
		priorities: [
			{
				icon: "/nothing.png",
				title: "Customizable Boosting experience",
				description:
					"Kingboosting offers you a great degree of freedom with your boosts details, making sure you get your LoL elo boost exacly the way you want!",
			},
			{
				icon: "/nothing.png",
				title: "24/7 available boosters",
				description:
					"Here at Kingboosting we have a vast selection of boosters that are available in all regions. and in 9 different time zones. Our challenger players can boost in all regions and servers and upon purchase you will be connected with a challenger player available 24/7",
			},
			{
				icon: "/nothing.png",
				title: "Best prices guaranteed & our refund policy",
				description:
					"At KingBoosting we offer the most reasonable prices for anyone who wishes to take a boosting order, and if you are not satisfied with your service, we offer a generous refund policy, making sure you never leave unsatisfied!",
			},
			{
				icon: "",
				title: "Challenger Lol elo boosters guaranteed",
				description:
					"Kingboosting's employees are verified challenger players in previous seasons, they have a wide range of champions that they capable of playing as well as being masters in every role. All hand picked and tested in every role!",
			},
		],
		icons: [
			"mdi-school",
			"mdi-google-circles-communities",
			"mdi-chart-bubble",
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
	margin-bottom: 80px;
}
.container {
	position: relative;
	flex-direction: column;
	z-index: 3;
}
.section-title {
	position: relative;
	font-size: 24px;
}
.container {
	display: flex;
	justify-content: center;
	align-items: center;
}
.v-card-container {
	box-sizing: border-box;
	padding: 16px;
}
.v-card {
	box-sizing: border-box;
	padding: 0 32px;
}
.title {
	font-size: 16px !important;
	text-align: center;
}
.desc {
	font-size: 14px;
	line-height: 24px;
}
.bg-white {
	background: rgba(255, 255, 255, 0.2);
}
.bg-dark {
	background: rgba(0, 0, 0, 0.6);
}
.flex-center {
	display: flex;
	justify-content: center;
	align-items: center;
}
.ml0 {
	margin-left: 0;
}
.mr0 {
	margin-right: 0;
}
.v-icon {
	font-size: 80px !important;
}
.p0 {
	padding: 0;
}
@media only screen and (max-width: 1600px) {
	section {
		margin-top: 0;
	}
}
@media only screen and (max-width: 1200px) {
	.bg {
		width: inherit;
		height: 100%;
	}
}
</style>
