<template>
	<v-stepper vertical non-linear>
		<v-stepper-step step="1">Select Your Current League</v-stepper-step>
		<v-stepper-content step="1">
			<v-card raised class="mb-4" :style="{boxShadow: tier.box_shadow}">
				<v-container>
					<v-row align="center" justify="center">
						<v-col md="3">
							<img
								:src="division.image || tier.image"
								:alt="tier.name"
								loading="lazy"
								width="280"
								height="320"
							/>
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
								:height="50"
								:loader-height="4"
								v-model="number_of_wins"
								:thumb-size="50"
							>
								<template v-slot:progress>wach koukou!!</template>
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
							<v-checkbox
								label="Specific champions at +20% cost"
								prepend-icon="mdi-account-group"
								v-model="specificChampions"
							></v-checkbox>
							<v-checkbox
								label="Priority order (2x speed) at +25% cost"
								prepend-icon="mdi-flash"
								v-model="priorityOrder"
							></v-checkbox>
							<v-checkbox label="With Streaming +15% cost" prepend-icon="mdi-video" v-model="streaming"></v-checkbox>
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
							€{{ price.toFixed(2) }} (${{ priceUSD }}) OR 1,390
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
							<div class="text-center">
								<v-dialog v-model="dialog" width="940">
									<template v-slot:activator="{ on }">
										<v-btn class="float-right" outlined color="purple" dark v-on="on">BOOST ME</v-btn>
									</template>
									<!-- Entire stepper -->
									<v-stepper v-model="e1">
										<template>
											<v-stepper-header>
												<!-- 3 templates here -->
												<template>
													<v-stepper-step key="login" :complete="e1 > 1" :step="1" editable>Login</v-stepper-step>
													<v-divider v-if="1 !== steps" :key="1"></v-divider>
												</template>
												<template>
													<v-stepper-step key="details" :complete="e1 > 2" :step="2" editable>Details</v-stepper-step>
													<v-divider v-if="2 !== steps" :key="2"></v-divider>
												</template>
												<template>
													<v-stepper-step key="pay" :complete="e1 > 3" :step="3" editable>Pay</v-stepper-step>
													<v-divider v-if="3 !== steps" :key="3"></v-divider>
												</template>
											</v-stepper-header>
											<v-stepper-items>
												<v-stepper-content key="1-content" :step="1">
													<v-form v-model="valid">
														<v-container>
															<v-row>
																<v-col cols="12" md="6">
																	<v-text-field
																		v-model="email"
																		:rules="emailRules"
																		label="E-mail address *"
																		required
																		placeholder="Your email address"
																	></v-text-field>
																</v-col>
															</v-row>
															<v-row>
																<v-col cols="12" md="6">
																	<v-text-field
																		type="password"
																		v-model="password"
																		:rules="passwordRules"
																		:counter="10"
																		label="Password *"
																		required
																	></v-text-field>
																</v-col>
															</v-row>
														</v-container>
													</v-form>
													<v-btn text @click="sendResetPasswordEmail">Forgotten Password?</v-btn>
													<v-btn color="primary" @click="login">Continue</v-btn>
													<v-btn text>Cancel</v-btn>
												</v-stepper-content>
												<v-stepper-content key="2-content" :step="2">
													<v-form v-model="valid">
														<v-container>
															<v-row>
																<!-- Summoner -->
																<v-col cols="12" md="6">
																	<v-text-field
																		v-model="nickname"
																		label="Nickname *"
																		required
																		placeholder="Your in-game name"
																	></v-text-field>
																</v-col>
																<!-- Select Booster -->
																<v-col class="d-flex" cols="12" sm="6">
																	<v-select :items="boosters" label="Your Booster" :loading="boostersLoading"></v-select>
																</v-col>
															</v-row>
															<v-row>
																<v-col cols="12" md="6">
																	<v-text-field
																		v-model="comment"
																		label="Comments NOT REQUIRED"
																		placeholder="Your comments"
																		required
																	></v-text-field>
																</v-col>
															</v-row>
															<v-row>
																<v-col cols="12" md="6">
																	<v-checkbox
																		label="Appear Offline on Chat"
																		prepend-icon="mdi-account"
																		v-model="offline"
																	></v-checkbox>
																</v-col>
															</v-row>
															<v-row>
																<v-col cols="12" md="6">
																	<v-icon>mdi-information-outline</v-icon>
																	<span>Further information will be requested after payment</span>
																</v-col>
															</v-row>
														</v-container>
													</v-form>
													<v-btn text @click="sendResetPasswordEmail">Forgotten Password?</v-btn>
													<v-btn color="primary" @click="login">Continue</v-btn>
													<v-btn text>Cancel</v-btn>
												</v-stepper-content>
												<v-stepper-content key="3-content" :step="3">
													<v-card class="mb-12" color="grey lighten-1" height="200px"></v-card>
													<v-btn color="primary" @click="nextStep(3)">Continue</v-btn>
													<v-btn text>Cancel</v-btn>
												</v-stepper-content>
											</v-stepper-items>
										</template>
									</v-stepper>
								</v-dialog>
							</div>
						</v-col>
					</v-row>
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
			// Stepper for services tabs
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
			price: 1.9,
			// Default exchange rate, to be changed by API call
			exchangeRate: 1.1003,
			specificChampions: false,
			priorityOrder: false,
			streaming: false,
			dialog: false,
			// Modal stepper
			e1: 1,
			steps: 3,
			// Login form data
			valid: false,
			email: "",
			emailRules: [
				v => !!v || "E-mail is required",
				v => /.+@.+/.test(v) || "E-mail must be valid"
			],
			password: "",
			passwordRules: [
				v => !!v || "Password is required",
				v => v.length > 8 || "Password must be longer than 8 characters"
			],
			// Details form data
			nickname: "",
			comment: "",
			offline: false,
			boosters: [], // Get it from local API call,
			boostersLoading: false
		};
	},
	computed: {
		plural(count, noun) {
			return (count, noun) => `${count} ${noun}${count !== 1 ? "s" : ""}`;
		},
		priceUSD() {
			return (this.price * this.exchangeRate).toFixed(2);
		}
	},
	methods: {
		pricePlusPercent(percent) {
			return (
				this.price + this.division.price * (percent / 100) * this.number_of_wins
			);
		},
		priceMinusPercent(percent) {
			return (
				this.price - this.division.price * (percent / 100) * this.number_of_wins
			);
		},
		onInput(val) {
			this.steps = parseInt(val);
		},
		nextStep(n) {
			if (n === this.steps) {
				this.e1 = 1;
			} else {
				this.e1 = n + 1;
			}
		},
		sendResetPasswordEmail() {
			axios
				.post("password/email", { email: this.email })
				.then(response => {
					alert(response.data.status);
				})
				.catch(error => {
					alert(error.response.data.errors.email[0]);
				});
		},
		login() {
			axios
				.post("/login", { email: this.email, password: this.password })
				.then(response => {
					alert(response.data.status);
					this.nextStep(1);
					// Make API call to get boosters
					this.getBoostersList();
				})
				.catch(error => {
					alert(error.response.data.errors);
				});
		},
		getBoostersList() {
			this.boostersLoading = true;
			axios.get("/api/getBoostersNames").then(response => {
				this.boosters = response.data;
				this.boostersLoading = false;
			});
		}
	},
	watch: {
		selectedTierID(tierId) {
			this.tier = _.find(this.tiers, ["id", tierId]);
			this.max = _.maxBy(this.tier.wins, "wins").wins;
			this.eta = _.find(this.tier.wins, ["wins", this.number_of_wins]).eta;
			if (!_.isEmpty(this.tier.divisions)) {
				// Divisions not empty, therefor less than master
				this.hasDivisions = true;
				this.selectedDivisionID = this.tier.divisions[0].id;
			} else {
				// Set division to an empty object with null image to pass coalesce in template
				this.division = { image: null };
				// Remove the divisions select from the DOM
				this.hasDivisions = false;
				// Set price to tier's price
				this.price = parseFloat(this.tier.price);
			}
		},
		selectedDivisionID(divisionId) {
			this.eta = _.find(this.tier.wins, ["wins", this.number_of_wins]).eta;
			this.division = _.find(this.tier.divisions, ["id", divisionId]);
		},
		number_of_wins(value) {
			// Update eta & price
			this.eta = _.find(this.tier.wins, ["wins", value]).eta;
			this.price = this.division.price * value;
		},
		specificChampions(value) {
			if (value) {
				// If checked
				this.price = this.pricePlusPercent(20);
			} else {
				// If unchecked
				this.price = this.priceMinusPercent(20);
			}
		},
		priorityOrder(value) {
			if (value) {
				// If checked
				this.price = this.pricePlusPercent(25);
			} else {
				// If unchecked
				this.price = this.priceMinusPercent(25);
			}
		},
		streaming(value) {
			if (value) {
				// If checked
				this.price = this.pricePlusPercent(15);
			} else {
				// If unchecked
				this.price = this.priceMinusPercent(15);
			}
		},
		steps(val) {
			if (this.e1 > val) {
				this.e1 = val;
			}
		}
	},
	mounted() {
		// Get live USD to EUR exchange rate
		axios
			.get("https://api.exchangeratesapi.io/latest?symbols=USD")
			.then(response => {
				this.exchangeRate = response.data.rates.USD;
			});
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
			this.price = this.division.price * this.number_of_wins;
		});
		axios.get("/api/servers").then(response => (this.servers = response.data));
	}
};
</script>

<style lang="scss">
.v-input.v-input--is-label-active.v-input--is-dirty.theme--dark.v-input__slider {
	margin-top: 40px !important;
	margin-bottom: -40px !important;
}
</style>
