<template>
	<div class="is-centered-xy">
		<div class="wrap is-circle is-centered-xy">
			<img
				:src="service.image"
				alt
				class="object is-a is-circle"
				v-for="(service, index) in services"
				:key="index"
			/>
		</div>
	</div>
</template>

<script>
export default {
	data() {
		return {
			services: [
				{ image: "/img/circles/1.png", name: "somekhfdslkhfjd" },
				{ image: "/img/jhghgyy.png" },
				{ image: "/img/jhghgyy.png" },
				{ image: "/img/jhghgyy.png" },
				{ image: "/img/jhghgyy.png" },
				{ image: "/img/jhghgyy.png" },
				{ image: "/img/jhghgyy.png" },
				{ image: "/img/jhghgyy.png" }
			]
		};
	}
};
</script>

<style scoped>
.is-centered-xy {
	margin-top: 10px;
	margin-bottom: 10px;
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
	width: 60px;
	height: 60px;
	/* background-color: red; */
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
