<template>
	<div>
		<div class="title">
			<div class="title-id">
				<h2>1</h2>
			</div>
			<h2 class="title-txt">Select Your Current League</h2>
		</div>
		<v-card raised class="mb-4" :style="{boxShadow: tier.box_shadow}">
			<v-container>
				<v-row align="center" justify="center">
					<v-col md="3">
						<img :src="division.image || tier.image" :alt="tier.name" loading="lazy" width="200" />
					</v-col>
					<v-col md="9" class="have-selectors">
						<v-container>
							<v-row>
								<v-col>
									<v-select
										:items="tiers"
										:label="$t('Current tier')"
										dense
										solo
										v-model="selectedTierID"
										item-text="name"
										item-value="id"
									></v-select>
								</v-col>
								<v-col v-if="hasDivisions">
									<v-select
										:items="divisions"
										:label="$t('Current division')"
										dense
										solo
										v-model="selectedDivisionID"
										item-text="name"
										item-value="id"
									></v-select>
								</v-col>
							</v-row>
							<v-select
								:items="servers"
								:label="$t('Select your server')"
								dense
								solo
								v-model="selectedServerID"
								item-text="region"
								item-value="id"
							></v-select>
						</v-container>
					</v-col>
				</v-row>
			</v-container>
		</v-card>
	</div>
</template>

<script>
export default {
	data() {
		return {
			// We should use the index in the array instead of the ID
			tiers: [],
			tier: { wins: [] }, // Currently selected tier, wins is an empty array because it's used in template
			selectedTierID: 3, // Silver
			divisions: [],
			selectedDivisionID: 12, // Silver I
			division: {}, // Currently selected division
			hasDivisions: true,
			selectedServerID: 2,
			servers: []
		};
	},
	watch: {
		selectedTierID(tierId) {
			this.tier = _.find(this.tiers, ["id", tierId]);
			this.$store.commit("league/changeTier", this.tier);
			// Get max num of wins and commit it to store
			// Get the maximum number of wins in the tier
			let max = _.maxBy(this.tier.wins, "wins").wins;
			this.$store.commit("wins/changeMaxNumberOfWins", max);
			if (!_.isEmpty(this.tier.divisions)) {
				// Divisions not empty, therefor less than master
				this.hasDivisions = true;
				this.divisions = this.tier.divisions;
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
	methods: {
		async getTiers() {
			const response = await this.$axios.post("/tiers", {
				service: document.location.pathname
			});
			// The tiers array is that list
			this.tiers = response.data;
			// The silver tier (default)
			this.tier = _.find(this.tiers, ["id", this.selectedTierID]);
			this.$store.commit("league/changeTier", this.tier);
			// Divisions of the first tier
			this.divisions = this.tier.divisions;
			// Division I
			this.division = _.find(this.divisions, [
				"id",
				this.selectedDivisionID
			]);
		}
	},
	mounted() {
		this.getTiers();
		this.$axios.$get("/servers").then(servers => (this.servers = servers));
	}
};
</script>

<style scoped>
.have-selectors {
	padding-right: 20px;
	padding-left: 20px;
}

.title {
	display: flex;
	flex-direction: row;
	align-items: center;
	margin-bottom: 20px;
}

.title-txt {
	margin-left: 20px;
}

.title .title-id {
	color: #181818;
}

.title-id {
	width: 50px;
	height: 50px;
	border-radius: 50%;
	background: linear-gradient(#673ab7, #9c27b0);
	display: flex;
	align-items: center;
	justify-content: center;
}
</style>

<i18n>
{
	"en": {
		"Current tier": "Current tier",
		"Current division": "Current division",
		"Select your server": "Select your server"
	}
}
</i18n>
