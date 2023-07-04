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
			<v-row>
				<v-col>
					<v-form ref="form" v-model="valid">
						<v-select v-model="category" name="Category" label="Category"
							:rules="[(v) => !!v || 'Category is required']" :items="categories"></v-select>
						<v-text-field name="name" label="Name" :rules="nameRules"></v-text-field>
						<v-text-field name="email" label="E-mail address"
							:rules="[(v) => /.+@.+\..+/.test(v) || 'E-mail must be valid']"></v-text-field>
						<v-textarea outlined name="message" placeholder="Your Message"></v-textarea>
						<v-btn :disabled="!valid" color="primary" class="mr-4" @click="submit">Send Message</v-btn>
					</v-form>
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
				const formData = new FormData(this.$refs.form.$el); // reference to form element
				const data = {}; // need to convert it before using not with XMLHttpRequest
				for (let [key, val] of formData.entries()) {
					Object.assign(data, { [key]: val });
				}
				this.$axios
					.post("api/contact", data)
					.then((response) => {
						this.$notify(response.data.message, "success");
						this.$refs.form.reset();
					})
					.catch((errors) => {
						this.$notify(
							errors.response.data.message,
							"error",
							errors.response.data.errors
						);
					});
			}
		},
		validate() {
			this.$refs.form.validate();
		},
	},
};
</script>
