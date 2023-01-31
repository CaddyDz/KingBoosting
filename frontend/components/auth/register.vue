<template>
	<v-row>
		<v-col>
			<RegisterForm @close="this.$emit('next', 1)" @cancel="this.$emit('cancel')"></RegisterForm>
		</v-col>
	</v-row>
</template>
