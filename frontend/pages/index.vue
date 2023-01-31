<template>
	<v-layout column fill-height>
		<v-img src="/img/indexAssets/kingBoosting.png" class="bg" />
		<v-container>
			<v-row align="center" justify="center" class="logo">
				<img src="/img/logo/logo.png" alt="Logo" width="55%" class="mt-15" />
			</v-row>
			<v-row align="end" justify="center" class="mt-7 mb-14">
				<v-btn-toggle rounded v-model="undefined" background-color="transparent">
					<v-btn class="hero-btn" to="/division-boosting">Get Boosted</v-btn>
					<v-btn class="hero-btn" to="/coaching">Get Coached</v-btn>
					<v-btn class="hero-btn">Buy an account</v-btn>
				</v-btn-toggle>
			</v-row>
		</v-container>
		<games />
		<features />
		<why-us />
		<how-it-works />
		<customers-voice />
	</v-layout>
</template>


<style scoped>
.bg {
	position: absolute;
	width: 100%;
	filter: contrast(70%);
	z-index: 1;
}
.container {
	position: relative;
	z-index: 3;
}
.logo {
	margin-top: 160px;
}
.hero-btn {
	background: black;
	box-sizing: border-box;
	padding: 0 48px !important;
	font-size: 14px;
	margin: 0 2px;
}
.hero-btn:hover {
	background-color: #472783 !important;
}

.round-left {
	border-radius: 80px 0 0 80px;
}
.round-right {
	border-radius: 0 80px 80px 0;
}
.mtr-12 {
	margin-top: 192px;
}
@media only screen and (max-width: 1200px) {
	.bg {
		width: inherit;
		height: 100%;
	}
}
</style>
