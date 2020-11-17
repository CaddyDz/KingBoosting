// This entire component is in AppBarComponent.vue
<template>
	<v-container fluid>
		<v-row>
			<!-- Necessary to finish 12 -->
			<v-col md="2"></v-col>
			<v-col md="2">
				<v-menu transition="slide-y-transition" min-width="100%" open-on-hover>
					<template v-slot:activator="{on}">
						<a href="/regular-boosting" v-on="on" class="link">{{ $t('Boosting') }}</a>
					</template>
					<v-row align="center" justify="center">
						<v-col md="3">
							<nuxt-link to="/regular-boosting">
								<img src="/img/tft.png" alt />
								<!-- Vuetify Built-in class -->
								<p class="text-center">{{ $t('Regular Boosting') }}</p>
							</nuxt-link>
						</v-col>
						<v-col md="3">
							<nuxt-link to="/regular-boosting">
								<img src="/img/tft.png" alt />
								<!-- Vuetify Built-in class -->
								<p class="text-center">{{ $t('Regular Boosting') }}</p>
							</nuxt-link>
						</v-col>
					</v-row>
				</v-menu>
			</v-col>
			<v-col md="2">
				<a href="#" :title="$t('Coaching')" class="link">{{ $t('Coaching') }}</a>
			</v-col>
			<v-col md="2">
				<a href="#" :title="$t('Lol Accounts')" class="link">{{ $t('Lol Accounts') }}</a>
			</v-col>
			<v-col md="2">
				<nuxt-link to="/boosters" :title="$t('Boosters')">
					<p class="text-center link nuxt-link">{{ $t('Boosters') }}</p>
				</nuxt-link>
			</v-col>
			<v-col md="2">
				<a href="#" :title="$t('More*')" class="link">{{ $t('More*') }}</a>
			</v-col>
		</v-row>
	</v-container>
</template>

<style scoped>
/* Make the background color of the menu content dark */
.theme--dark {
	background: rgb(0, 0, 0);
	background: linear-gradient(
		180deg,
		rgba(0, 0, 0, 0) 0%,
		rgba(18, 4, 17, 1) 15%,
		rgba(18, 4, 17, 1) 15%,
		rgba(18, 4, 17, 1) 87%,
		rgba(18, 4, 17, 0) 87%
	);
}
/* The purple underground shade */
img:hover {
	background-image: url("/img/service_nav_bg.png");
	background-position: center bottom;
}
/* Remove stubborn underline */
a {
	text-decoration: none !important;
}
/* Make the text above the underground purple shade */
p {
	position: relative;
	bottom: 40px;
}
.nuxt-link {
	bottom: 3px;
}
/* Add underline manually to navbar links text */
.link {
	padding-bottom: 6px;
	border-bottom: 3px solid currentColor;
}
.v-menu__content {
	box-shadow: none !important;
}
</style>

<i18n>
{
	"en": {
		"Boosting": "Boosting",
		"Regular Boosting": "Regular Boosting",
		"Coaching": "Coaching",
		"Lol Accounts": "Lol Accounts",
		"Boosters": "Boosters",
		"More*": "More*"
	},
	"fr": {
	    "Boosting": "Booster",
		"Regular Boosting": "Boost régulier",
		"Coaching": "Allenamento",
		"Lol Accounts": "Comptes Lol",
		"Boosters": "Boosters",
		"More*": "Plus*"
	},
	"it": {
		"Boosting": "Boosting",
		"Regular Boosting": "Regular Boosting",
		"Coaching": "Coaching",
		"Lol Accounts": "Lol Accounts",
		"Boosters": "Boosters",
		"More*": "Di Più*"
	},
	"de": {
		"Boosting": "Boosting",
		"Regular Boosting": "Regular Boosting",
		"Coaching": "Coaching",
		"Lol Accounts": "Lol Accounts",
		"Boosters": "Boosters",
		"More*": "More*"
	},
	"at": {
		"Boosting": "Boosting",
		"Regular Boosting": "Regular Boosting",
		"Coaching": "Coaching",
		"Lol Accounts": "Lol Accounts",
		"Boosters": "Boosters",
		"More*": "More*"
	},
	"swiss": {
		"Boosting": "Boosting",
		"Regular Boosting": "Regular Boosting",
		"Coaching": "Coaching",
		"Lol Accounts": "Lol Accounts",
		"Boosters": "Boosters",
		"More*": "More*"
	},
	"es": {
		"Boosting": "Boosting",
		"Regular Boosting": "Regular Boosting",
		"Coaching": "Coaching",
		"Lol Accounts": "Lol Accounts",
		"Boosters": "Boosters",
		"More*": "More*"
	},
	"nl": {
		"Boosting": "Boosting",
		"Regular Boosting": "Regular Boosting",
		"Coaching": "Coaching",
		"Lol Accounts": "Lol Accounts",
		"Boosters": "Boosters",
		"More*": "More*"
	},
	"se": {
		"Boosting": "Boosting",
		"Regular Boosting": "Regular Boosting",
		"Coaching": "Coaching",
		"Lol Accounts": "Lol Accounts",
		"Boosters": "Boosters",
		"More*": "More*"
	},
	"ne": {
		"Boosting": "Boosting",
		"Regular Boosting": "Regular Boosting",
		"Coaching": "Coaching",
		"Lol Accounts": "Lol Accounts",
		"Boosters": "Boosters",
		"More*": "More*"
	},
    "dk": {
		"Boosting": "Boosting",
		"Regular Boosting": "Regular Boosting",
		"Coaching": "Coaching",
		"Lol Accounts": "Lol Accounts",
		"Boosters": "Boosters",
		"More*": "More*"
	}
}
</i18n>
