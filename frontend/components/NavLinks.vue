// This entire component is in AppBarComponent.vue
<template>
	<v-container fluid class="container">
		<nuxt-link to="/" class="link" >
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
					</v-row>
				</v-menu>
		</nuxt-link>
		<nuxt-link to="/" class="link" >{{ $t('Regular Boosting') }}</nuxt-link>
		<nuxt-link to="/" class="link" >{{ $t('Coaching') }}</nuxt-link>
		<nuxt-link to="/" class="link" >{{ $t('Lol Accounts') }}</nuxt-link>
		<nuxt-link to="/" class="link" >{{ $t('Boosters') }}</nuxt-link>
		<nuxt-link to="/" class="link" >{{ $t('More') }}</nuxt-link>
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

.container {
	justify-content: center !important;
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
	/* bottom: 40px; */
}
.nuxt-link {
	bottom: 3px;
}
/* Add underline manually to navbar links text */
.link {
	margin-right: 25px;
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
	}
}
</i18n>