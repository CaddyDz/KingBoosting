<template>
  <v-dialog max-width="800px" v-model="showSignup">
    <template v-slot:activator="{ on }">
      <v-btn @click="handleSignup" color="#50AE55" block class="py-1">
        <v-icon small>mdi-email</v-icon>
        <span>REGISTER WITH EMAIL</span>
      </v-btn>
    </template>
    <v-card>
      <v-container class="pa-0">
        <v-row>
          <v-col class="pa-0">
            <v-card color="#303030">
                <v-tabs hide-slider dark background-color="transparent" class="pa-5" v-model="selectedTab">
                  <v-tab :disabled="selectedTab !== 0" key="#tab-1">
                    <div 
                      class="tab-title-circle" 
                      :style="tabNumberBG(0)"
                    >
                      1
                    </div>
                    <span>Login</span>
                  </v-tab>
                  <v-divider></v-divider>
                  <v-tab :disabled="selectedTab !== 1" key="#tab-2">
                    <div 
                      class="tab-title-circle" 
                      :style="tabNumberBG(1)"
                    >
                      2
                    </div>
                    <span>Details</span>
                  </v-tab>
                  <v-divider></v-divider>
                  <v-tab :disabled="selectedTab !== 2" key="#tab-3">
                    <div 
                      class="tab-title-circle" 
                      :style="tabNumberBG(2)"
                    >
                      3
                    </div>
                    <span>Pay</span>
                  </v-tab>
                </v-tabs>
            </v-card>
          </v-col>
        </v-row>
        <v-row class="px-6">
          <v-col>
            <v-tabs-items v-model="selectedTab">
                <v-tab-item key="#tab-1">
                    <v-form>
                        <v-container>
                          <v-row>
                              <v-text-field label="Email address *" placeholder="Your email address"></v-text-field>
                          </v-row>
                          <v-row>
                              <v-text-field label="Username *" placeholder="Your username"></v-text-field>
                          </v-row>
                          <v-row>
                              <v-text-field label="First name *" placeholder="Your first name"></v-text-field>
                          </v-row>
                          <v-row>
                              <v-text-field label="Last name *" placeholder="Your last name"></v-text-field>
                          </v-row>
                          <v-row>
                              <v-text-field label="Skype / Discord" placeholder="Your Skype or Discord"></v-text-field>
                          </v-row>
                          <v-row class="justify-end">
                              <v-btn @click="goToTab(1)" color="#673AB7">Register</v-btn>
                          </v-row>
                        </v-container>
                    </v-form>
                </v-tab-item>
                <v-tab-item key="#tab-2">
                  <h2>Details Tab</h2>
                </v-tab-item>
                <v-tab-item key="#tab-3">
                  <h2>Pay Tab</h2>
                </v-tab-item>
            </v-tabs-items>

          </v-col>
        </v-row>
      </v-container>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  props: {
    closeLogin: {
      type: Function,
      default: () => {}
    }
  },
  data() {
    return {
      showSignup: false,
      selectedTab: 0
    };
  },
  methods: {
    handleSignup() {
      this.closeLogin();
      this.showSignup = true;
    },
    goToTab(tab) {
      this.selectedTab = tab;
    },
    tabNumberBG(tab) {
      return {
        backgroundColor: this.selectedTab === tab ? "#673ab7" : "#9E9E9E"
      }
    }
  }
};
</script>

<style scoped>
.v-btn:hover:before {
  content: none;
}

.container {
    background-color: #303030;
}

.row {
  margin-left: 0;
  margin-right: 0;
}

.v-divider {
  align-self: center;
}

.v-tab {
  padding: 0;
  text-transform: none;
}

.v-tab--active {
  color: #fff !important;
}

.tab-title-circle {
  display: flex;
  justify-content: center;
  align-items: center;
  width: 25px;
  height: 25px;
  border-radius: 50%;
  background-color: #673ab7;
  margin-right: 5px;
  color: #fff;
  font-size: 0.7rem;
}
</style>