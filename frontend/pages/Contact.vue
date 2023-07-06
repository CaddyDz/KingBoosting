<template>
	<div class="ma-16">
		<h1 class="d-flex justify-center">Contact us</h1>
		<div class="d-flex justify-center mt-5 text-center">
			<p style="max-width: 700px;">
				You can choose from a range of contact types. <br>
				All you have to do is to find the one that suits you the best
				and we are ready to answer any of your questions as soon as possible.
			</p>
		</div>
		<v-container>
			<v-row>
				<v-col>
					<v-form ref="form" v-model="valid">
						<v-select v-model="form.category" label="Category"
							:rules="[(v) => !!v || 'Category is required']" :items="categories"></v-select>
						<v-text-field v-model="form.name" label="Name" :rules="nameRules"></v-text-field>
						<v-text-field v-model="form.email" label="E-mail address"
							:rules="[(v) => /.+@.+\..+/.test(v) || 'E-mail must be valid']"></v-text-field>
						<v-textarea v-model="form.message" outlined placeholder="Your Message"></v-textarea>
						<v-btn :disabled="!valid" color="primary" class="mr-4" @click="submit">Send Message</v-btn>
					</v-form>
				</v-col>
			</v-row>
			<v-row>
				<v-col>
					<a href="https://facebook.com/kingboosting">
						<v-card>
							<v-card-title>Facebook</v-card-title>
							<v-card-text>
								KingBoosting
								<v-icon x-large>mdi-facebook</v-icon>
							</v-card-text>
						</v-card>
					</a>
				</v-col>
				<v-col>
					<a href="https://twitter.com/kingboosting">
						<v-card>
							<v-card-title>Twitter</v-card-title>
							<v-card-text>
								KingBoosting
								<v-icon x-large>mdi-twitter</v-icon>
							</v-card-text>
						</v-card>
					</a>
				</v-col>
				<v-col>
					<a href="https://www.instagram.com/kingboosting_official/">
						<v-card>
							<v-card-title>Instagram</v-card-title>
							<v-card-text>
								KingBoosting
								<v-icon x-large>mdi-instagram</v-icon>
							</v-card-text>
						</v-card>
					</a>
				</v-col>
			</v-row>
		</v-container>
	</div>
</template>

<script>
export default {
	data: () => ({
		categories: [
			"General Question",
			"Order Issue",
			"Application",
			"Bug Report",
			"Other",
		],
		category: "General Question",
		valid: false,
		nameRules: [
			(v) =>
				(v && v.length <= 10) || "Name must be less than 10 characters",
		],
		form: {
			category: 'General Question',
			name: '',
			email: '',
			message: '',
		}
	}),
	watch: {
		category(value) {
			if (value == "Application") {
				this.$router.push("/apply");
			}
		},
	},
	methods: {
		submit() {
			this.validate();
			if (this.valid) {
				this.$axios
					.post("api/contact", this.form)
					.then((response) => {
						this.$notify(response.data.message, "success");
						this.$refs.form.reset();
					})
					.catch((error) =>
						this.$notify(
							error.response?.data.message || error.message,
							"error",
							error.response?.data.errors || [],
						)
					);
			}
		},
		validate() {
			this.$refs.form.validate();
		},
	},
};
</script>
