<template>
	<v-stepper vertical non-linear>
		<v-stepper-step step="1">Select Your Current League</v-stepper-step>

		<v-stepper-content step="1">
			<v-card raised class="mb-4" :style="{boxShadow: tier.box_shadow}">
				<v-container>
					<v-row align="center" justify="center">
						<v-col md="3">
							<img :src="division.image || tier.image" :alt="tier.name" loading="lazy" />
						</v-col>
						<v-col md="9">
							<v-row>
								<v-col>
									<v-select
										:items="tiers"
										name="tier"
										label="Current tier"
										v-model="selectedTierID"
										item-text="name"
										item-value="id"
									></v-select>
								</v-col>
								<v-col v-if="this.hasDivisions">
									<v-select
										:items="tier.divisions"
										label="Current division"
										item-text="name"
										v-model="selectedDivisionID"
										item-value="id"
									></v-select>
								</v-col>
							</v-row>
							<v-select :items="servers" label="Select your server" item-text="region" :value="servers[0]"></v-select>
						</v-col>
					</v-row>
				</v-container>
			</v-card>
		</v-stepper-content>
		<!-- Don't put the stepper step in the component after it because it removes the vertical line between the steps -->
		<v-stepper-step step="2">Select Your Number Of Wins</v-stepper-step>
		<number-of-wins :max="max"></number-of-wins>
		<payment-section></payment-section>
	</v-stepper>
</template>
<script>
export default {
	props: {
		service: {
			type: Object,
			required: true
		}
	},
	data() {
		return {
			e6: 1, // Stepper stupid model value, can't be hardcoded
			tier: { wins: [] }, // Currently selected tier
			division: {}, // Currently selected division
			tiers: [], // List of all tiers
			selectedTierID: 1, // Pretty self explanatory
			selectedDivisionID: 1, // Same ooh, ooh same ooh
			hasDivisions: true,
			servers: [],
			max: 10
		};
	},
	watch: {
		selectedTierID(tierId) {
			this.tier = _.find(this.tiers, ["id", tierId]);
			this.max = _.maxBy(this.tier.wins, "wins").wins;
			if (!_.isEmpty(this.tier.divisions)) {
				this.hasDivisions = true;
				this.selectedDivisionID = this.tier.divisions[0].id;
			} else {
				// Set division to an empty object with null image to pass coalesce in template
				this.division = { image: null };
				// Remove the divisions select from the DOM
				this.hasDivisions = false;
			}
		},
		selectedDivisionID(divisionId) {
			this.division = _.find(this.tier.divisions, ["id", divisionId]);
		}
	},
	mounted() {
		// Get list of tiers objects from db
		axios.post("/api/tiers", { service: this.service.slug }).then(response => {
			// The tiers array is that list
			this.tiers = response.data;
			// The first tier
			this.tier = _.first(response.data);
			// Get the maximum number of wins in the tier
			this.max = _.maxBy(this.tier.wins, "wins").wins;
			// Divisions of the first tier
			this.divisions = this.tier.divisions;
			// Division IV
			this.division = _.first(this.tier.divisions);
		});
		axios.get("/api/servers").then(response => (this.servers = response.data));
	}
};
</script>
