<template>
<body class="is-centered-xy">
	<div class="wrap is-circle is-centered-xy">
		<div class="object is-a is-circle" v-for="service in services" :key="service"></div>
	</div>
</body>
</template>

<script>
export default {
	data() {
		return {
			services: 1,
			interval: {}
		};
	},
	watch: {
		services: function(val) {
			if (val == 10) {
				clearInterval(this.interval);
			}
		}
	},
	mounted() {
		this.interval = setInterval(
			() => (this.services = this.services + 1),
			2000
		);
	}
};
</script>

<style scoped>
.is-centered-xy {
	display: flex;
	align-items: center;
	justify-content: center;
}

.is-circle {
	border-radius: 50%;
}

.wrap {
	background: blue;
	width: 300px;
	height: 300px;
}

.object {
	width: 30px;
	height: 30px;
	background: red;
	animation: rotate 50s linear infinite;
	position: absolute;
}

@keyframes rotate {
	from {
		transform: rotate(0deg) translateX(150px);
	}
	to {
		transform: rotate(360deg) translateX(150px);
	}
}
</style>
