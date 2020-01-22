<template>
	<div>
		<a href="https://facebook.com/kingboosting" target="_blank">
			<v-icon color="white">mdi-facebook</v-icon>
		</a>
		<a href="https://www.instagram.com/kingboosting_official/" target="_blank">
			<v-icon color="white">mdi-instagram</v-icon>
		</a>
		<a href="https://twitter.com/kingboosting" target="_blank">
			<v-icon color="white">mdi-twitter</v-icon>
		</a>
		<a href="skype:">
			<v-icon color="white">mdi-skype</v-icon>
		</a>
	</div>
</template>

<script>
export default {
	mounted() {
		console.log("Component mounted.");
	}
};
</script>

<style scoped>
a {
	text-decoration: none;
}
i:hover {
	color: purple !important;
}
</style>
