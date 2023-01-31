<template>
	<v-row>
		<v-col>
			<div class="font-weight-medium">
				<v-icon>mdi-account-check</v-icon>
				<span>Returning Customers</span>
			</div>
			<LoginForm @close="nextStep" @cancel="cancel"></LoginForm>
		</v-col>
		<v-col cols="4">
			<div class="font-weight-medium">
				<v-icon>mdi-account-check</v-icon>
				<span>New Customers</span>
			</div>
			<SocialLogin></SocialLogin>
		</v-col>
	</v-row>
</template>

<script>
export default {
	methods: {
		nextStep() {
			this.$emit("next", 1);
		},
		cancel() {
			this.$emit("cancel");
		},
	},
};
</script>
