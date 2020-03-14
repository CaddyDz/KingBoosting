<template>
	<div>
		<span>
			<v-icon color="white">mdi-cellphone-iphone</v-icon>
			<a href="/pay-with-mobile">{{ $t('Mobile Pay') }}</a>
		</span>
		<span>
			<a href="/contact">{{ $t('Contact') }}</a>
		</span>
		<span>
			<a href="/loyalty-program">{{ $t('Loyalty Program') }}</a>
		</span>
		<locale-switcher></locale-switcher>
	</div>
</template>

<style scoped>
a {
	color: white;
	text-decoration: none;
	color: linear-gradient(to right, purple 50%, white 50%);
	transition: all 500ms ease;
}
a:hover {
	color: purple !important;
}
span {
	padding-right: 10px;
	color: white;
}
.v-overflow-btn {
	margin-top: 22px;
	max-width: 51%;
}
.language-selector {
	cursor: pointer;
}
.theme--dark.v-list {
	background-color: #250f24;
	border-radius: 1px;
}
</style>

<i18n>
{
	"en": {
		"Mobile Pay": "Mobile Pay",
		"Contact": "Contact",
		"Loyalty Program": "Loyalty Program"
  },
  "fr": {
    "Mobile Pay": "Paiement Mobile",
    "Contact": "Contactez Nous",
    "Loyalty Program": "Programme de fidélité"
  }
}
</i18n>
