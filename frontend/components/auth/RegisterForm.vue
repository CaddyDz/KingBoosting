<template>
	<v-form v-model="valid" @submit.prevent="register">
		<v-layout column>
			<v-flex>
				<v-text-field label="Email address" placeholder="Your email address" v-model="credentials.email" :rules="emailRules" :error-messages="emailErrors" required></v-text-field>
			</v-flex>
			<v-flex>
				<v-text-field label="Confirm e-mail address" placeholder="Confirm e-mail address" v-model="credentials.email_confirmation" :rules="emailRules" :error-messages="emailErrors" required></v-text-field>
			</v-flex>
			<v-flex cols="12" md="6">
				<v-text-field v-model="credentials.username" label="Username" :rules="usernameRules" :error-messages="usernameErrors" required placeholder="Your username"></v-text-field>
			</v-flex>
			<v-flex>
				<v-row dark class="ml-2 mt-2">
					<v-btn color="#673AB7" small :disabled="!valid" type="submit">Continue</v-btn>
					<v-btn text small @click="cancel">Cancel</v-btn>
				</v-row>
			</v-flex>
		</v-layout>
	</v-form>
</template>

<script>
export default {
	data: () => ({
		valid: true,
		credentials: {
			email: "",
			username: "",
			email_confirmation: "",
		},
		emailErrors: [],
		usernameErrors: [],
		emailRules: [
			(v) => !!v || "E-mail is required",
			(v) => /.+@.+/.test(v) || "E-mail must be valid",
		],
		usernameRules: [
			(v) => !!v || "Username is required",
			(v) => v.length >= 3 || "Please insert a valid username",
		],
	}),
	methods: {
		async register() {
			await this.$axios
				.post("register", {
					email: this.credentials.email,
					email_confirmation: this.credentials.email_confirmation,
					username: this.credentials.username,
				})
				.then((response) => {
					this.$notify(
						"Account created, we emailed you a password",
						"success"
					);
					// Should open the login dialog here & disable registration
					this.$root.$emit("openLoginForm");
				})
				.catch((errors) => {
					this.$notify(
						errors.response.data.message,
						"error",
						errors.response.data.errors
					);
				});
		},
		cancel() {
			this.$emit("close");
		},
	},
};
</script>
