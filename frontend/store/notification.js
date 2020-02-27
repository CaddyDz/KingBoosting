export const state = () => ({
  isNotificationOpen: false,
  notificationText: "",
  notificationMode: 'info',
})

export const mutations = {
  openNotification(state, payload) {
    state.isNotificationOpen = true;
    state.notificationText = payload.text;
    state.notificationMode = payload.mode;
  },
  closeNotification(state) {
    state.isNotificationOpen = false;
  },
}
