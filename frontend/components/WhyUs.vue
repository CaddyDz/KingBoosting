<template>
	<div class="why-us">
		<div class="section-name">
			<h1>{{ $t('Why Us') }}</h1>
		</div>
		<v-container class="container">
			<v-row>
				<v-card class="card" align-self="center" v-for="(item ,index) in whyUsData" :key="index">
					<v-col>
						<v-icon size="100px" class="icons">{{icons[index]}}</v-icon>
						<v-card-title>
							<h2>{{ item.title }}</h2>
						</v-card-title>
						<v-card-text class="headline">
							<p>{{ item.description }}</p>
						</v-card-text>
					</v-col>
				</v-card>
			</v-row>
		</v-container>
	</div>
</template>

<script>
export default {
	data() {
		return {
			whyUsData: [],
			icons: [
				"mdi-school",
				"mdi-google-circles-communities",
				"mdi-chart-bubble"
			]
		};
	},
	methods: {
		fetchWhyUsData() {
			this.$axios.get("/priorities").then(Response => {
				this.whyUsData = Response.data;
			});
		}
	},
	mounted() {
		this.fetchWhyUsData();
	}
};
</script>

<style scoped>
.container {
	margin-top: 50px;
	margin-bottom: 30px;
}
.icons {
	background: -webkit-linear-gradient(#673ab7, #9c27b0);
	background-clip: text;
	-webkit-background-clip: text;
	-webkit-text-fill-color: transparent;
}
.why-us {
	background-color: #212224;
	position: relative;
	background-image: url("/img/backgrounds/priorities-bg.webp");
	-webkit-background-size: cover;
	-moz-background-size: cover;
	-o-background-size: cover;
	background-size: cover;
}

h1 {
	margin: 0;
}

ul {
	list-style-type: none;
	background-color: #212224;
}

h2 {
	color: #9c27b0;
}

p {
	color: #999a9c;
	font-size: 14px;
}
.section-name {
	display: flex;
	align-items: center;
	justify-content: center;
	width: 200px;
	height: 60px;
	background: linear-gradient(#673ab7, #9c27b0);
	z-index: 1;
	position: absolute;
	top: -30px;
}

.card {
	margin-top: 20px;
	margin-bottom: 20px;
	opacity: 0.8;
}
</style>

<i18n>
{
  "en": {
    "Why Us": "Why Us"
  }
}
</i18n>
