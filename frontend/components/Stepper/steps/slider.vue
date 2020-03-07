<template>
    <div>
        <div class="title">
            <div class="title-id"><h2> {{step.id}} </h2></div>
            <h2 class="title-txt"> {{step.title}} </h2>
        </div>
        <v-card raised class="mb-4" >
            <v-container class="container">
                <v-slider
                    v-model="slider"
                    thumb-label="always"
                    max="10"
                    @end="sliderChangeHandler({value:$event})"
                ></v-slider>
                <v-radio-group class="radio-group" v-model="row" dark mandatory row>
                    <v-radio label="Solo/Duo" value="Solo/Duo"></v-radio>
                    <v-radio label="Flex 5v5" value="Flex"></v-radio>
                </v-radio-group>
            </v-container>
        </v-card>
    </div>
</template>

<script>
export default {
    props: ["step"],
    data(){
        return {
            items: ['Foo', 'Bar', 'Fizz', 'Buzz'],
            hasDivisions:true,
            slider:4,
            radios: "Solo/Duo",
            row:"",
            sliderValue: 4
        }
    },
    methods:{
        sliderChangeHandler(e){
            // TODO: change it to write to the store
            this.sliderValue = e.value;
        }
    }
}
</script>

<style scoped>
.container {
	display: flex;
	flex-direction: column;
	/* align-items: center; */
	justify-content: center;
}

.radio-group {
	align-self: center;
	margin-top: 40px;
}

.title {
	display: flex;
	flex-direction: row;
	align-items: center;
	margin-bottom: 20px;
}

.title-txt {
	margin-left: 20px;
}

.title .title-id {
	color: #181818;
}

.title-id {
	width: 50px;
	height: 50px;
	border-radius: 50%;
	background: linear-gradient(#673ab7, #9c27b0);
	display: flex;
	align-items: center;
	justify-content: center;
}
</style>

<i18n>
{
  "en": {
    "Flex 5v5": "Flex 5v5",
    "Solo/Duo": "Solo/Duo"
  }
}
</i18n>
