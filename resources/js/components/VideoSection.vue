<template>
	<v-container>
		<v-row>
			<v-spacer></v-spacer>
			<v-col md="2">
				<div class="text-center">
					<v-dialog v-model="dialog" width="500">
						<template v-slot:activator="{ on }">
							<v-icon color="orange darken-2" size="150" v-on="on">mdi-play-circle-outline</v-icon>
						</template>
						<iframe
							width="560"
							height="315"
							src="https://www.youtube.com/embed/LO1mTELoj6o"
							frameborder="0"
							allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
							allowfullscreen
						></iframe>
					</v-dialog>
				</div>
			</v-col>
			<v-spacer></v-spacer>
		</v-row>
	</v-container>
</template>

<script>
export default {
	data() {
		return {
			dialog: false
		};
	}
};
</script>
