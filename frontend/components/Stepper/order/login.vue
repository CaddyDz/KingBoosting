<template>
	<v-stepper-content key="1-content" step="1">
		<v-row>
			<v-col>
				<LoginForm @close="nextStep(1)" @cancel="cancel"></LoginForm>
			</v-col>
			<v-col cols="4">
				<!-- <SocialLogin></SocialLogin> -->
			</v-col>
		</v-row>
	</v-stepper-content>
</template>

<script>
export default {
	methods: {
		nextStep(step) {
			this.$emit("next", step);
		},
		cancel() {
			this.$emit("cancel");
		}
	}
};
</script>
