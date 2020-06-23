<template>
	<v-app dark>
		<v-container>
			<v-row align="center" justify="center">
				<h1>{{ $t('400 Bad Request') }}</h1>
			</v-row>
			<v-row align="center" justify="center">
				<h2>{{ $t('No email address associated with this Facebook account') }}</h2>
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
	middleware({ store, redirect, $axios }) {
		return $axios.get("checkFB").then(response => {
			if (!response.data.rejected) {
				return redirect("/");
			}
		});
	},
	head() {
		const title = this.$i18n.t("400 Bad Request");
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
    "400 Bad Request": "400 Bad Request",
    "No email address associated with this Facebook account": "No email address associated with this Facebook account",
    "Home Page": "Home Page"
	},
	"fr": {
    "400 Bad Request": "400 Bad Request",
    "No email address associated with this Facebook account": "No email address associated with this Facebook account",
    "Home Page": "Page d'accueil"
  }
}
</i18n>
