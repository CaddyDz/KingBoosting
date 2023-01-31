<template>
	<v-container>
		<v-row align="center" justify="center" class="mt-6 mb-6">
			<h1>Page not found</h1>
		</v-row>
		<v-row align="center" justify="center">
			<v-img src="/img/404.svg" alt="404" />
		</v-row>
		<v-row align="start" justify="center" class="mb-16">
			<v-btn color="primary" elevation="2" outlined x-large nuxt to="/">Home page</v-btn>
		</v-row>
	</v-container>
</template>

<script>
export default {
	head() {
		return "404 Not Found";
	},
};
</script>

<style scoped>
h1 {
	font-size: 20px;
}
</style>
