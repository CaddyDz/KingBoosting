<template>
	<v-form @submit.prevent="userLogin">
		<v-layout column>
			<v-flex>
				<v-text-field
					label="Email address *"
					placeholder="Your email address"
					v-model="login.email"
					required
				></v-text-field>
			</v-flex>
			<v-flex>
				<v-text-field
					type="password"
					v-model="login.password"
					label="Password *"
					placeholder="Your password"
					required
				></v-text-field>
			</v-flex>
			<v-flex>
				<v-row dark class="ml-2 mt-2">
					<v-btn
						color="#673AB7"
						small
						type="submit"
					>Continue</v-btn>
					<!-- <v-btn text small @click="cancel">{{ $t('Cancel') }}</v-btn> -->
				</v-row>
			</v-flex>
		</v-layout>
	</v-form>
</template>

<script>
export default {
	auth: 'guest',
	data: () => ( {
		login: {
			email: "",
			password: ""
		},
	}),
	methods: {
		async userLogin() {
			try {
				let response = await this.$auth.loginWith("laravelSanctum", {
					data: this.login
				});
				console.log(response);
			} catch (err) {
				console.log(err.response.data.errors);
			}
		},
		requestPasswordReset() {
			if (!this.email) {
				this.emailErrors = [
						"Please fill out email field to reset your password"
				];
				this.$store.commit("notification/open", {
					text: "Please fill out email field to reset your password",
					mode: "error"
				});
				return;
			} else {
				// Send the email via axios to request password reset
				this.$axios
					.$post("/password/email", { email: this.email })
					.then(response => {
						this.$store.commit("notification/open", {
							text:
								"We have sent you an email to reset your password"
							,
							mode: "success"
						});
					})
					.catch(errors => {
						this.$store.commit("notification/open", {
							text: "Something went wrong",
							mode: "error"
						});
					});
			}
		},
		close() {
			this.$emit("close");
		},
		cancel() {
			this.$emit("cancel");
		}
	},
	mounted() {
		// this.auth = this.$store.getters.isLoggedIn;
	}
};
</script>
