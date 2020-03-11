<template>
  <v-stepper v-model="e1">

    <v-stepper-header>
      <!-- 3 templates here -->
      <template>
        <v-stepper-step key="login" :complete="isLoggedIn" :step="1">{{ $t('Login') }}</v-stepper-step>
        <v-divider v-if="1 !== steps" :key="1"></v-divider>
      </template>
      <template>
        <v-stepper-step key="details" :complete="e1 > 2" :step="2" editable>{{ $t('Details') }}</v-stepper-step>
        <v-divider v-if="2 !== steps" :key="2"></v-divider>
      </template>
      <template>
        <v-stepper-step key="pay" :complete="el > 3" :step="3" editable>Pay</v-stepper-step>
        <v-divider v-if="3 !== steps" :key="3"></v-divider>
      </template>
    </v-stepper-header>
    <v-stepper-items>
      <v-stepper-content key="1-content" :step="1">
        <!-- If user is already logged in, this step should be skipped -->
        <LoginComponent :config="{button:true}" v-if="!isLoggedIn"></LoginComponent>
      </v-stepper-content>
      <v-stepper-content key="2-content" :step="2">
        <v-form v-model="valid">
          <v-container>
            <v-row>
              <!-- Summoner -->
              <v-col cols="12" md="6">
                <v-text-field v-model="nickname" :label="$t('Nickname *')" required
                  :placeholder="$t('Your in-game name')"></v-text-field>
              </v-col>
              <!-- Select Booster -->
              <v-col class="d-flex" cols="12" sm="6">
                <v-select :items="boosters" :label="$t('Your Booster')" :loading="boostersLoading"></v-select>
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="12" md="6">
                <v-text-field v-model="comment" :label="$t('Comments NOT REQUIRED')" :placeholder="$t('Your comments')"
                  required></v-text-field>
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="12" md="6">
                <v-checkbox :label="$t('Appear Offline on Chat')" prepend-icon="mdi-account" v-model="offline">
                </v-checkbox>
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="12" md="6">
                <v-icon>mdi-information-outline</v-icon>
                <span>{{ $t('Further information will be requested after payment') }}</span>
              </v-col>
            </v-row>
          </v-container>
        </v-form>
        <v-btn color="primary" @click="nextStep(2)">{{ $t('Next') }}</v-btn>
        <v-btn text>{{ $t('Cancel') }}</v-btn>
      </v-stepper-content>
      <v-stepper-content key="3-content" :step="3">
        <v-card class="mb-12" color="grey lighten-1" height="200px"></v-card>
        <v-btn color="primary" @click="boostMe">{{ $t('Continue') }}</v-btn>
        <v-btn text>{{ $t('Cancel') }}</v-btn>
      </v-stepper-content>
    </v-stepper-items>
  </v-stepper>
</template>

<script>
  export default {
    computed: {
      plural(count, noun) {
        return (count, noun) => `${count} ${noun}${count !== 1 ? "s" : ""}`;
      },
      priceUSD() {
        return (this.price * this.exchangeRate).toFixed(2);
      },
      isLoggedIn() {
        return this.$store.getters['log_in_out/isLoggedIn'];
      }
    },
    data() {
      return {
        e1: 1,
        boosters: [],
        boostersLoading: true,
        valid: false,
        email: "",
        emailRules: [
          v => !!v || this.$i18n.t("E-mail is required"),
          v => /.+@.+/.test(v) || this.$i18n.t("E-mail must be valid")
        ],
        password: "",
        passwordRules: [
          v => !!v || this.$i18n.t("Password is required"),
          v =>
          v.length > 8 ||
          this.$i18n.t("Password must be longer than 8 characters")
        ],

      }
    },
    methods: {
      getBoostersList() {
        this.$axios.get("/getBoostersNames").then(res => {
          this.boosters = res.data;
          this.boostersLoading = false;
        }).catch(err => {
          // TODO:
        })
      },
      sendResetPasswordEmail() {
        this.$axios
          .post("/password/email", {
            email: this.email
          })
          .then(response => {
            alert(response.data.status);
          })
          .catch(error => {
            alert(error.response.data.errors.email[0]);
          });
      },
      boostMe() {
        // TODO:
      },
      login() {
        this.$axios
          .post("/login", {
            email: this.email,
            password: this.password
          })
          .then(response => {
            alert(response.data.status);
            this.nextStep(1);
            // Make API call to get boosters
            this.getBoostersList();
          })
          .catch(error => {
            alert(error.response.data.errors);
          });
      },
      nextStep(n) {
        if (n === this.steps) {
          this.e1 = 1;
        } else {
          this.e1 = n + 1;
        }
      },
    },
    mounted() {
      this.getBoostersList();
    }
  }

</script>


<i18n>
  {
  "en": {
  "Select Your Current League": "Select Your Current League",
  "Current tier": "Current tier",
  "Select Your Number Of Wins": "Select Your Number Of Wins",
  "Solo/Duo": "Solo/Duo",
  "Flex 5v5": "Flex 5v5",
  "Checkout": "Checkout",
  "Specific champions at +20% cost": "Specific champions at +20% cost",
  "Priority order (2x speed) at +25% cost": "Priority order (2x speed) at +25% cost",
  "With Streaming +15% cost": "With Streaming +15% cost",
  "Approximate completion time:": "Approximate completion time:",
  "Discount Code": "Discount Code",
  "BOOST ME": "BOOST ME",
  "Details": "Details",
  "Nickname *": "Nickname *",
  "Your in-game name": "Your in-game name",
  "Comments NOT REQUIRED": "Comments NOT REQUIRED",
  "Your comments": "Your comments",
  "Appear Offline on Chat": "Appear Offline on Chat",
  "Further information will be requested after payment": "Further information will be requested after payment",
  "Forgotten Password?": "Forgotten Password?",
  "Continue": "Continue",
  "Cancel":"Cancel",
  "E-mail is required": "E-mail is required",
  "E-mail must be valid": "E-mail must be valid",
  "Password is required": "Password is required",
  "Password must be longer than 8 characters": "Password must be longer than 8 characters",
  "Login": "Login",
  "Select your server": "Select your server",
  "Current division": "Current division",
  "Win": "Win",
  "Your Booster": "Your Booster",
  "Next": "Next"
  }
  }
</i18n>
