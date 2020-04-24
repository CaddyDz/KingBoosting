<template>
	<v-card color="black">
		<v-row class="pa-3">
			<v-col class="pb-1">
				<div class="font-weight-medium">
					<v-icon small>mdi-account-check</v-icon>
					<span>{{ $t('Social Login') }}</span>
				</div>
			</v-col>
			<v-col class="pb-1">
				<v-btn color="#2C98F0" block class="py-1" href="/login/facebook">
					<v-icon small>mdi-facebook</v-icon>
					<span>{{ $t('LOGIN WITH FACEBOOK') }}</span>
				</v-btn>
			</v-col>
			<v-col class="pb-1">
				<v-btn color="#F2453D" block class="py-1" href="/login/google">
					<v-icon small>mdi-google</v-icon>
					<span>{{ $t('LOGIN WITH GOOGLE') }}</span>
				</v-btn>
			</v-col>
		</v-row>
	</v-card>
</template>

<i18n>
{
    "en": {
       "Social Login": "Social Login",
       "LOGIN WITH FACEBOOK": "LOGIN WITH FACEBOOK",
       "LOGIN WITH GOOGLE": "LOGIN WITH GOOGLE"
	},
    "fr": {
       "Social Login": "Connexion sociale",
       "LOGIN WITH FACEBOOK": "SE CONNECTER AVEC FACEBOOK",
       "LOGIN WITH GOOGLE": "SE CONNECTER AVEC GOOGLE"
  },
    "it": {
	   "Social Login": "Connessione sociale",
       "LOGIN WITH FACEBOOK": "FAI IL LOGIN CON FACEBOOK",
       "LOGIN WITH GOOGLE": "ACCEDI CON GOOGLE"
	},
	"de": {
	   "Social Login": "Connexion sociale",
       "LOGIN WITH FACEBOOK": "SE CONNECTER AVEC FACEBOOK",
       "LOGIN WITH GOOGLE": "SE CONNECTER AVEC GOOGLE"
	},
	"at": {
	   "Social Login": "Connexion sociale",
       "LOGIN WITH FACEBOOK": "SE CONNECTER AVEC FACEBOOK",
       "LOGIN WITH GOOGLE": "SE CONNECTER AVEC GOOGLE"
	},
	"swiss": {
	   "Social Login": "Connexion sociale",
       "LOGIN WITH FACEBOOK": "SE CONNECTER AVEC FACEBOOK",
       "LOGIN WITH GOOGLE": "SE CONNECTER AVEC GOOGLE"
	},
	"es": {
	   "Social Login": "Connexion sociale",
       "LOGIN WITH FACEBOOK": "SE CONNECTER AVEC FACEBOOK",
       "LOGIN WITH GOOGLE": "SE CONNECTER AVEC GOOGLE"
	},
	"nl": {
	   "Social Login": "Connexion sociale",
       "LOGIN WITH FACEBOOK": "SE CONNECTER AVEC FACEBOOK",
       "LOGIN WITH GOOGLE": "SE CONNECTER AVEC GOOGLE"
	},
	"se": {
	   "Social Login": "Connexion sociale",
       "LOGIN WITH FACEBOOK": "SE CONNECTER AVEC FACEBOOK",
       "LOGIN WITH GOOGLE": "SE CONNECTER AVEC GOOGLE"
	},
	"ne": {
	   "Social Login": "Connexion sociale",
       "LOGIN WITH FACEBOOK": "SE CONNECTER AVEC FACEBOOK",
       "LOGIN WITH GOOGLE": "SE CONNECTER AVEC GOOGLE"
	},
	"dk": {
	   "Social Login": "Connexion sociale",
       "LOGIN WITH FACEBOOK": "SE CONNECTER AVEC FACEBOOK",
       "LOGIN WITH GOOGLE": "SE CONNECTER AVEC GOOGLE"
	}
}
</i18n>
