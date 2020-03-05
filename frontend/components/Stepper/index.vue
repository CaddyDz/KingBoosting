<template>
    <v-stepper vertical non-linear>
		<v-stepper-step step="1">{{ $t('Select Your Current League') }}</v-stepper-step>
		<v-stepper-content step="1" >
            <step-one />
        </v-stepper-content>
		<v-stepper-step step="2">{{ $t('Select Your Number Of Wins') }}</v-stepper-step>
		<v-stepper-content step="1" >
            <step-two />
        </v-stepper-content>
		<v-stepper-step step="3">{{ $t('Checkout') }}</v-stepper-step>
		<v-stepper-content step="1" >
            <step-three />
        </v-stepper-content>
	</v-stepper>
</template>

<script>
import stepOne from './steps/stepOne.vue';
import stepTwo from './steps/stepTwo.vue';
import stepThree from './steps/stepThree';

export default {
    data:() => {return},
    components: {
        stepOne,
        stepTwo,
        stepThree
    }
}
</script>

<style>

</style>