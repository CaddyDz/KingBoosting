export const state = () => ({
    order: {
        slider : {
            wins : 0 ,
            radio: "Solo/Duo"
        },
        selector : {
            tier: "",
            division: "",
            server: ""
        },
        options: {
            specificChampions: false,
            priorityOrder: false,
            streaming: false,
            discountCode: null
        }
    }
  })
  
  export const mutations = {
      setSlider(state , payload){
        state.order.slider.wins =payload.wins;
        state.order.slider.radio =payload.radio;
      },
      setSelector(state,payload){
        state.order.selector.tier = payload.tier;
        state.order.selector.division = payload.division;
        state.order.selector.server = payload.server;
      },
      setOptions(state,payload){
        state.order.options.specificChampions = payload.specificChampions;
        state.order.options.priorityOrder = payload.priorityOrder;
        state.order.options.streaming = payload.streaming;
        state.order.options.discountCode = payload.discountCode;
      }
  }