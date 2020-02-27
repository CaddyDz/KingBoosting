<template>
	<v-stepper vertical non-linear>
		<v-stepper-step step="1">{{ $t('Select Your Current League') }}</v-stepper-step>
		<v-stepper-content step="1">
			<v-card raised class="mb-4" :style="{boxShadow: tier.box_shadow}">
				<v-container>
					<v-row align="center" justify="center">
						<v-col md="3">
							<img :src="division.image || tier.image" :alt="tier.name" />
						</v-col>
						<v-col md="9">
							<v-row>
								<v-col>
									<v-select
										:items="tiers"
										name="tier"
										:label=" $t('Current tier')"
										v-model="selectedTierID"
										item-text="name"
										item-value="id"
									></v-select>
								</v-col>
								<v-col v-if="this.hasDivisions">
									<v-select
										:items="tier.divisions"
										:label=" $t('Current division')"
										item-text="name"
										v-model="selectedDivisionID"
										item-value="id"
									></v-select>
								</v-col>
							</v-row>
							<v-select :items="servers" :label=" $t('Select your server')" item-text="region" :value="servers[0]"></v-select>
						</v-col>
					</v-row>
				</v-container>
			</v-card>
		</v-stepper-content>
		<!-- Don't put the stepper step in the component after it because it removes the vertical line between the steps -->
		<v-stepper-step step="2">{{ $t('Select Your Number Of Wins') }}</v-stepper-step>
		<number-of-wins></number-of-wins>
		<payment-section :tier="this.tier"></payment-section>
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
			tiers: [], // List of all tiers,
			divisions: [], // List of all divisions in a tier
			selectedTierID: 1, // Pretty self explanatory
			selectedDivisionID: 1, // Same ooh, ooh same ooh
			hasDivisions: true,
			servers: []
		};
	},
	computed: {
		tier() {
			return this.$store.state.currentlySelectedTier; // Currently selected tier
		},
		division() {
			return this.$store.state.currentlySelectedDivision; // Currently selected division
		}
	},
	watch: {
		selectedTierID(tierId) {
			// User selected a tier, let's commit it to store
			this.$store.commit(
				"updateCurrentlySelectedTier",
				_.find(this.tiers, ["id", tierId])
			);
			if (!_.isEmpty(this.tier.divisions)) {
				// If the selected tier has divisions
				this.hasDivisions = true;
				this.selectedDivisionID = this.tier.divisions[0].id;
			} else {
				// Set division to an empty object with null image to pass coalesce in template
				this.$store.state.currentlySelectedDivision = { image: null };
				// Remove the divisions select from the DOM
				this.hasDivisions = false;
			}
		},
		selectedDivisionID(divisionId) {
			this.$store.commit(
				"updateCurrentlySelectedDivision",
				_.find(this.tier.divisions, ["id", divisionId])
			);
		}
	},
	mounted() {
		// Get list of tiers objects from db
		axios.post("/api/tiers", { service: this.service.slug }).then(response => {
			// The tiers array is the service
			this.tiers = response.data;
			// Divisions of the first tier
			this.divisions = _.first(this.tiers).divisions;
			// Commit the first tier as the currently selected Tier
			this.$store.commit("updateCurrentlySelectedTier", _.first(this.tiers));
			// Division IV
			this.$store.commit(
				"updateCurrentlySelectedDivision",
				// this.tier is the computed property
				_.first(this.tier.divisions)
			);
			// Get the maximum number of wins in the tier, commit it to the store
			this.$store.commit(
				"updateMaximumNumberOfWins",
				_.maxBy(this.tier.wins, "wins").wins
			);
		});
		// Get servers from API
		axios.get("/api/servers").then(response => (this.servers = response.data));
	}
};
</script>

<i18n>
    {
        "en": {
            "Select Your Current League": "Select Your Current League",
            "Center": "Center",
            "Loyalty Program": "Loyalty Program",
			"Language": "Language"	
        }
    }
</i18n>