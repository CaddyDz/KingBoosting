<template>
	<div>
		<v-btn class="ma-2" outlined color="white" href="/dashboard" v-if="auth">{{ $t('Members Area') }}</v-btn>
		<v-dialog max-width="800px" v-model="dialog" v-else>
			<template v-slot:activator="{ on }">
				<v-btn class="ma-2" outlined color="white" v-on="on">{{ $t('Members Area') }}</v-btn>
			</template>
			<v-card class="px-5" color="#303030">
				<v-container>
					<v-row>
						<v-col cols="7">
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
						</v-col>
						<v-col cols="5">
							<v-card color="black">
								<v-row class="pa-3">
									<v-col class="pb-1">
										<div class="font-weight-medium">
											<v-icon small>mdi-account-check</v-icon>
											<span>{{ $t('Social Login') }}</span>
										</div>
									</v-col>
									<v-col class="pb-1">
										<v-btn color="#2C98F0" block class="py-1" href="/login/facebook">
											<v-icon small>mdi-facebook</v-icon>
											<span>{{ $t('LOGIN WITH FACEBOOK') }}</span>
										</v-btn>
									</v-col>
									<v-col class="pb-1">
										<v-btn color="#F2453D" block class="py-1" href="/login/google">
											<v-icon small>mdi-google</v-icon>
											<span>{{ $t('LOGIN WITH GOOGLE') }}</span>
										</v-btn>
									</v-col>
								</v-row>
							</v-card>
						</v-col>
					</v-row>
				</v-container>
			</v-card>
		</v-dialog>
	</div>
</template>

<script>
export default {
	data() {
		return {
			auth: false,
			dialog: false,
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

<style scoped>
.v-btn:hover:before {
	content: none;
}
</style>

<i18n>
{
  "en": {
    "Members Area": "Members Area",
    "Email address *": "Email address *",
    "Your email address": "Your email address",
    "Password *": "Password *",
    "Your password": "Your password",
    "Forgotten Password?": "FORGOTTEN PASSWORD?",
    "Continue": "CONTINUE",
    "Cancel": "CANCEL",
    "Social Login": "Social Login",
    "LOGIN WITH FACEBOOK": "LOGIN WITH FACEBOOK",
    "LOGIN WITH GOOGLE": "LOGIN WITH GOOGLE",
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
