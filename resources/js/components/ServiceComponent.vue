<template>
	<div>
		<v-container fluid :style="{background: 'url(' + this.service.bg_img + ') top/cover'}">
			<div id="particles"></div>
			<v-row justify="end" align="center">
				<v-col md="8">
					<services-slider></services-slider>
					<!-- <img :src="this.service.image" :alt="this.service.name" /> -->
				</v-col>
			</v-row>
		</v-container>
		<div class="checkout">
			<v-container>
				<v-row>
					<v-col md="9">
						<checkout></checkout>
					</v-col>
					<v-col md="3">Verified Customer Reviews</v-col>
				</v-row>
			</v-container>
		</div>
	</div>
</template>

<script>
require("particles.js");
export default {
	props: {
		service: {
			required: true,
			type: Object
		}
	},
	mounted() {
		particlesJS.load("particles", "/particlesjs-config.json");
	}
};
</script>

<style scoped>
.checkout {
	top: -32px;
	background-size: cover;
	background-position: top center;
	background-repeat: no-repeat;
	min-height: 330px;
	position: relative;
	z-index: 0;
	background-image: url("/img/blue-bg.png");
}
#particles canvas {
	display: block;
	vertical-align: bottom;
	transform: scale(1);
	opacity: 1;
	transition: opacity 0.8s ease, transform 1.4s ease;
}

#particles {
	width: 100%;
	height: 100%;
	position: fixed;
	top: 0;
	left: 0;
}
</style>
