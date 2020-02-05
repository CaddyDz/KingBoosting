<template>
	<v-app-bar app color="purple darken-4" extended dense>
		<div id="particles-js"></div>
		<a href="/">
			<img src="/img/placeholder_logo.gif" alt="King Boosting" />
		</a>
		<v-spacer></v-spacer>
		<nav-links></nav-links>
		<v-spacer></v-spacer>
		<v-btn class="ma-2" outlined color="white" href="/dashboard">Members Area</v-btn>
	</v-app-bar>
</template>

<script>
require("particles.js");
export default {
	mounted() {
		// particlesJS.load("particles-js", "/particlesjs-config.json");
	}
};
</script>

<style scoped>
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
