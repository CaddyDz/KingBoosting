<template>
	<v-stepper-content step="1">
		<v-card raised class="mb-4" elevation="24">
			<v-container>
				<v-row justify="center" align="center">
					<v-col md="2"></v-col>
					<v-col md="8">
						<wins-slider :wins="wins"></wins-slider>
					</v-col>
					<v-col md="2"></v-col>
				</v-row>
				<v-row>
					<v-col md="3"></v-col>
					<v-col md="6">
						<v-radio-group row v-model="radios">
							<v-icon>mdi-account</v-icon>
							<v-radio label="Solo/Duo" value="solo"></v-radio>
							<v-icon>mdi-account-group</v-icon>
							<v-radio label="Flex 5v5" value="flex"></v-radio>
						</v-radio-group>
					</v-col>
					<v-col md="3"></v-col>
				</v-row>
			</v-container>
		</v-card>
	</v-stepper-content>
</template>

<script>
export default {
	data() {
		return {
			wins: 1,
			radios: "solo"
		};
	}
};
</script>
