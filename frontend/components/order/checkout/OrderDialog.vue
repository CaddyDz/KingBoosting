<template>
	<v-stepper v-model="currentStep">
		<v-stepper-header>
			<!-- 3 templates here -->
			<template>
				<v-stepper-step :complete="this.$auth.loggedIn" step="1">{{ register ? "Register" : "Login" }}</v-stepper-step>
				<v-divider></v-divider>
			</template>
			<template>
				<v-stepper-step key="order-details" :complete="currentStep > 2" step="2">Details</v-stepper-step>
				<v-divider v-if="2 !== steps" key="2"></v-divider>
			</template>
			<template>
				<v-stepper-step key="pay" :complete="currentStep > 3" step="3">Pay</v-stepper-step>
				<v-divider v-if="3 !== steps" key="3"></v-divider>
			</template>
		</v-stepper-header>
		<v-stepper-items>
			<v-stepper-content step="1">
				<login @next="nextStep(1)" @cancel="cancel" v-if="!register" />
				<register v-if="register" @next="nextStep(1)" @cancel="cancel" />
			</v-stepper-content>
			<order-details @next="nextStep(2)" />
			<Pay @cancel="cancel" />
		</v-stepper-items>
	</v-stepper>
</template>

<script>
export default {
	data: () => ({
		currentStep: 1,
		valid: false,
		steps: 3,
		register: false,
	}),
	methods: {
		cancel() {
			this.currentStep = this.$auth.loggedIn ? 2 : 1;
			this.$emit("closeDialog");
		},
		nextStep(n) {
			if (n === this.steps) {
				this.currentStep = 1;
			} else {
				this.currentStep++;
			}
		},
	},
	mounted() {
		if (this.$auth.loggedIn) {
			this.currentStep = 2;
		}
		this.$root.$on("openRegisterForm", () => {
			this.register = true;
		});
		this.$root.$on("openLoginForm", () => {
			this.register = false;
		});
		this.$root.$on("logout", () => {
			this.currentStep = 1;
		});
	},
};
</script>

<style scoped>
.theme--dark.v-stepper {
	background-color: black;
}
</style>
