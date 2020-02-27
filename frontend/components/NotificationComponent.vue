<template>
	<v-snackbar v-model="snackbar" right top :color="mode" :timeout="3000">
		{{ text }}
		<v-btn color="black" text @click="snackbar = false">{{ $t('Close') }}</v-btn>
	</v-snackbar>
</template>

<script>
export default {
	computed: {
		snackbar: {
			get() {
				return this.$store.state.isNotificationOpen;
			},
			set() {
				this.$store.commit("closeNotification");
			}
		},
		text() {
			return this.$store.state.notificationText;
		},
		mode() {
			return this.$store.state.notificationMode;
		}
	}
};
</script>

<i18n>
{
  "en": {
    "Close": "Close"
  }
}
</i18n>
