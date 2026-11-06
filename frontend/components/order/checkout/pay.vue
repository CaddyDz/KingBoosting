<template>
	<v-stepper-content key="3-content" step="3">
		<form id="payment-form">
			<div id="card-element" class="mb-12"></div>
			<v-btn color="primary" @click="sendOrder" :disabled="!valid">Continue</v-btn>
			<v-btn text @click="cancel">Cancel</v-btn>
		</form>
	</v-stepper-content>
</template>

<script>
export default {
	data: () => ({
		valid: false,
		stripeToken: "",
	}),
	methods: {
		sendOrder() {
			if (!this.valid) {
				return;
			}
			// We need to emit the event with a payload containing the stripe payment token
			this.$root.$emit("sendOrder", this.stripeToken);
		},
		cancel() {
			this.$emit("cancel");
		},
		async tokenize(card) {
			const { token, error } = await this.$stripe.createToken(card);
			return token;
		},
	},
	mounted() {
		const elements = this.$stripe.elements();
		const card = elements.create("card", {
			style: {
				base: {
					iconColor: "#c4f0ff",
					color: "#fff",
					fontWeight: 500,
					fontFamily: "Roboto, Open Sans, Segoe UI, sans-serif",
					fontSize: "16px",
					fontSmoothing: "antialiased",
					":-webkit-autofill": {
						color: "#fce883",
					},
					"::placeholder": {
						color: "#87BBFD",
					},
				},
				invalid: {
					iconColor: "#FFC7EE",
					color: "#FFC7EE",
				},
			},
		});
		// Add an instance of the card Element into the `card-element` <div>
		card.mount("#card-element");
		card.on("change", (event) => {
			if (event.complete) {
				this.valid = true;
				this.$stripe.createToken(card).then((result) => {
					if (result.error) {
						this.valid = false;
						this.$notify("Invalid card", "error");
					} else if (result.token) {
						this.valid = true;
						this.$notify("Valid Card", "success");
						this.stripeToken = result.token.id;
					}
				});
			} else if (event.error) {
				this.valid = false;
				this.$notify(event.error.message, "error");
			} else if (event.empty) {
				this.valid = false;
				this.$notify("Please enter your credit card number", "error");
			}
		});
	},
};
</script>
