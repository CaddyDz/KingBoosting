<template>
	<v-row justify="end" align="end">
		<v-col md="12">
			<v-sheet min-width="100%" color="transparent" tile>
				<v-slide-group v-model="model" class="pa-4" center-active mandatory>
					<!-- This is a hack to center the first element -->
					<v-slide-item v-for="n in 3" :key="n">
						<v-card color="transparent" height="210" class="ma-4" width="180" outlined :ripple="false">
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
						<nuxt-link :to="service.slug">
							<v-card
								:color="active ? 'transparent' : 'transparent'"
								:class="{active: active}"
								height="210"
								class="ma-4"
								width="180"
								@click="toggle()"
								outlined
								:ripple="false"
								hover
							>
								<v-row>
									<img :src="'/'+service.image" :alt="service.name" />
									<v-card-text>
										<p class="font-weight-bold caption text-center">{{ service.name }}</p>
									</v-card-text>
								</v-row>
							</v-card>
						</nuxt-link>
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
	computed: {
		services() {
			return this.$store.state.services.services;
		},
		model: {
			get() {
				return this.service.id + 2;
			},
			set() {
				return this.service.id + 2;
			}
		}
	},
	mounted() {
		// If there are no services in the store, ex: user entered page directly
		if (_.isEmpty(this.services)) {
			this.$axios.$get("/services").then(data => {
				this.$store.commit("services/populate", data);
			});
		}
	}
};
</script>

<style lang="scss" scoped>
$card-border-radius: 50% !default;

.active {
	background-image: url("/img/service_nav_bg.png");
	background-position: center bottom;
}
</style>
