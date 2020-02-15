<template>
    <v-stepper-content step="1">
        <v-card color="black lighten-1" class="mb-12" outlined>
            <v-container>
                <!-- Checkboxes and ETA -->
                <v-row no-gutters>
                    <v-col md="6">
                        <v-checkbox
                            label="Specific champions at +20% cost"
                            prepend-icon="mdi-account-group"
                            v-model="specificChampions"
                        ></v-checkbox>
                        <v-checkbox
                            label="Priority order (2x speed) at +25% cost"
                            prepend-icon="mdi-flash"
                            v-model="priorityOrder"
                        ></v-checkbox>
                        <v-checkbox
                            label="With Streaming +15% cost"
                            prepend-icon="mdi-video"
                            v-model="streaming"
                        ></v-checkbox>
                    </v-col>
                    <v-col md="6">
                        <!-- ETA -->
                        <p class="text-right">
                            Approximate completion time: {{ eta }}
                        </p>
                    </v-col>
                </v-row>
                <v-row>
                    <v-col md="4">
                        <strong class="red--text text--lighten-1">
                            <s>€24.00($26.66)</s>
                        </strong>
                        <br />
                        €{{ price.toFixed(2) }} ($15.44) OR 1,390
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
                                    <v-btn
                                        class="float-right"
                                        outlined
                                        color="purple"
                                        dark
                                        v-on="on"
                                        >BOOST ME</v-btn
                                    >
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
            price : 1.90,
            eta: "",
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

            login: ""
        };
    },
    computed: {

    },
    watch: {
      eta(){

      },
      price(){},
      specificChampions(value){
        if (value) {
          // If checked
          this.$store.commit('tierList/updatePricePlusPercent', 20);
          this.price = this.$store.getters['tierList/getPrice'];
        } else {
          // If unchecked
          this.$store.commit('tierList/updatePriceMinusPercent', 20);
          this.price = this.$store.getters['tierList/getPrice'];
        }
      },
      priorityOrder(value){
        if (value) {
          // If checked
          this.$store.commit('tierList/updatePricePlusPercent', 25);
          this.price = this.$store.getters['tierList/getPrice'];
        } else {
          // If unchecked
          this.$store.commit('tierList/updatePriceMinusPercent', 25);
          this.price = this.$store.getters['tierList/getPrice'];
        }
      },
      streaming(value){
        if (value) {
          // If checked
          this.$store.commit('tierList/updatePricePlusPercent', 15);
          this.price = this.$store.getters['tierList/getPrice'];
        } else {
          // If unchecked
          this.$store.commit('tierList/updatePriceMinusPercent', 15);
          this.price = this.$store.getters['tierList/getPrice'];
        }
      },
    },
    beforeMount(){

    },
    mounted() {
        // Default value for ETA is the tier's ETA for 4 wins
        // Default value for Price is the tier's division price for 4 wins

        setTimeout(() => {
            // Get currently selected division using VueX
        this.price = this.$store.getters['tierList/getPrice'];

            this.eta = this.$store.getters["tierList/getDefaultETA"];
        }, 2000);

        this.$root.$on("wins_changed", value => {
            // SAVE CURRENT ETA ON VUEX AND FETCH IT AGAIN
            this.eta = this.$store.getters['tierList/getETA'];
            this.price = this.$store.getters['tierList/getPrice'];
        });

        this.$root.$on("change", value => {
            this.eta = value;
        });
        this.$root.$on('price_updated', value => {
            this.price = this.$store.getters['tierList/getPrice']
        } )
        // console.log(_.find(this.$store.getters['tierList/getSelectedTier'].wins, ["wins", 4]).eta)
        // console.log(this.$store.state.tierList.eta)
    }
};
</script>
