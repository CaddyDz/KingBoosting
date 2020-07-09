export const state = () => ({
  tier: {
    wins: [{
      wins: 4,
      eta: "0-1 day"
    }]
  },
  division: {},
  server: "EU-West",
  wins: 4,
  mode: "Solo/Duo",
  options: [],
  discountCode: null,
  basePrice: 3.9,
  percent: 0,
  nickname: '',
  booster: null,
  comments: '',
  offline: false
});

export const mutations = {
  changeTier(state, payload) {
    state.tier = payload;
  },
  changeDivision(state, payload) {
    state.division = payload;
  },
  changeServer(state, payload) {
    state.server = payload
  },
  changeNumberOfWins(state, wins) {
    state.wins = wins;
  },
  changeGameMode(state, mode) {
    state.mode = mode;
  },
  addOption(state, payload) {
    state.options.push(payload);
  },
  removeOption(state, payload) {
    state.options = _.remove(state.options, (option) => option != payload);
  },
  changeDiscountCode(state, payload) {
    state.discountCode = payload;
  },
  changeBasePrice(state, price) {
    state.basePrice = price;
  },
  addPercent(state, percent) {
    state.percent += percent;
  },
  substractPercent(state, percent) {
    state.percent -= percent;
  },
  changeNickname(state, payload) {
    state.nickname = payload;
  },
  changeBooster(state, payload) {
    state.booster = payload;
  },
  changeComment(state, payload) {
    state.comments = payload;
  },
  changeChatMode(state, payload) {
    state.offline = payload;
  }
}

export const getters = {
  eta(state) {
    return _.find(state.tier.wins, [
      "wins",
      state.wins
    ]).eta;
  },
  price(state) {
    let price = state.basePrice * state.wins;
    price += (price * state.percent) / 100;
    return price;
  }
}
