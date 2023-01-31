<template>
	<v-form v-model="valid" @submit.prevent="login">
		<v-layout column>
			<v-flex>
				<v-text-field label="Email address" placeholder="Your email address" v-model="credentials.email" :rules="emailRules" :error-messages="emailErrors" required></v-text-field>
			</v-flex>
			<v-flex>
				<v-text-field type="password" v-model="credentials.password" label="Password *" placeholder="Your password" :rules="passwordRules" required></v-text-field>
			</v-flex>
			<v-flex>
				<v-row dark class="ml-2 mt-2">
					<v-btn text small @click="requestPasswordReset">Forgotten Password?</v-btn>
					<v-btn color="#673AB7" small :disabled="!valid" type="submit">Continue</v-btn>
					<v-btn text small @click="close">Cancel</v-btn>
				</v-row>
			</v-flex>
		</v-layout>
	</v-form>
</template>

<script>
export default {
	props: {
		redirect: {
			type: Boolean,
			required: false,
			default: false,
		},
	},
	data: () => ({
		valid: true,
		credentials: {
			email: "",
			password: "",
		},
		emailErrors: [],
		emailRules: [
			(v) => !!v || "E-mail is required",
			(v) => /.+@.+/.test(v) || "E-mail must be valid",
		],
		passwordRules: [
			(v) => !!v || "Password is required",
			(v) => v.length >= 8 || "Password must be at least 8 characters",
		],
	}),
	methods: {
		async login() {
			try {
				await this.$auth
					.loginWith("laravelSanctum", {
						data: this.credentials,
					})
					.then(() => {
						this.$notify("Logged In", "success");
						if (this.redirect) {
							setTimeout(() => {
								window.location = process.env.HOST_URL;
							}, 2000);
						}
						this.close();
					})
					.catch((errors) => {
						this.$sentry.captureException(errors);
						this.$notify(
							errors.response.data.message,
							"error",
							errors.response.data.errors
						);
					});
			} catch ({ response }) {
				// TODO: send error to sentry
				this.$sentry.captureException(response);
				this.$notify(
					"Could not contact server",
					"error",
					response.data.errors
				);
			}
		},
		requestPasswordReset() {
			if (!this.email) {
				this.emailErrors = [
					"Please fill out email field to reset your password",
				];
				this.$notify(
					"Please fill out email field to reset your password",
					"error"
				);
				return;
			} else {
				// Send the email via axios to request password reset
				this.$axios
					.post("/password/email", { email: this.email })
					.then(() => {
						this.$notify(
							"We have sent you an email to reset your password",
							"success"
						);
					})
					.catch((errors) => {
						this.$notify(
							"Something went wrong",
							"error",
							errors.response.data.errors
						);
					});
			}
		},
		close() {
			this.$emit("close");
		},
	},
};
</script>
