export const state = () => ({
  services: [],
})

export const mutations = {
  populate(state, payload) {
    state.services = payload.services
  }
}
