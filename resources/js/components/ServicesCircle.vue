<template>
	<div class="is-centered-xy">
		<div class="wrap is-circle is-centered-xy">
			<div
				class="object is-a is-circle"
				v-for="(service, index) in services"
				:key="index"
				:style="{animationDelay: index*5 + 's'}"
			>
				<img :src="service.image" alt :style="{animationDelay: index*5 + 's'}" />
			</div>
		</div>
	</div>
</template>

<script>
export default {
	data() {
		return {
			services: [
				{ image: "/img/circles/1.png", name: "Account market" },
				{ image: "/img/circles/2.png", name: "ELO Boosting Packs" },
				{ image: "/img/circles/3.png", name: "Champion Mastery" },
				{ image: "/imgg/circles/4.png", name: "TFT Boosting" },
				{ image: "/img/circles/5.png", name: "League Boosting" },
				{ image: "/img/circles/6.png", name: "Duo Queue Boosting" },
				{ image: "/img/circles/7.png", name: "Account Leveling" },
				{ image: "/img/circles/8.png", name: "Normal Matches" },
				{ image: "/img/circles/9.png", name: "Placment Matches" },
				{ image: "/img/circles/10.png", name: "Promotion Boosting" },
				{ image: "/img/circles/11.png", name: "Buy Lol Smurf Account" },
				{ image: "/img/circles/12.png", name: "Ranked Wins Boost" }
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
	background-color: transparent;
	border: 2px solid purple;
	width: 300px;
	height: 300px;
}

.object {
	width: 60px;
	height: 60px;
	animation-name: orbit;
	animation-duration: 50s;
	animation-iteration-count: infinite;
	animation-timing-function: linear;
	position: absolute;
}

.object:hover {
	animation-play-state: paused;
}

img {
	max-width: 100%;
	max-height: 100%;
	position: absolute;
	animation-name: rotate;
	/* animation-direction: reverse; */
	animation-duration: 50s;
	animation-iteration-count: infinite;
	animation-timing-function: linear;
}

@keyframes orbit {
	from {
		transform: rotate(0deg) translateX(150px);
	}
	to {
		transform: rotate(360deg) translateX(150px);
	}
}
@keyframes rotate {
	from {
		transform: rotate(360deg);
	}
	to {
		transform: rotate(0deg);
	}
}
</style>
