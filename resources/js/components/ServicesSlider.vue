<template>
	<v-sheet min-width="100%" color="transparent" tile>
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
					<v-row align="start" justify="center" v-if="active">
						<v-scale-transition>
							<p>{{ service.kind.name }}</p>
						</v-scale-transition>
					</v-row>
					<v-row>
						<img :src="service.image" :alt="service.name" />
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
