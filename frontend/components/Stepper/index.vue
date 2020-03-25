<template>
	<div>
		<selector :step="steps[0]" />
		<slider :step="steps[2]" />
		<options :step="steps[3]" />
	</div>
</template>

<script>
export default {
	props: ["service"],
	data() {
		return {
			steps: [
				{ id: 1, title: this.$i18n.t("Select Your Current League") },
				{ id: 2, title: this.$i18n.t("Select Your Number Of Wins") },
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
  }
}
</i18n>
