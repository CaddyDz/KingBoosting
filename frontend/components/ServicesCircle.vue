<template>
	<div id="container">
		<div class="item" v-for="(service, index) in services" :key="index">
			<img :src="service.image" :alt="service.name" />
		</div>
	</div>
</template>

<script>
export default {
	methods: {
		applyStyles() {
			// Radius is usually half the width of the container
			var radius = 250; // adjust to move out items in and out
			var fields = document.getElementsByClassName("item");
			var container = document.getElementById("container");
			var width = container.offsetWidth;
			var height = container.offsetHeight;
			var angle = 0;
			var step = (2 * Math.PI) / fields.length;
			for (let field of fields) {
				var x = Math.round(
					width / 2 + radius * Math.cos(angle) - field.offsetWidth / 2
				);
				var y = Math.round(
					height / 2 + radius * Math.sin(angle) - field.offsetHeight / 2
				);
				field.style.left = x + "px";
				field.style.top = y + "px";
				angle += step;
			}
		}
	},
	computed: {
		services() {
			return this.$store.state.services.services;
		}
	},
	mounted() {
		this.applyStyles();
	}
};
</script>

<style scoped>
#container {
	width: 500px;
	height: 500px;
	margin: 100px auto;
	border: 2px solid purple;
	position: relative;
	border-radius: 50%;
	animation: spin 50s linear infinite;
}

.item {
	width: 100px;
	height: 100px;
	border-radius: 50%;
	position: absolute;
	animation-delay: 2000ms;
	animation: spin 50s linear infinite reverse;
}

img {
	max-width: 100%;
	min-width: 100%;
}

@keyframes spin {
	100% {
		transform: rotate(1turn);
	}
}

#container:hover,
#container:hover .item {
	animation-play-state: paused;
}
</style>
