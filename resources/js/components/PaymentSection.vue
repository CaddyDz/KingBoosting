<template>
    <v-stepper-content step="1">
      <v-card color="black lighten-1" class="mb-12" outlined>
        <v-container>
          <!-- Checkboxes and ETA -->
          <v-row no-gutters>
            <v-col md="6">
              <v-checkbox label="Specific champions at +20% cost" prepend-icon="mdi-account-group"></v-checkbox>
              <v-checkbox label="Priority order (2x speed) at +25% cost" prepend-icon="mdi-flash"></v-checkbox>
              <v-checkbox label="With Streaming +15% cost" prepend-icon="mdi-video"></v-checkbox>
            </v-col>
            <v-col md="6">
              <!-- ETA -->
              <p class="text-right">Approximate completion time: {{ eta }}</p>
            </v-col>
          </v-row>
          <v-row>
            <v-col md="4">
              <strong class="red--text text--lighten-1">
                <s>€24.00($26.66)</s>
              </strong>
              <br />
              €{{ price }} ($15.44) OR 1,390
            </v-col>
            <v-col md="4">
              <v-text-field
                label="Discount Code"
                placeholder="Discount Code"
                outlined
                append-icon="mdi-check-bold"
              ></v-text-field>
            </v-col>
            <v-col md="4">
              <div class="text-center">
                <v-dialog v-model="dialog" width="940">
                  <template v-slot:activator="{ on }">
                    <v-btn class="float-right" outlined color="purple" dark v-on="on">BOOST ME</v-btn>
                  </template>
                  <!-- Entire stepper -->
                  <!-- <checkout-dialog ></checkout-dialog> -->
                </v-dialog>
              </div>
            </v-col>
          </v-row>
          <!-- Price and discount -->
          <v-row></v-row>
        </v-container>
      </v-card>
    </v-stepper-content>
  
</template>

<script>
export default {
  data() {
    return {

      // Default exchange rate, to be changed by API call
      eta : "",
      exchangeRate: 1.1003,
      specificChampions: false,
      priorityOrder: false,
      streaming: false,
      //THE FUCKIN DIALOG
			dialog: false,
      // Login form data
      valid: false,
      email: "",
      emailRules: [
        v => !!v || "E-mail is required",
        v => /.+@.+/.test(v) || "E-mail must be valid"
      ],
      password: "",
      passwordRules: [
        v => !!v || "Password is required",
        v => v.length > 8 || "Password must be longer than 8 characters"
      ],
      // Details form data
      nickname: "",
      comment: "",
      offline: false,
      boosters: [], // Get it from local API call,
      boostersLoading: false,

      login : ""
    };
  },
  computed: {
    price() {
      return this.$store.getters['tierList/getPrice'];
    },
  },
  watch : {

  },
  mounted() {
    // Default value for ETA is the tier's ETA for 4 wins
    // Default value for Price is the tier's division price for 4 wins
    setTimeout(() => {
      // this.price = _.find(this.tier.divisions); // Get currently selected division using VueX
      this.eta = this.$store.getters['tierList/getDefaultETA']
      
    }, 2000);
    this.$root.$on("wins_changed", value => {
      // Get eta from the store
      this.$store.commit('tierList/updateETA', _.find(this.$store.getters['tierList/getSelectedTier'].wins, ["wins", value]).eta)
      this.eta = this.$store.getters['tierList/getETA']
      // this.price = (this.price * value).toFixed();
    });
    this.$root.$on('change', value =>{
      this.eta = value;
      
    })
  //console.log(this.$store.state.tierList.eta)
    
  },
};
</script>
