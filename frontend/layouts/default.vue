<template>
	<v-app dark>
		<notification-component></notification-component>
		<TopBar/>
		<app-bar-component />
		<v-main>
			<v-container fluid>
				<Nuxt v-if="!$slots.default" />
				<slot />
			</v-container>
		</v-main>
		<app-footer />
	</v-app>
</template>

<script>
export default {
	name: "Default",
};
</script>

<style scoped>
.container {
	padding: 0px;
}
</style>
