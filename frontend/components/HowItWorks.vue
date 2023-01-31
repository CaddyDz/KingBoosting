<template>
	<section class="flex-center">
		<v-img src="/img/indexAssets/howItWorks.png" class="bg" />
		<v-container>
			<v-card elevation="3">
				<v-card-title class="section-title flex-center">
					<h1>How KingBoosting Works ?</h1>
				</v-card-title>

				<v-row>
					<v-col class="steps" md="7">
						<v-row v-for="(step, index) in steps" :key="index">
							<p class="step">{{ step }}</p>
						</v-row>
					</v-col>
					<v-col md="5">
						<div class="image">
							<div id="laptop" class="flex-center">
								<div class="laptop-top">
									<div class="laptop-screen">
										<v-icon class="play">mdi-play-box-outline</v-icon>
										<v-icon class="list">mdi-format-list-text</v-icon>
										<v-icon class="cred">mdi-credit-card-outline</v-icon>
										<v-icon class="account">mdi-card-account-details</v-icon>
									</div>
								</div>
								<div class="laptop-bottom"></div>
							</div>
							<div id="tablet" class="flex-center">
								<div class="tablet-screen">
									<v-icon class="cog-sm">mdi-cog</v-icon>
									<v-icon class="cog-lg-w">mdi-cog</v-icon>
									<v-icon class="cog-lg-p">mdi-cog</v-icon>
								</div>
								<div class="tablet-btn"></div>
							</div>
						</div>
					</v-col>
				</v-row>
			</v-card>
		</v-container>
	</section>
</template>

<script>
export default {
	data: () => ({
		steps: [
			"You select the game and details of your boost",
			"Upon agreeing to the purchase, you will be redirected to your chosen payment gateway, you can fill this in as a guest or as a returning customer.",
			"Your order gets placed, and is immediately visible in the member area, where you can track, spectate, pause or live chat with your booster!",
		],
		icons: [
			{ name: "phone_iphone", color: "#7854c5" },
			{ name: "settings", color: "#7854c5" },
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
.bg {
	position: absolute;
	width: 100%;
	margin-top: -7%;
	filter: contrast(50%);
	z-index: 1;
}
img {
	height: 100vh;
}
.container {
	position: relative;
	z-index: 3;
}
.v-card {
	width: 100%;
	background: rgba(59, 38, 89, 0.6);
	box-sizing: border-box;
	padding: 32px;
	margin-top: 32px;
}
.section-title {
	font-size: 37px !important;
}
.steps {
	border-left: 13px solid white;
	box-sizing: border-box;
	padding-left: 40px;
}
.step {
	font-size: 26px !important;
}

.image {
	position: relative;
	width: 100%;
	height: 100%;
}
#tablet {
	position: absolute;
	top: 0;
	right: 0;
	width: 192px;
	height: 272px;
	background: #7854c5;
	border-radius: 8px;
	box-sizing: border-box;
	padding: 16px;
	padding-bottom: 48px;
}
.tablet-screen {
	width: 100%;
	height: 100%;
	background: #2d1d50;
}
.cog-sm {
	position: absolute;
	font-size: 52px;
	top: 65px;
	left: 45px;
	animation: rotate-counter-clock 4s linear infinite;
}
.cog-lg-w {
	position: absolute;
	top: 120px;
	left: 28px;
	font-size: 80px;
	animation: rotate-counter-clock 4s linear infinite;
}
.cog-lg-p {
	position: absolute;
	top: 80px;
	right: 20px;
	font-size: 80px;
	color: #7854c5;
	animation: rotate-clock 4s linear infinite;
}

@keyframes rotate-clock {
	from {
		transform: rotate(0deg);
	}
	to {
		transform: rotate(360deg);
	}
}
@keyframes rotate-counter-clock {
	from {
		transform: rotate(0deg);
	}
	to {
		transform: rotate(-360deg);
	}
}
.tablet-btn {
	position: absolute;
	bottom: 16px;
	background: #2d1d50;
	width: 24px;
	height: 24px;
	border-radius: 48px;
}
#laptop {
	position: absolute;
	bottom: 0;
	left: 0;
	width: 345px;
	height: 240px;
}
.laptop-top {
	position: absolute;
	top: 0%;
	background: #7854c5;
	margin: auto;
	width: 90%;
	height: 96.5%;
	border-radius: 8px;
	box-sizing: border-box;
	padding: 16px;
}
.laptop-screen {
	position: relative;
	width: 100%;
	height: 100%;
	background: #2d1d50;
}
.play {
	position: absolute;
	bottom: 0;
	right: 0;
	font-size: 112px;
}
.list {
	position: absolute;
	top: 0;
	left: 0;
	font-size: 144px;
}
.account {
	position: absolute;
	bottom: 4px;
	left: 48px;
	font-size: 128px;
	color: #7854c5;
}
.cred {
	position: absolute;
	top: 0;
	right: 0;
	font-size: 96px;
}
.laptop-bottom {
	position: absolute;
	bottom: 0;
	background: #7854c5;
	width: 100%;
	height: 3.5%;
	border-radius: 1.6px 1.6px 0.48px 0.48px;
}
.icon {
	color: #7854c5aa;
}
@media only screen and (max-width: 1600px) {
	section {
		margin-top: -80px;
	}
}
@media only screen and (max-width: 1200px) {
	.bg {
		width: inherit;
		height: 100%;
	}
}
</style>;
