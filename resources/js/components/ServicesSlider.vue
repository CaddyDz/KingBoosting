<template>
	<v-sheet elevation="8" max-width="1920" color="transparent">
		<v-slide-group v-model="model" class="pa-4" center-active>
			<v-slide-item
				v-for="(service, index) in services"
				:key="index"
				v-slot:default="{ active, toggle }"
			>
				<v-card
					:color="active ? 'transparent' : 'transparent'"
					class="ma-4"
					height="150"
					width="100"
					@click="toggle"
				>
					<p>{{ service.kind.name }}</p>
					<img :src="service.image" :alt="service.name" />
					<v-row class="fill-height" align="center" justify="center">
						<v-scale-transition>
							<v-icon v-if="active" color="white" size="48" v-text="'mdi-close-circle-outline'"></v-icon>
						</v-scale-transition>
					</v-row>
				</v-card>
			</v-slide-item>
		</v-slide-group>
	</v-sheet>
</template>

<script>
export default {
	data: () => ({
		model: null,
		services: []
	}),
	mounted() {
		axios
			.get("/api/services")
			.then(response => (this.services = response.data));
	}
};
</script>
