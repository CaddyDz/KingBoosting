<template>
	<v-app dark>
		<v-container>
			<v-row align="center" justify="center">
				<h1 v-if="error.statusCode === 404">{{ pageNotFound }}</h1>
				<h1 v-else>{{ otherError }}</h1>
			</v-row>
			<v-row align="center" justify="center">
				<NuxtLink to="/">{{ $t('Home Page') }}</NuxtLink>
			</v-row>
		</v-container>
	</v-app>
</template>

<script>
export default {
	layout: "empty",
	name: "error",
	props: {
		error: {
			type: Object,
			default: null
		}
	},
	data() {
		return {
			pageNotFound: this.$i18n.t("404 Not Found"),
			otherError: this.$i18n.t("An error occured")
		};
	},
	head() {
		const title =
			this.error.statusCode === 404 ? this.pageNotFound : this.otherError;
		return {
			title
		};
	}
};
</script>

<style scoped>
h1 {
	font-size: 20px;
}
</style>
<i18n>
{
  "en": {
    "404 Not Found": "404 Not Found",
    "An error occurred": "An error occurred",
    "Home Page": "Home Page"
	},
	"fr": {
    "404 Not Found": "404 Page introuvable",
    "An error occurred": "Une erreur est survenue",
    "Home Page": "Page d'accueil"
  }
}
</i18n>
