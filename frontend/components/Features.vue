<template>
	<v-container class="features-section container--fluid">
		<div class="section-name">
			<h1>{{ $t('Features') }}</h1>
		</div>
		<v-row class>
			<v-col md="4" class="relative-box d-flex justify-start align-end">
				<img height="auto" class="bg-img" src="/img/features/bg.png" alt="Our Features" />
			</v-col>
			<v-col class="fix-index" md="8">
				<v-row>
					<v-col v-for="(feature, index) in features" :key="index" cols="6">
						<h2 class="feature-title">{{ feature.title }}</h2>
						<v-row>
							<v-col cols="2" class="d-flex justify-center align-center">
								<v-icon class="have-linear-gradient icon">{{ icons[index] }}</v-icon>
							</v-col>
							<v-col cols="10">
								<p class="feature-description">{{ feature.description }}</p>
							</v-col>
						</v-row>
					</v-col>
				</v-row>
			</v-col>
		</v-row>
	</v-container>
</template>
<script>
export default {
	props: ["features"],
	data() {
		return {
			icons: ["", "mdi-timer", "mdi-monitor", "mdi-shield-outline"]
		};
	}
};
</script>
<style scoped>
.fix-index {
	z-index: 2;
	padding-right: 60px;
}

.relative-box {
	position: relative;
}

.bg-img {
	position: absolute;
	left: 0;
	top: 0;
	height: 100%;
	max-width: initial;
	z-index: 0;
}

.our-features-button {
	background-image: linear-gradient(to right, #673ab7, #9c27b0);
	padding: 10px 20px;
	z-index: 1;
}

.features-section {
	background-color: #202224;
	padding: 0;
	position: relative;
}

.feature-title {
	font-size: 18px;
	font-weight: bold;
}

.feature-description {
	font-size: 14px;
	color: #76787a;
	font-weight: bold;
	text-align: justify;
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
.have-linear-gradient {
	background: -webkit-linear-gradient(#673ab7, #9c27b0);
	background-clip: text;
	-webkit-background-clip: text;
	-webkit-text-fill-color: transparent;
}
.icon {
	font-size: 80px;
}
</style>

<i18n>
{
  "en": {
    "Features": "Features"
  },
  "fr": {
	  "Features": "Traits"
  }
}
</i18n>
