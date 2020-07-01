<template>
	<v-app dark>
		<notification-component></notification-component>
		<v-system-bar lights-out app>
			<social-component></social-component>
			<v-spacer></v-spacer>
			<top-right-links></top-right-links>
		</v-system-bar>
		<app-bar-component></app-bar-component>
		<v-content>
			<v-container fluid>
				<nuxt />
			</v-container>
		</v-content>
	</v-app>
</template>

<script>
export default {};
</script>

<style scoped>
.container {
	padding: 0px;
}
</style>
