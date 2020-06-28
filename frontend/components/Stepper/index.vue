<template>
	<div>
		<SelectCurrentLeague />
		<SelectNumberOfWins />
		<!-- <options :step="steps[3]" /> -->
	</div>
</template>

<script>
export default {
	data() {
		return {
			steps: [
				{ id: 2, title: this.$i18n.t("Select Your Number Of Wins") },
				{ id: 3, title: this.$i18n.t("Checkout") }
			]
		};
	}
};
</script>
<i18n>
{
	"en": {
		"Select Your Current League": "Select Your Current League",
		"Select Your Number Of Wins": "Select Your Number Of Wins",
		"Checkout": "Checkout"
	},
		"fr": {
			"Select Your Current League": "Sélectionnez votre ligue actuelle",
			"Select Your Number Of Wins": "Sélectionnez votre nombre de victoires",
			"Checkout":"Paiment"
	},
		"it": {
		"Select Your Current League": "Seleziona la tua lega attuale",
		"Select Your Number Of Wins": "Seleziona il tuo numero di vittorie",
		"Checkout": "Pagamento"
	},
		"de": {
		"Select Your Current League": "Select Your Current League",
		"Select Your Number Of Wins": "Select Your Number Of Wins",
		"Checkout": "Checkout"
	},
	"at": {
		"Select Your Current League": "Select Your Current League",
		"Select Your Number Of Wins": "Select Your Number Of Wins",
		"Checkout": "Checkout"
	},
	"swiss": {
		"Select Your Current League": "Select Your Current League",
		"Select Your Number Of Wins": "Select Your Number Of Wins",
		"Checkout": "Checkout"
	},
	"es": {
		"Select Your Current League": "Select Your Current League",
		"Select Your Number Of Wins": "Select Your Number Of Wins",
		"Checkout": "Checkout"
	},
	"nl": {
		"Select Your Current League": "Select Your Current League",
		"Select Your Number Of Wins": "Select Your Number Of Wins",
		"Checkout": "Checkout"
	},
	"se": {
		"Select Your Current League": "Select Your Current League",
		"Select Your Number Of Wins": "Select Your Number Of Wins",
		"Checkout": "Checkout"
	},
	"ne": {
		"Select Your Current League": "Select Your Current League",
		"Select Your Number Of Wins": "Select Your Number Of Wins",
		"Checkout": "Checkout"
	},
	"dk": {
		"Select Your Current League": "Select Your Current League",
		"Select Your Number Of Wins": "Select Your Number Of Wins",
		"Checkout": "Checkout"
	}
}
</i18n>
