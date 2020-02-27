<template>
	<div>
		<v-container fluid :style="{background: 'url(' + this.service.bg_img + ') top/cover'}">
			<div id="particles"></div>
			<v-row justify="center" align="center">
				<v-col md="8" style="text-align: center;">
					{{ kind }}
					<br />
					<p class="display-2 font-weight-bold">{{ this.service.name }}</p>
					<br />
					{{ this.service.description }}
				</v-col>
			</v-row>
			<services-slider :service="service"></services-slider>
		</v-container>
		<div class="checkout">
			<v-container>
				<v-row>
					<v-col md="9">
						<simple :service="service"></simple>
						<!-- <checkout :service="service"></checkout> -->
					</v-col>
					<v-col md="3">{{ $t('Verified Customer Reviews</v-col')>
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
	data() {
		return {
			kind: ""
		};
	},
	mounted() {
		// particlesJS.load("particles", "/particlesjs-config.json");
		axios
			.get(`/api/services/${this.service.kind_id}/kind`)
			.then(response => (this.kind = response.data));
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

<i118>
{
	"en":{
		"Verified Customer Reviews</v-col": "Verified Customer Reviews</v-col"
	}
}
</i18n>