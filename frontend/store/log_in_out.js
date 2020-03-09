export const state = () => ({
    isLoggedIn:false,
    token: ""
  });
  
  export const mutations = {
    login(state, payload) {
        state.isLoggedIn = true;
        state.token = payload.token ;
    },
    logout(state){
        state.isLoggedIn = false;
        state.token = "";
    }
  };