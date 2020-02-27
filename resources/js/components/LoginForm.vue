<template>
	<v-form v-model="valid" ref="form">
		<v-layout column>
			<v-flex>
				<v-text-field
					:label="$t('Email address *')"
					:placeholder="$t('Your email address')"
					v-model="email"
					:rules="emailRules"
					required
				></v-text-field>
			</v-flex>
			<v-flex>
				<v-text-field
					type="password"
					v-model="password"
					:label="$t('Password *')"
					:placeholder="$t('Your password')"
					:rules="passwordRules"
					required
				></v-text-field>
			</v-flex>
			<v-flex>
				<v-row dark class="ml-2 mt-2">
					<v-btn text small @click="requestPasswordReset">{{ $t('Forgotten Password?') }}</v-btn>
					<v-btn color="#673AB7" small @click="login" :disabled="!valid">{{ $t('Continue') }}</v-btn>
					<v-btn text small @click="dialog = false">{{ $t('Cancel') }}</v-btn>
				</v-row>
			</v-flex>
		</v-layout>
	</v-form>
</template>

<script>
export default {
	data() {
		return {
			valid: true,
			email: "",
			emailRules: [
				v => !!v || this.$i18n.t("E-mail is required"),
				v => /.+@.+/.test(v) || this.$i18n.t("E-mail must be valid")
			],
			passwordRules: [
				v => !!v || this.$i18n.t("Password is required"),
				v =>
					v.length > 8 || this.$i18n.t("Password must be at least 8 characters")
			],
			password: ""
		};
	},
	methods: {
		login() {
			if (!this.$refs.form.validate()) {
				return;
			}
			axios
				.post("/api/login", {
					email: this.email,
					password: this.password
				})
				.then(response => {
					this.$store.commit("openNotification", {
						text: response.data.message,
						mode: "success"
					});
					window.location = "/dashboard";
				})
				.catch(errors => {
					this.$store.commit("openNotification", {
						text: errors.response.data.errors,
						mode: "error"
					});
				});
		},
		requestPasswordReset() {
			if (!this.email) {
				this.$store.commit("openNotification", {
					text: this.$i18n.t(
						"Please fill out email field to reset your password"
					),
					mode: "error"
				});
				return;
			} else {
				// Send the email via axios to request password reset
				axios
					.post("/api/password/email", { email: this.email })
					.then(response => {
						this.$store.commit("openNotification", {
							text: this.$i18n.t(
								"We have sent you an email to reset your password"
							),
							mode: "success"
						});
					})
					.catch(errors => {
						this.$store.commit("openNotification", {
							text: this.$i18n.t({
								text: "Something went wrong",
								mode: "error"
							})
						});
					});
			}
		}
	},
	mounted() {
		this.auth = this.$store.getters.isLoggedIn;
	}
};
</script>

<i18n>
{
  "en": {
    "Email address *": "Email address *",
    "Your email address": "Your email address",
    "Password *": "Password *",
    "Your password": "Your password",
    "Forgotten Password?": "FORGOTTEN PASSWORD?",
    "Continue": "CONTINUE",
    "Cancel": "CANCEL",
    "E-mail is required": "E-mail is required",
    "E-mail must be valid": "E-mail must be valid",
    "Password is required": "Password is required",
    "Password must be at least 8 characters": "Password must be at least 8 characters",
    "Please fill out email field to reset your password": "Please fill out email field to reset your password",
    "We have sent you an email to reset your password": "",
    "Something went wrong": "Something went wrong"
  }
}
</i18n>
