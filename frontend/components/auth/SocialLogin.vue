<template>
	<v-card color="black">
		<v-row class="pa-3">
			<v-col class="pb-1">
				<v-btn color="#2C98F0" block class="py-1" @click="redirect('facebook')">
					<v-icon small>mdi-facebook</v-icon>
					<span>LOGIN WITH FACEBOOK</span>
				</v-btn>
			</v-col>
			<v-col class="pb-1">
				<v-btn color="#F2453D" block class="py-1" @click="redirect('google')">
					<v-icon small>mdi-google</v-icon>
					<span>LOGIN WITH GOOGLE</span>
				</v-btn>
			</v-col>
			<v-col class="pb-1">
				<v-btn color="#3b9967" block class="py-1" @click="openRegisterForm">
					<v-icon small>mdi-email</v-icon>
					<span>Register with e-mail</span>
				</v-btn>
			</v-col>
		</v-row>
	</v-card>
</template>

<script>
export default {
	methods: {
		redirect(provider) {
			window.location = `${process.env.HOST_URL}/login/${provider}`;
		},
		openRegisterForm() {
			this.$root.$emit("openRegisterForm");
		},
	},
};
</script>
