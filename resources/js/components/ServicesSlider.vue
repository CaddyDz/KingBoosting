<template>
	<v-row justify="end" align="end">
		<v-col md="12">
			<v-sheet min-width="100%" color="transparent" tile>
				<v-slide-group v-model="model" class="pa-4" center-active mandatory @click="magic">
					<!-- This is a hack to center the first element -->
					<v-slide-item v-for="n in 3" :key="n">
						<v-card color="transparent" height="210" class="ma-4" width="150" outlined :ripple="false">
							<v-row>
								<v-card-text></v-card-text>
							</v-row>
						</v-card>
					</v-slide-item>
					<!-- End hack -->
					<v-slide-item
						v-for="(service, index) in services"
						:key="index + 4"
						v-slot:default="{ active, toggle }"
					>
						<v-card
							:color="active ? 'transparent' : 'transparent'"
							:class="{active: active}"
							height="210"
							class="ma-4"
							width="150"
							@click="visit(service.slug), toggle"
							outlined
							:ripple="false"
							hover
						>
							<v-row>
								<img :src="service.image" :alt="service.name" />
								<v-card-text>
									<p class="font-weight-bold caption text-center">{{ service.name }}</p>
								</v-card-text>
							</v-row>
						</v-card>
					</v-slide-item>
				</v-slide-group>
			</v-sheet>
		</v-col>
	</v-row>
</template>

<script>
export default {
	props: {
		service: {
			type: Object,
			required: true
		}
	},
	data: () => ({
		model: null,
		services: []
	}),
	methods: {
		visit(slug) {
			window.location = "/" + slug;
		}
	},
	created() {
		// Get the window location slug
		// Get the id of the service from that slug
		// Set the data model to that id so that the selected service is centered
		this.model = this.service.id + 2;
	},
	mounted() {
		axios
			.get("/api/services")
			.then(response => (this.services = response.data));
	}
};
</script>

<style scoped>
.active {
	background-image: url("/img/service_nav_bg.png");
	background-position: center bottom;
}
</style>

<style lang="scss">
$card-border-radius: 50% !default;
</style>
