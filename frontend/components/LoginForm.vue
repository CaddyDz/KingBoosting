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
					<v-btn text small @click="close">{{ $t('Cancel') }}</v-btn>
				</v-row>
			</v-flex>
		</v-layout>
	</v-form>
</template>

<script>
import axios from "axios";
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
			this.$axios
				.post("/login", {
					email: this.email,
					password: this.password
				})
				.then(response => {
					this.$store.commit("log_in_out/login", {
						token: response.data.success.access_token
					});
					this.$store.commit("notification/open", {
						text: response.data.message,
						mode: "success"
					});
					this.close();
					// i see like no need to change the route.
					// window.location = process.env.DASHBOARD_URL;
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
					.post("/password/email", { email: this.email })
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
		},
		close() {
			this.$emit("close");
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
    "We have sent you an email to reset your password": "We have sent you an email to reset your password",
    "Something went wrong": "Something went wrong"
	 },
	"fr": {
    "Email address *": "Adresse email *",
    "Your email address": "Votre adresse email",
    "Password *": "Mot de passe *",
    "Your password": "Votre mot de passe",
    "Forgotten Password?": "MOT DE PASSE OUBLIÉ?",
    "Continue": "CONTINUER",
    "Cancel": "ANNULER",
    "E-mail is required": "L'email est requis",
    "E-mail must be valid": "L'email doit être valide",
    "Password is required": "Mot de passe requis",
    "Password must be at least 8 characters": "Mot de passe doit être d'au moins 8 caractères",
    "Please fill out email field to reset your password": "Veuillez remplir le champ email pour réinitialiser votre mot de passe",
    "We have sent you an email to reset your password": "Nous vous envoyé un e-mail pour réinitialiser votre mot de passe",
    "Something went wrong": "Quelque chose a mal tourné"
	}
}
</i18n>
