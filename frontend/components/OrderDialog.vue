<template>
	<v-stepper v-model="currentStep">
		<v-stepper-header>
			<!-- 3 templates here -->
			<template>
				<v-stepper-step key="login" :complete="isLoggedIn" step="1">{{ $t('Login') }}</v-stepper-step>
				<v-divider v-if="1 !== steps.length" key="1"></v-divider>
			</template>
			<template>
				<v-stepper-step key="order-details" :complete="currentStep > 2" step="2">{{ $t('Details') }}</v-stepper-step>
				<v-divider v-if="2 !== steps.length" key="2"></v-divider>
			</template>
			<template>
				<v-stepper-step key="pay" :complete="currentStep > 3" step="3">{{ $t('Pay') }}</v-stepper-step>
				<v-divider v-if="3 !== steps.length" key="3"></v-divider>
			</template>
		</v-stepper-header>
		<v-stepper-items>
			<login @next="nextStep($event)" @cancel="cancel" />
			<order-details @next="nextStep(2)" />
			<pay @cancel="cancel" />
		</v-stepper-items>
	</v-stepper>
</template>

<script>
export default {
	data() {
		return {
			currentStep: 1,
			valid: false,
			steps: []
		};
	},
	computed: {
		priceUSD() {
			return (this.price * this.exchangeRate).toFixed(2);
		},
		isLoggedIn() {
			return this.$store.getters["auth/isLoggedIn"];
		}
	},
	methods: {
		getBoostersList() {
			this.$axios
				.get("/getBoostersNames")
				.then(res => {
					this.boosters = res.data;
					this.boostersLoading = false;
				})
				.catch(err => {
					// TODO:
				});
		},
		sendResetPasswordEmail() {
			this.$axios
				.post("/password/email", {
					email: this.email
				})
				.then(response => {
					alert(response.data.status);
				})
				.catch(error => {
					alert(error.response.data.errors.email[0]);
				});
		},
		login() {
			this.$axios
				.post("/login", {
					email: this.email,
					password: this.password
				})
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
		closeDialog() {
			this.currentStep = 1;
			this.$emit("closeDialog");
		},
		cancel() {
			this.$emit("closeDialog");
		},
		nextStep(n) {
			if (n === this.steps.length) {
				this.currentStep = 1;
			} else {
				this.currentStep = n + 1;
			}
		}
	},
	mounted() {
		// if already logged in, jump to next step
		if (this.$store.state.auth.isLoggedIn) {
			this.currentStep = 2;
		}
		this.getBoostersList();
	}
};
</script>


<i18n>
{
		"en": {
				"Pay": "Pay",
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
				"Forgotten Password?": "Forgotten Password?",
				"Continue": "Continue",
				"Cancel":"Cancel",
				"E-mail is required": "E-mail is required",
				"E-mail must be valid": "E-mail must be valid",
				"Password is required": "Password is required",
				"Password must be longer than 8 characters": "Password must be longer than 8 characters",
				"Login": "Login",
				"Select your server": "Select your server",
				"Current division": "Current division",
				"Win": "Win",
				"Next": "Next"
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
				"Cancel":"Annuler",
				"E-mail is required": "Un e-mail est requis",
				"E-mail must be valid": "E-mail doit être valide",
				"Password is required": "Mod de passe requis",
				"Password must be longer than 8 characters": "Le mot de passe doit comporter plus de 8 caractéres",
				"Login": "S'identifier",
				"Select your server": "Sélectionnez votre serveur",
				"Current division": "Division actuelle",
				"Win": "Gagn",
				"Your Booster": "Votre Booster",
				"Next": "Prochain"
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
				"Cancel":"Annulla",
				"E-mail is required": "E-mail é obbligatorio",
				"E-mail must be valid": "E-mail Deve essere valido",
				"Password is required": "Parola d'ordine é obbligatoria",
				"Password must be longer than 8 characters": "La parola d'ordine deve contenere più di 8 caratteri",
				"Login": "Accesso",
				"Select your server": "Seleziona il tuo server",
				"Current division": "Divisione attuale",
				"Win": "Vittoria",
				"Your Booster": "IL tuo Booster",
				"Next": "Seguente"
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
				"Cancel":"Cancel",
				"E-mail is required": "E-mail is required",
				"E-mail must be valid": "E-mail must be valid",
				"Password is required": "Password is required",
				"Password must be longer than 8 characters": "Password must be longer than 8 characters",
				"Login": "Login",
				"Select your server": "Select your server",
				"Current division": "Current division",
				"Win": "Win",
				"Your Booster": "Your Booster",
				"Next": "Next"
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
				"Cancel":"Cancel",
				"E-mail is required": "E-mail is required",
				"E-mail must be valid": "E-mail must be valid",
				"Password is required": "Password is required",
				"Password must be longer than 8 characters": "Password must be longer than 8 characters",
				"Login": "Login",
				"Select your server": "Select your server",
				"Current division": "Current division",
				"Win": "Win",
				"Your Booster": "Your Booster",
				"Next": "Next"
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
				"Cancel":"Cancel",
				"E-mail is required": "E-mail is required",
				"E-mail must be valid": "E-mail must be valid",
				"Password is required": "Password is required",
				"Password must be longer than 8 characters": "Password must be longer than 8 characters",
				"Login": "Login",
				"Select your server": "Select your server",
				"Current division": "Current division",
				"Win": "Win",
				"Your Booster": "Your Booster",
				"Next": "Next"
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
				"Cancel":"Cancel",
				"E-mail is required": "E-mail is required",
				"E-mail must be valid": "E-mail must be valid",
				"Password is required": "Password is required",
				"Password must be longer than 8 characters": "Password must be longer than 8 characters",
				"Login": "Login",
				"Select your server": "Select your server",
				"Current division": "Current division",
				"Win": "Win",
				"Your Booster": "Your Booster",
				"Next": "Next"
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
				"Cancel":"Cancel",
				"E-mail is required": "E-mail is required",
				"E-mail must be valid": "E-mail must be valid",
				"Password is required": "Password is required",
				"Password must be longer than 8 characters": "Password must be longer than 8 characters",
				"Login": "Login",
				"Select your server": "Select your server",
				"Current division": "Current division",
				"Win": "Win",
				"Your Booster": "Your Booster",
				"Next": "Next"
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
				"Cancel":"Cancel",
				"E-mail is required": "E-mail is required",
				"E-mail must be valid": "E-mail must be valid",
				"Password is required": "Password is required",
				"Password must be longer than 8 characters": "Password must be longer than 8 characters",
				"Login": "Login",
				"Select your server": "Select your server",
				"Current division": "Current division",
				"Win": "Win",
				"Your Booster": "Your Booster",
				"Next": "Next"
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
				"Cancel":"Cancel",
				"E-mail is required": "E-mail is required",
				"E-mail must be valid": "E-mail must be valid",
				"Password is required": "Password is required",
				"Password must be longer than 8 characters": "Password must be longer than 8 characters",
				"Login": "Login",
				"Select your server": "Select your server",
				"Current division": "Current division",
				"Win": "Win",
				"Your Booster": "Your Booster",
				"Next": "Next"
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
				"Cancel":"Cancel",
				"E-mail is required": "E-mail is required",
				"E-mail must be valid": "E-mail must be valid",
				"Password is required": "Password is required",
				"Password must be longer than 8 characters": "Password must be longer than 8 characters",
				"Login": "Login",
				"Select your server": "Select your server",
				"Current division": "Current division",
				"Win": "Win",
				"Your Booster": "Your Booster",
				"Next": "Next"
		}
}
</i18n>
