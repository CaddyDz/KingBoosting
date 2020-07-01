<template>
	<v-stepper vertical non-linear>
		<v-stepper-step step="1">{{ $t('Select Your Current League') }}</v-stepper-step>
		<v-stepper-content step="1">
			<v-card raised class="mb-4" :style="{boxShadow: tier.box_shadow}">
				<v-container>
					<v-row align="center" justify="center">
						<v-col md="3">
							<img :src="division.image || tier.image" :alt="tier.name" loading="lazy" width="200" />
						</v-col>
						<v-col md="9">
							<v-row>
								<v-col>
									<v-select
										:items="tiers"
										name="tier"
										:label="$t('Current tier')"
										v-model="selectedTierID"
										item-text="name"
										item-value="id"
									></v-select>
								</v-col>
								<v-col v-if="this.hasDivisions">
									<v-select
										:items="tier.divisions"
										:label="$t('Current division')"
										item-text="name"
										v-model="selectedDivisionID"
										item-value="id"
									></v-select>
								</v-col>
							</v-row>
							<v-select
								:items="servers"
								:label="$t('Select your server')"
								item-text="region"
								:value="servers[0]"
							></v-select>
						</v-col>
					</v-row>
				</v-container>
			</v-card>
		</v-stepper-content>
		<v-stepper-step step="2">{{ $t('Select Your Number Of Wins') }}</v-stepper-step>
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
								<template v-slot:thumb-label :style="{top: '50px'}">{{ plural(number_of_wins, $t('Win')) }}</template>
							</v-slider>
						</v-col>
						<v-col md="2"></v-col>
					</v-row>
					<v-row>
						<v-col md="3"></v-col>
						<v-col md="6">
							<v-radio-group row v-model="radios">
								<v-icon>mdi-account</v-icon>
								<v-radio :label="$t('Solo/Duo')" value="solo"></v-radio>
								<v-icon>mdi-account-group</v-icon>
								<v-radio :label="$t('Flex 5v5')" value="flex"></v-radio>
							</v-radio-group>
						</v-col>
						<v-col md="3"></v-col>
					</v-row>
				</v-container>
			</v-card>
		</v-stepper-content>
		<v-stepper-step step="3">{{ $t('Checkout') }}</v-stepper-step>
		<v-stepper-content step="1">
			<v-card color="black lighten-1" class="mb-12" outlined>
				<v-container>
					<!-- Checkboxes and ETA -->
					<v-row no-gutters>
						<v-col md="6">
							<v-checkbox
								:label="$t('Specific champions at +20% cost')"
								prepend-icon="mdi-account-group"
								v-model="specificChampions"
							></v-checkbox>
							<v-checkbox
								:label="$t('Priority order (2x speed) at +25% cost')"
								prepend-icon="mdi-flash"
								v-model="priorityOrder"
							></v-checkbox>
							<v-checkbox
								:label="$t('With Streaming +15% cost')"
								prepend-icon="mdi-video"
								v-model="streaming"
							></v-checkbox>
						</v-col>
						<v-col md="6">
							<!-- ETA -->
							<p class="text-right">{{ $t('Approximate completion time:') }} {{ eta }}</p>
						</v-col>
					</v-row>
					<v-row>
						<v-col md="4">
							<strong class="red--text text--lighten-1">
								<s>€24.00($26.66)</s>
							</strong>
							<br />
							€{{ price.toFixed(2) }} (${{ priceUSD }})
						</v-col>
						<v-col md="4">
							<v-text-field
								:label="$t('Discount Code')"
								:placeholder="$t('Discount Code')"
								outlined
								append-icon="mdi-check-bold"
							></v-text-field>
						</v-col>
						<v-col md="4">
							<div class="text-center">
								<v-dialog v-model="dialog" width="940">
									<template v-slot:activator="{ on }">
										<v-btn class="float-right" outlined color="purple" dark v-on="on">{{ $t('BOOST ME') }}</v-btn>
									</template>
									<!-- Entire stepper -->
									<v-stepper v-model="e1">
										<template>
											<v-stepper-header>
												<!-- 3 templates here -->
												<template>
													<v-stepper-step key="login" :complete="isLoggedIn" :step="1">{{ $t('Login') }}</v-stepper-step>
													<v-divider v-if="1 !== steps" :key="1"></v-divider>
												</template>
												<template>
													<v-stepper-step key="details" :complete="e1 > 2" :step="2" editable>{{ $t('Details') }}</v-stepper-step>
													<v-divider v-if="2 !== steps" :key="2"></v-divider>
												</template>
												<template>
													<v-stepper-step key="pay" :complete="e1 > 3" :step="3" editable>Pay</v-stepper-step>
													<v-divider v-if="3 !== steps" :key="3"></v-divider>
												</template>
											</v-stepper-header>
											<v-stepper-items>
												<v-stepper-content key="1-content" :step="1">
													<!-- If user is already logged in, this step should be skipped -->
													<LoginComponent></LoginComponent>
												</v-stepper-content>
												<v-stepper-content key="2-content" :step="2">
													<v-form v-model="valid">
														<v-container>
															<v-row>
																<!-- Summoner -->
																<v-col cols="12" md="6">
																	<v-text-field
																		v-model="nickname"
																		:label="$t('Nickname *')"
																		required
																		:placeholder="$t('Your in-game name')"
																	></v-text-field>
																</v-col>
																<!-- Select Booster -->
																<v-col class="d-flex" cols="12" sm="6">
																	<v-select :items="boosters" :label="$t('Your Booster')" :loading="boostersLoading"></v-select>
																</v-col>
															</v-row>
															<v-row>
																<v-col cols="12" md="6">
																	<v-text-field
																		v-model="comment"
																		:label="$t('Comments NOT REQUIRED')"
																		:placeholder="$t('Your comments')"
																		required
																	></v-text-field>
																</v-col>
															</v-row>
															<v-row>
																<v-col cols="12" md="6">
																	<v-checkbox
																		:label="$t('Appear Offline on Chat')"
																		prepend-icon="mdi-account"
																		v-model="offline"
																	></v-checkbox>
																</v-col>
															</v-row>
															<v-row>
																<v-col cols="12" md="6">
																	<v-icon>mdi-information-outline</v-icon>
																	<span>{{ $t('Further information will be requested after payment') }}</span>
																</v-col>
															</v-row>
														</v-container>
													</v-form>
													<v-btn text @click="sendResetPasswordEmail">{{ $t('Forgotten Password?') }}</v-btn>
													<v-btn color="primary" @click="login">{{ $t('Continue') }}</v-btn>
													<v-btn text>{{ $t('Cancel') }}</v-btn>
												</v-stepper-content>
												<v-stepper-content key="3-content" :step="3">
													<v-card class="mb-12" color="grey lighten-1" height="200px"></v-card>
													<v-btn color="primary" @click="nextStep(3)">{{ $t('Continue') }}</v-btn>
													<v-btn text>{{ $t('Cancel') }}</v-btn>
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
				v => !!v || this.$i18n.t("E-mail is required"),
				v => /.+@.+/.test(v) || this.$i18n.t("E-mail must be valid")
			],
			password: "",
			passwordRules: [
				v => !!v || this.$i18n.t("Password is required"),
				v =>
					v.length > 8 ||
					this.$i18n.t("Password must be longer than 8 characters")
			],
			// Details form data
			nickname: "",
			comment: "",
			offline: false,
			boosters: [], // Get it from local API call,
			boostersLoading: false,
			register: false
		};
	},
	computed: {
		plural(count, noun) {
			return (count, noun) => `${count} ${noun}${count !== 1 ? "s" : ""}`;
		},
		priceUSD() {
			return (this.price * this.exchangeRate).toFixed(2);
		},
		isLoggedIn() {
			return this.$store.getters.isLoggedIn;
		}
	},
	methods: {
		// Get list of tiers objects from db
		async getTiers() {
			const response = await this.$axios.post("/tiers", {
				service: this.service.slug
			});
			// The tiers array is that list
			this.tiers = response;
			// The first tier
			this.tier = _.first(response);
			// Get the maximum number of wins in the tier
			this.max = _.maxBy(this.tier.wins, "wins").wins;
			// Divisions of the first tier
			this.divisions = this.tier.divisions;
			// Division IV
			this.division = _.first(this.tier.divisions);
			// ETA
			this.eta = _.find(this.tier.wins, ["wins", 4]).eta;
			this.price = this.division.price * this.number_of_wins;
		},
		pricePlusPercent(percent) {
			return (
				this.price +
				this.division.price * (percent / 100) * this.number_of_wins
			);
		},
		priceMinusPercent(percent) {
			return (
				this.price -
				this.division.price * (percent / 100) * this.number_of_wins
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
			this.$axios
				.post("/password/email", { email: this.email })
				.then(response => {
					alert(response.data.status);
				})
				.catch(error => {
					alert(error.response.data.errors.email[0]);
				});
		},
		login() {
			this.$axios
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
		async getBoostersList() {
			const boosters = await this.$axios.get("/getBoostersNames");
			this.boosters = boosters;
			this.boostersLoading = true;
		}
	},
	watch: {
		selectedTierID(tierId) {
			this.tier = _.find(this.tiers, ["id", tierId]);
			this.max = _.maxBy(this.tier.wins, "wins").wins;
			this.eta = _.find(this.tier.wins, [
				"wins",
				this.number_of_wins
			]).eta;
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
			this.eta = _.find(this.tier.wins, [
				"wins",
				this.number_of_wins
			]).eta;
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
		this.getTiers();
		// Get live USD to EUR exchange rate
		this.$axios
			.get("https://api.exchangeratesapi.io/latest?symbols=USD")
			.then(response => {
				this.exchangeRate = response.data.rates.USD;
			});
		this.$axios.$get("/servers").then(servers => (this.servers = servers));
		// Jump to second step if user is already logged in
		if (this.isLoggedIn) {
			this.e1 = 2;
			// Get boosters list
			this.getBoostersList();
		} else {
			// Logged out
		}
	}
};
</script>

<style>
.v-input.v-input--is-label-active.v-input--is-dirty.theme--dark.v-input__slider {
	margin-top: 40px !important;
	margin-bottom: -40px !important;
}
</style>

<i18n>
{
		"en": {
				"Select Your Current League": "Select Your Current League",
				"Current tier": "Current tier",
				"Select Your Number Of Wins": "Select Your Number Of Wins",
				"Solo/Duo": "Solo/Duo",
				"Flex 5v5": "Flex 5v5",
				"Checkout": "Checkout",
				"Specific champions at +20% cost": "Specific champions at +20% cost",
				"Priority order (2x speed) at +25% cost": "Priority order (2x speed) at +25% cost",
				"With Streaming +15% cost": "With Streaming +15% cost",
				"Approximate completion time:": "Approximate completion time:",
				"Discount Code": "Discount Code",
				"BOOST ME": "BOOST ME",
				"Details": "Details",
				"Nickname *": "Nickname *",
				"Your in-game name": "Your in-game name",
				"Comments NOT REQUIRED": "Comments NOT REQUIRED",
				"Your comments": "Your comments",
				"Appear Offline on Chat": "Appear Offline on Chat",
				"Further information will be requested after payment": "Further information will be requested after payment",
				"Forgotten Password?": "Forgotten Password?",
				"Continue": "Continue",
				"Cancel": "Cancel",
				"E-mail is required": "E-mail is required",
				"E-mail must be valid": "E-mail must be valid",
				"Password is required": "Password is required",
				"Password must be longer than 8 characters": "Password must be longer than 8 characters",
				"Login": "Login",
				"Select your server": "Select your server",
				"Current division": "Current division",
				"Win": "Win",
				"Your Booster": "Your Booster"
		},
		"fr": {
				"Select Your Current League": "Sélectionnez votre ligue actuelle",
				"Current tier": "Niveau actuel",
				"Select Your Number Of Wins": "Sélectionnez votre nombre de victoires",
				"Solo/Duo": "Solo/Duo",
				"Flex 5v5": "Flex 5v5",
				"Checkout": "Checkout",
				"Specific champions at +20% cost": "Champions spécifiques à +20% de Coût",
				"Priority order (2x speed) at +25% cost": "Commande prioritaire (2x vitesse) à +25% de Coût",
				"With Streaming +15% cost": "Avec Streaming +15% de Coût",
				"Approximate completion time:": "Temps d'achévement approximatif:",
				"Discount Code": "Code de réduction",
				"BOOST ME": "BOOST ME",
				"Details": "Détails",
				"Nickname *": "Surnom *",
				"Your in-game name": "Votre nom dans le jeu",
				"Comments NOT REQUIRED": "Commentaires NON REQUIS",
				"Your comments": "Vos commentaires",
				"Appear Offline on Chat": "apparaître hors ligne sur le chat",
				"Further information will be requested after payment": "De plus amples information seront demandées aprèes le paiement",
				"Forgotten Password?": "Mot de passe oublié?",
				"Continue": "Continuer",
				"Cancel": "Annuler",
				"E-mail is required": "Un e-mail est requis",
				"E-mail must be valid": "E-mail doit être valide",
				"Password is required": "Mod de passe requis",
				"Password must be longer than 8 characters": "Le mot de passe doit comporter plus de 8 caractéres",
				"Login": "S'identifier",
				"Select your server": "Sélectionnez votre serveur",
				"Current division": "Division actuelle",
				"Win": "Gagn",
				"Your Booster": "Votre Booster"
		},
		"it": {
				"Select Your Current League": "Seleziona la tua lega attuale",
				"Current tier": "Livello attuale",
				"Select Your Number Of Wins": "Seleziona Il Tuo Numero Di Vittorie",
				"Solo/Duo": "Solo/Duo",
				"Flex 5v5": "Flex 5v5",
				"Checkout": "Verificazione",
				"Specific champions at +20% cost": "Campioni specifici a +20% di costo",
				"Priority order (2x speed) at +25% cost": "Ordine prioritario (2x velocità) a +25% di costo",
				"With Streaming +15% cost": "Con Streaming +15% di costo",
				"Approximate completion time:": "Tempo approssimativo di completamento:",
				"Discount Code": "Codice di sconto",
				"BOOST ME": "BOOST ME",
				"Details": "Dettagli",
				"Nickname *": "Soprannome *",
				"Your in-game name": "Il tuo nome nel gioco",
				"Comments NOT REQUIRED": "Commento non Richiesto",
				"Your comments": "Il tuo commento",
				"Appear Offline on Chat": "Appare Offline in Chat",
				"Further information will be requested after payment": "Ulteriori informazioni saranno richieste dopo il pagamento",
				"Forgotten Password?": "La Parola d'ordine é dimenticata?",
				"Continue": "Continua",
				"Cancel": "Annulla",
				"E-mail is required": "E-mail é obbligatorio",
				"E-mail must be valid": "E-mail Deve essere valido",
				"Password is required": "Parola d'ordine é obbligatoria",
				"Password must be longer than 8 characters": "La parola d'ordine deve contenere più di 8 caratteri",
				"Login": "Accesso",
				"Select your server": "Seleziona il tuo server",
				"Current division": "Divisione attuale",
				"Win": "Vittoria",
				"Your Booster": "IL tuo Booster"
		},
		"de": {
				"Select Your Current League": "Select Your Current League",
				"Current tier": "Current tier",
				"Select Your Number Of Wins": "Select Your Number Of Wins",
				"Solo/Duo": "Solo/Duo",
				"Flex 5v5": "Flex 5v5",
				"Checkout": "Checkout",
				"Specific champions at +20% cost": "Specific champions at +20% cost",
				"Priority order (2x speed) at +25% cost": "Priority order (2x speed) at +25% cost",
				"With Streaming +15% cost": "With Streaming +15% cost",
				"Approximate completion time:": "Approximate completion time:",
				"Discount Code": "Discount Code",
				"BOOST ME": "BOOST ME",
				"Details": "Details",
				"Nickname *": "Nickname *",
				"Your in-game name": "Your in-game name",
				"Comments NOT REQUIRED": "Comments NOT REQUIRED",
				"Your comments": "Your comments",
				"Appear Offline on Chat": "Appear Offline on Chat",
				"Further information will be requested after payment": "Further information will be requested after payment",
				"Forgotten Password?": "Forgotten Password?",
				"Continue": "Continue",
				"Cancel": "Cancel",
				"E-mail is required": "E-mail is required",
				"E-mail must be valid": "E-mail must be valid",
				"Password is required": "Password is required",
				"Password must be longer than 8 characters": "Password must be longer than 8 characters",
				"Login": "Login",
				"Select your server": "Select your server",
				"Current division": "Current division",
				"Win": "Win",
				"Your Booster": "Your Booster"
		},
		"at": {
				"Select Your Current League": "Select Your Current League",
				"Current tier": "Current tier",
				"Select Your Number Of Wins": "Select Your Number Of Wins",
				"Solo/Duo": "Solo/Duo",
				"Flex 5v5": "Flex 5v5",
				"Checkout": "Checkout",
				"Specific champions at +20% cost": "Specific champions at +20% cost",
				"Priority order (2x speed) at +25% cost": "Priority order (2x speed) at +25% cost",
				"With Streaming +15% cost": "With Streaming +15% cost",
				"Approximate completion time:": "Approximate completion time:",
				"Discount Code": "Discount Code",
				"BOOST ME": "BOOST ME",
				"Details": "Details",
				"Nickname *": "Nickname *",
				"Your in-game name": "Your in-game name",
				"Comments NOT REQUIRED": "Comments NOT REQUIRED",
				"Your comments": "Your comments",
				"Appear Offline on Chat": "Appear Offline on Chat",
				"Further information will be requested after payment": "Further information will be requested after payment",
				"Forgotten Password?": "Forgotten Password?",
				"Continue": "Continue",
				"Cancel": "Cancel",
				"E-mail is required": "E-mail is required",
				"E-mail must be valid": "E-mail must be valid",
				"Password is required": "Password is required",
				"Password must be longer than 8 characters": "Password must be longer than 8 characters",
				"Login": "Login",
				"Select your server": "Select your server",
				"Current division": "Current division",
				"Win": "Win",
				"Your Booster": "Your Booster"
		},
		"swiss": {
				"Select Your Current League": "Select Your Current League",
				"Current tier": "Current tier",
				"Select Your Number Of Wins": "Select Your Number Of Wins",
				"Solo/Duo": "Solo/Duo",
				"Flex 5v5": "Flex 5v5",
				"Checkout": "Checkout",
				"Specific champions at +20% cost": "Specific champions at +20% cost",
				"Priority order (2x speed) at +25% cost": "Priority order (2x speed) at +25% cost",
				"With Streaming +15% cost": "With Streaming +15% cost",
				"Approximate completion time:": "Approximate completion time:",
				"Discount Code": "Discount Code",
				"BOOST ME": "BOOST ME",
				"Details": "Details",
				"Nickname *": "Nickname *",
				"Your in-game name": "Your in-game name",
				"Comments NOT REQUIRED": "Comments NOT REQUIRED",
				"Your comments": "Your comments",
				"Appear Offline on Chat": "Appear Offline on Chat",
				"Further information will be requested after payment": "Further information will be requested after payment",
				"Forgotten Password?": "Forgotten Password?",
				"Continue": "Continue",
				"Cancel": "Cancel",
				"E-mail is required": "E-mail is required",
				"E-mail must be valid": "E-mail must be valid",
				"Password is required": "Password is required",
				"Password must be longer than 8 characters": "Password must be longer than 8 characters",
				"Login": "Login",
				"Select your server": "Select your server",
				"Current division": "Current division",
				"Win": "Win",
				"Your Booster": "Your Booster"
		},
		"es": {
				"Select Your Current League": "Select Your Current League",
				"Current tier": "Current tier",
				"Select Your Number Of Wins": "Select Your Number Of Wins",
				"Solo/Duo": "Solo/Duo",
				"Flex 5v5": "Flex 5v5",
				"Checkout": "Checkout",
				"Specific champions at +20% cost": "Specific champions at +20% cost",
				"Priority order (2x speed) at +25% cost": "Priority order (2x speed) at +25% cost",
				"With Streaming +15% cost": "With Streaming +15% cost",
				"Approximate completion time:": "Approximate completion time:",
				"Discount Code": "Discount Code",
				"BOOST ME": "BOOST ME",
				"Details": "Details",
				"Nickname *": "Nickname *",
				"Your in-game name": "Your in-game name",
				"Comments NOT REQUIRED": "Comments NOT REQUIRED",
				"Your comments": "Your comments",
				"Appear Offline on Chat": "Appear Offline on Chat",
				"Further information will be requested after payment": "Further information will be requested after payment",
				"Forgotten Password?": "Forgotten Password?",
				"Continue": "Continue",
				"Cancel": "Cancel",
				"E-mail is required": "E-mail is required",
				"E-mail must be valid": "E-mail must be valid",
				"Password is required": "Password is required",
				"Password must be longer than 8 characters": "Password must be longer than 8 characters",
				"Login": "Login",
				"Select your server": "Select your server",
				"Current division": "Current division",
				"Win": "Win",
				"Your Booster": "Your Booster"
		},
		"nl": {
				"Select Your Current League": "Select Your Current League",
				"Current tier": "Current tier",
				"Select Your Number Of Wins": "Select Your Number Of Wins",
				"Solo/Duo": "Solo/Duo",
				"Flex 5v5": "Flex 5v5",
				"Checkout": "Checkout",
				"Specific champions at +20% cost": "Specific champions at +20% cost",
				"Priority order (2x speed) at +25% cost": "Priority order (2x speed) at +25% cost",
				"With Streaming +15% cost": "With Streaming +15% cost",
				"Approximate completion time:": "Approximate completion time:",
				"Discount Code": "Discount Code",
				"BOOST ME": "BOOST ME",
				"Details": "Details",
				"Nickname *": "Nickname *",
				"Your in-game name": "Your in-game name",
				"Comments NOT REQUIRED": "Comments NOT REQUIRED",
				"Your comments": "Your comments",
				"Appear Offline on Chat": "Appear Offline on Chat",
				"Further information will be requested after payment": "Further information will be requested after payment",
				"Forgotten Password?": "Forgotten Password?",
				"Continue": "Continue",
				"Cancel": "Cancel",
				"E-mail is required": "E-mail is required",
				"E-mail must be valid": "E-mail must be valid",
				"Password is required": "Password is required",
				"Password must be longer than 8 characters": "Password must be longer than 8 characters",
				"Login": "Login",
				"Select your server": "Select your server",
				"Current division": "Current division",
				"Win": "Win",
				"Your Booster": "Your Booster"
		},
		"se": {
				"Select Your Current League": "Select Your Current League",
				"Current tier": "Current tier",
				"Select Your Number Of Wins": "Select Your Number Of Wins",
				"Solo/Duo": "Solo/Duo",
				"Flex 5v5": "Flex 5v5",
				"Checkout": "Checkout",
				"Specific champions at +20% cost": "Specific champions at +20% cost",
				"Priority order (2x speed) at +25% cost": "Priority order (2x speed) at +25% cost",
				"With Streaming +15% cost": "With Streaming +15% cost",
				"Approximate completion time:": "Approximate completion time:",
				"Discount Code": "Discount Code",
				"BOOST ME": "BOOST ME",
				"Details": "Details",
				"Nickname *": "Nickname *",
				"Your in-game name": "Your in-game name",
				"Comments NOT REQUIRED": "Comments NOT REQUIRED",
				"Your comments": "Your comments",
				"Appear Offline on Chat": "Appear Offline on Chat",
				"Further information will be requested after payment": "Further information will be requested after payment",
				"Forgotten Password?": "Forgotten Password?",
				"Continue": "Continue",
				"Cancel": "Cancel",
				"E-mail is required": "E-mail is required",
				"E-mail must be valid": "E-mail must be valid",
				"Password is required": "Password is required",
				"Password must be longer than 8 characters": "Password must be longer than 8 characters",
				"Login": "Login",
				"Select your server": "Select your server",
				"Current division": "Current division",
				"Win": "Win",
				"Your Booster": "Your Booster"
		},
		"ne": {
				"Select Your Current League": "Select Your Current League",
				"Current tier": "Current tier",
				"Select Your Number Of Wins": "Select Your Number Of Wins",
				"Solo/Duo": "Solo/Duo",
				"Flex 5v5": "Flex 5v5",
				"Checkout": "Checkout",
				"Specific champions at +20% cost": "Specific champions at +20% cost",
				"Priority order (2x speed) at +25% cost": "Priority order (2x speed) at +25% cost",
				"With Streaming +15% cost": "With Streaming +15% cost",
				"Approximate completion time:": "Approximate completion time:",
				"Discount Code": "Discount Code",
				"BOOST ME": "BOOST ME",
				"Details": "Details",
				"Nickname *": "Nickname *",
				"Your in-game name": "Your in-game name",
				"Comments NOT REQUIRED": "Comments NOT REQUIRED",
				"Your comments": "Your comments",
				"Appear Offline on Chat": "Appear Offline on Chat",
				"Further information will be requested after payment": "Further information will be requested after payment",
				"Forgotten Password?": "Forgotten Password?",
				"Continue": "Continue",
				"Cancel": "Cancel",
				"E-mail is required": "E-mail is required",
				"E-mail must be valid": "E-mail must be valid",
				"Password is required": "Password is required",
				"Password must be longer than 8 characters": "Password must be longer than 8 characters",
				"Login": "Login",
				"Select your server": "Select your server",
				"Current division": "Current division",
				"Win": "Win",
				"Your Booster": "Your Booster"
		},
		"dk": {
				"Select Your Current League": "Select Your Current League",
				"Current tier": "Current tier",
				"Select Your Number Of Wins": "Select Your Number Of Wins",
				"Solo/Duo": "Solo/Duo",
				"Flex 5v5": "Flex 5v5",
				"Checkout": "Checkout",
				"Specific champions at +20% cost": "Specific champions at +20% cost",
				"Priority order (2x speed) at +25% cost": "Priority order (2x speed) at +25% cost",
				"With Streaming +15% cost": "With Streaming +15% cost",
				"Approximate completion time:": "Approximate completion time:",
				"Discount Code": "Discount Code",
				"BOOST ME": "BOOST ME",
				"Details": "Details",
				"Nickname *": "Nickname *",
				"Your in-game name": "Your in-game name",
				"Comments NOT REQUIRED": "Comments NOT REQUIRED",
				"Your comments": "Your comments",
				"Appear Offline on Chat": "Appear Offline on Chat",
				"Further information will be requested after payment": "Further information will be requested after payment",
				"Forgotten Password?": "Forgotten Password?",
				"Continue": "Continue",
				"Cancel": "Cancel",
				"E-mail is required": "E-mail is required",
				"E-mail must be valid": "E-mail must be valid",
				"Password is required": "Password is required",
				"Password must be longer than 8 characters": "Password must be longer than 8 characters",
				"Login": "Login",
				"Select your server": "Select your server",
				"Current division": "Current division",
				"Win": "Win",
				"Your Booster": "Your Booster"
		}
}
</i18n>
