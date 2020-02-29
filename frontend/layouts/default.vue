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
export default {
	data() {
		return {
			fixed: false,
			items: [
				{
					icon: "mdi-apps",
					title: "Welcome",
					to: "/"
				},
				{
					icon: "mdi-chart-bubble",
					title: "Inspire",
					to: "/inspire"
				}
			],
			miniVariant: false,
			right: true,
			rightDrawer: false
		};
	}
};
</script>

<style scoped>
.container {
	padding: 0px;
}
</style>
