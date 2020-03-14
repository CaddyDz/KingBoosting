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
  }
}
</i18n>
