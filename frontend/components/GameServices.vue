<template>
	<v-row class="services">
		<v-tabs grow centered color="primary" slider-size="8" :height="height">
			<v-tab v-for="(service, index) in services" :key="index" class="service" nuxt :to="service.slug">
				<div v-if="service.icon">
					{{ service.name }}
					<v-icon>{{ `mdi-${service.icon}` }}</v-icon>
				</div>
				<img class="service-img" :src="`/img/services/${service.slug}.png`" v-else />
			</v-tab>
		</v-tabs>
	</v-row>
</template>

<script>
export default {
	props: {
		services: {
			type: Array,
			required: true,
		},
	},
	computed: {
		height() {
			return _.first(this.services).icon ? "" : "100%";
		},
	},
};
</script>

<style scoped>
.services {
	margin: 1em -1.5em !important;
}

.service {
	width: 10em;
	padding: 0;
	background: #222;
	margin: 0 0.2em;
}

.service-img {
	width: 100%;
}
</style>
