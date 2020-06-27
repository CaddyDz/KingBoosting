<template>
	<div>
		<div class="title">
			<div class="title-id">
				<h2>{{ step.id }}</h2>
			</div>
			<h2 class="title-txt">{{ step.title }}</h2>
		</div>
		<v-card raised class="mb-4">
			<v-container>
				<v-row align="center" justify="center">
					<v-col md="3">
						<img :src="division.image" :alt="tier.name" loading="lazy" width="200" />
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
										@change="selectChangeHandler({target:'tier' , value:$event})"
									></v-select>
								</v-col>
								<v-col v-if="hasDivisions">
									<v-select
										:items="divisions"
										:label="$t('Current division')"
										dense
										solo
										item-text="name"
										item-value="id"
										v-model="selectedDivisionID"
										@change="selectChangeHandler({target:'division' , value:$event})"
									></v-select>
								</v-col>
							</v-row>
							<v-select
								:items="servers"
								label="Select your server"
								dense
								solo
								v-model="leagueConfig.server"
								@change="selectChangeHandler({target:'server' , value:$event})"
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
	props: ["step"],
	data() {
		return {
			tiers: [],
			selectedTierID: 3, // Pretty self explanatory
			selectedDivisionID: 2,
			division: {}, // Currently selected division
			tier: { wins: [] }, // Currently selected tier, wins is an empty array because it's used in template
			divisions: [],
			servers: [
				"North America",
				"EU-West",
				"EU-Nordic & East",
				"Turkey",
				"Russia",
				"Brazil",
				"Latin America North",
				"Latin America South",
				"Oceania",
				"Korea",
				"PBE"
			],
			hasDivisions: true,
			leagueConfig: {
				tier: "Silver",
				division: "Division I",
				server: "EU-West"
			}
		};
	},
	methods: {
		async getTiers() {
			const response = await this.$axios.post("/tiers", {
				service: document.location.pathname
			});
			// The tiers array is that list
			this.tiers = response.data;
			// The silver tier (default)
			this.tier = _.find(response.data, { id: this.selectedTierID });
			// Get the maximum number of wins in the tier
			this.max = _.maxBy(this.tier.wins, "wins").wins;
			// Divisions of the first tier
			this.divisions = this.tier.divisions;
			// Division IV
			this.division = _.first(this.tier.divisions);
			// this.division = _.find(this.tier.divisions, {
			// 	id: this.selectedDivisionID
			// });
			// ETA
			this.eta = _.find(this.tier.wins, ["wins", 4]).eta;
			this.price = this.division.price * this.number_of_wins;
		},
		commitToStore(c) {
			this.$store.commit("boosting_order/setSelector", c);
		},
		selectChangeHandler(e) {
			this.leagueConfig[e.target] = e.value;
			this.commitToStore(this.leagueConfig);
		}
	},
	mounted() {
		this.getTiers();
		this.commitToStore(this.leagueConfig);
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
