<template>
    <v-container class="features-section container--fluid">
        <v-row class="">
            <v-col md="4" class="relative-box d-flex justify-start align-end ">
				<img height="auto" class="bg-img" src="img/features/bg.png" alt="">
                <h1 class="our-features-button ">Our Features</h1>

            </v-col>
            <v-col class="fix-index" md="8">
                <v-row>
                    <v-col v-for="(feature, index) in featuresData" :key="index" cols="6">
                        <h2 class="feature-title"> {{feature.title}} </h2>
						<v-row>
							<v-col cols="2" class="d-flex justify-center align-center">
								<v-icon>mdi-star</v-icon>
							</v-col>
							<v-col  cols="10">
								<p class="feature-description"> {{ feature.description }} </p>
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
	data(){
		return {
			featuresData : []
		}
	},
	methods: {
		fetchFeaturesData(){
			axios.get('api/features')
			.then(response => (this.featuresData = response.data ) );
		},
	},
	mounted(){
		this.fetchFeaturesData()
	}
}
</script>
<style scoped>
.fix-index {
	z-index: 2;
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
	background-image: linear-gradient(to right, #D45151, #FD905C);
	padding: 10px 20px;
	z-index: 1;
}

.features-section {
 background-color: #202224;
 padding: 0 12px;
}

.feature-title  {
	font-size: 18px;
	font-weight: bold;
}

.feature-description {
	font-size: 14px;
	color: #76787A;
	font-weight: bold;
	text-align: justify;
}

</style>

