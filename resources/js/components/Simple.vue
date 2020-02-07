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
		<v-stepper-step step="2">Select Your Number Of Wins</v-stepper-step>
		<v-stepper-content step="1">
			<v-card raised class="mb-4" elevation="24">
				<v-container>
					<v-row justify="center" align="center">
						<v-col md="2"></v-col>
						<v-col md="8">
							<v-slider
								min="1"
								:max="max"
								thumb-label="always"
								color="purple"
								v-model="number_of_wins"
								thumb-size="50"
							>
								<template v-slot:thumb-label :style="{top: '50px'}">{{ plural(number_of_wins, "Win") }}</template>
							</v-slider>
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
		<v-stepper-step step="3">Checkout</v-stepper-step>
		<v-stepper-content step="1">
			<v-card color="black lighten-1" class="mb-12" outlined>
				<v-container>
					<!-- Checkboxes and ETA -->
					<v-row no-gutters>
						<v-col md="6">
							<v-checkbox label="Specific champions at +20% cost" prepend-icon="mdi-account-group"></v-checkbox>
							<v-checkbox label="Priority order (2x speed) at +25% cost" prepend-icon="mdi-flash"></v-checkbox>
							<v-checkbox label="With Streaming +15% cost" prepend-icon="mdi-video"></v-checkbox>
						</v-col>
						<v-col md="6">
							<!-- ETA -->
							<p class="text-right">Approximate completion time: {{ eta }}</p>
						</v-col>
					</v-row>
					<v-row>
						<v-col md="4">
							<strong class="red--text text--lighten-1">
								<s>€24.00($26.66)</s>
							</strong>
							<br />
							€{{ price }} (${{ priceUSD }}) OR 1,390
						</v-col>
						<v-col md="4">
							<v-text-field
								label="Discount Code"
								placeholder="Discount Code"
								outlined
								append-icon="mdi-check-bold"
							></v-text-field>
						</v-col>
						<v-col md="4">
							<v-btn class="float-right" outlined color="purple">BOOST ME</v-btn>
						</v-col>
					</v-row>
					<!-- Price and discount -->
					<v-row></v-row>
				</v-container>
			</v-card>
		</v-stepper-content>
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
			tier: { wins: [] }, // Currently selected tier, wins is an empty array because it's used in template
			division: {}, // Currently selected division
			tiers: [], // List of all tiers
			selectedTierID: 1, // Pretty self explanatory
			selectedDivisionID: 1, // Same ooh, ooh same ooh
			hasDivisions: true,
			servers: [],
			max: 10,
			radios: "solo",
			number_of_wins: 4,
			eta: "",
			price: 1.9
		};
	},
	watch: {
		selectedTierID(tierId) {
			this.tier = _.find(this.tiers, ["id", tierId]);
			this.max = _.maxBy(this.tier.wins, "wins").wins;
			this.eta = _.find(this.tier.wins, ["wins", value]).eta;
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
			this.eta = _.find(this.tier.wins, ["wins", value]).eta;
			this.division = _.find(this.tier.divisions, ["id", divisionId]);
		},
		number_of_wins(value) {
			// Update eta & price
			this.eta = _.find(this.tier.wins, ["wins", value]).eta;
			this.price = (this.division.price * value).toPrecision(3);
		}
	},
	computed: {
		plural(count, noun) {
			return (count, noun) => `${count} ${noun}${count !== 1 ? "s" : ""}`;
		},
		priceUSD() {
			return (this.price * 1.13).toPrecision(3);
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
			// ETA
			this.eta = _.find(this.tier.wins, ["wins", 4]).eta;
			this.price = (this.division.price * this.number_of_wins).toPrecision(3);
		});
		axios.get("/api/servers").then(response => (this.servers = response.data));
	}
};
</script>

<style lang="scss">
$slider-track-border-radius: 3px !default;
$slider-track-border-radius: 10 !default;
$slider-track-width: 2px !default;
$chip-group-no-color-opacity: 50 !default;
$slider-horizontal-margin-bottom: 0px !default;
$slider-horizontal-margin-top: 50px !default;

.v-slider--horizontal .v-slider__track-container {
	height: 20px;
}
.v-slider__track-fill {
	border-radius: 50px 0px 0px 50px;
}
.v-slider__track-background {
	border-radius: 0px 50px 50px 0px;
}
.v-input.v-input--is-label-active.v-input--is-dirty.theme--dark.v-input__slider {
	margin-top: 40px !important;
	margin-bottom: -40px !important;
}
</style>
