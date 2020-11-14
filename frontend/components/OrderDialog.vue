<template>
	<v-stepper v-model="currentStep">
		<v-stepper-header>
			<!-- 3 templates here -->
			<template>
				<v-stepper-step key="login" :complete="isLoggedIn" step="1">Login</v-stepper-step>
				<v-divider v-if="1 !== steps.length" key="1"></v-divider>
			</template>
			<template>
				<v-stepper-step key="order-details" :complete="currentStep > 2" step="2">Details</v-stepper-step>
				<v-divider v-if="2 !== steps.length" key="2"></v-divider>
			</template>
			<template>
				<v-stepper-step key="pay" :complete="currentStep > 3" step="3">Pay</v-stepper-step>
				<v-divider v-if="3 !== steps.length" key="3"></v-divider>
			</template>
		</v-stepper-header>
		<v-stepper-items>
			<login @next="nextStep($event)" @cancel="cancel" />
			<order-details @next="nextStep(2)" />
			<pay @cancel="cancel" />
		</v-stepper-items>
	</v-stepper>
</template>

<script>
export default {
	data: () => ( {
			currentStep: 1,
			valid: false,
			steps: []
	}),
	computed: {
		priceUSD() {
			return (this.price * this.exchangeRate).toFixed(2);
		},
		isLoggedIn() {
      return false;
			return this.$store.getters["auth/isLoggedIn"];
		}
	},
	methods: {
		getBoostersList() {
			this.$axios
				.get("/getBoostersNames")
				.then(res => {
					this.boosters = res.data;
					this.boostersLoading = false;
				})
				.catch(err => {
					// TODO:
				});
		},
		sendResetPasswordEmail() {
			this.$axios
				.post("/password/email", {
					email: this.email
				})
				.then(response => {
					alert(response.data.status);
				})
				.catch(error => {
					alert(error.response.data.errors.email[0]);
				});
		},
		login() {
			this.$axios
				.post("/login", {
					email: this.email,
					password: this.password
				})
				.then(response => {
					alert(response.data.status);
					this.nextStep(1);
					// Make API call to get boosters
					this.getBoostersList();
				})
				.catch(error => {
					alert(error.response.data.errors);
				});
		},
		closeDialog() {
			this.currentStep = 1;
			this.$emit("closeDialog");
		},
		cancel() {
			this.$emit("closeDialog");
		},
		nextStep(n) {
			if (n === this.steps.length) {
				this.currentStep = 1;
			} else {
				this.currentStep = n + 1;
			}
		}
	},
	mounted() {
		// if already logged in, jump to next step
		// if (this.$store.state.authentication.isLoggedIn) {
		// 	this.currentStep = 2;
		// }
		// this.getBoostersList();
	}
};
</script>
