<template>
	<v-app-bar app color="purple darken-4" extended dense>
		<v-container class="container">
			<nuxt-link to="/">
				<img src="/img/header_logo.png" alt="King Boosting" />
			</nuxt-link>
			<nav-links></nav-links>
			<LoginComponent :config="{button:false}"></LoginComponent>	
		</v-container>
	</v-app-bar>
</template>

<script>
// require("particles.js");
export default {
	mounted() {
		// particlesJS.load("particles-js", "/particlesjs-config.json");
	}
};
</script>

<style scoped>

.container {
	display: flex;
	flex-direction: row;
	align-items: center;
	justify-content: space-between;
}

.v-app-bar,
.v-application .purple.darken-4 {
	padding-top: 2%;
	border: 1px solid black !important;
	border-radius: 5px;
}
#particles-js canvas {
	display: block;
	vertical-align: bottom;
	transform: scale(1);
	opacity: 1;
	transition: opacity 0.8s ease, transform 1.4s ease;
}

#particles-js {
	width: 100%;
	height: 100%;
	position: fixed;
	z-index: -10;
	top: 0;
	left: 0;
}
</style>
