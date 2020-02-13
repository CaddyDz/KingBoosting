<template>
  <div>
    <v-col md="4">
      <div class="text-center">
        <v-dialog v-model="dialog" width="940">
          <template v-slot:activator="{ on }">
            <v-btn class="float-right" outlined color="purple" dark v-on="on">BOOST ME</v-btn>
          </template>
          <!-- Entire stepper -->
          <v-stepper v-model="e1">
            <template>
              <v-stepper-header>
                <!-- 3 templates here -->
                <template>
                  <v-stepper-step key="login" :complete="e1 > 1" :step="1" editable>Login</v-stepper-step>
                  <v-divider v-if="1 !== steps" :key="1"></v-divider>
                </template>
                <template>
                  <v-stepper-step key="details" :complete="e1 > 2" :step="2" editable>Details</v-stepper-step>
                  <v-divider v-if="2 !== steps" :key="2"></v-divider>
                </template>
                <template>
                  <v-stepper-step key="pay" :complete="e1 > 3" :step="3" editable>Pay</v-stepper-step>
                  <v-divider v-if="3 !== steps" :key="3"></v-divider>
                </template>
              </v-stepper-header>
              <v-stepper-items>
                <v-stepper-content key="1-content" :step="1">
                  <v-row>
                    <v-col md="8">
                      <v-form v-model="valid">
                        <v-container>
                          <v-row>
                            <v-text-field
                              v-model="email"
                              :rules="emailRules"
                              label="E-mail address *"
                              required
                              placeholder="Your email address"
                            ></v-text-field>
                          </v-row>
                          <v-row>
                            <v-text-field
                              type="password"
                              v-model="password"
                              :rules="passwordRules"
                              :counter="10"
                              label="Password *"
                              required
                            ></v-text-field>
                          </v-row>
                        </v-container>
                      </v-form>
                      <v-btn text @click="sendResetPasswordEmail">Forgotten Password?</v-btn>
                      <v-btn color="primary" @click="login">Continue</v-btn>
                      <v-btn text>Cancel</v-btn>
                    </v-col>
                    <v-col md="4">
                      <v-card color="black">
                        <v-card-title>
                          <v-icon>mdi-account-edit</v-icon>New Customers
                        </v-card-title>
                        <v-card-text>
                          <v-btn color="blue" block href="/login/facebook">
                            <v-icon color="white">mdi-facebook-box</v-icon>Login with Facebook
                          </v-btn>
                          <br />
                          <v-btn color="red" block>
                            <v-icon color="white">mdi-google-plus</v-icon>Sign in with Google+
                          </v-btn>
                          <br />
                          <v-btn color="green" block>
                            <v-icon color="white">mdi-email</v-icon>Register with email
                          </v-btn>
                          <br />
                          <v-btn color="grey" block>
                            <v-icon color="white">mdi-account-circle</v-icon>Continue as Guest
                          </v-btn>
                        </v-card-text>
                      </v-card>
                    </v-col>
                  </v-row>
                </v-stepper-content>
                <v-stepper-content key="2-content" :step="2">
                  <v-form v-model="valid">
                    <v-container>
                      <v-row>
                        <!-- Summoner -->
                        <v-col cols="12" md="6">
                          <v-text-field
                            v-model="nickname"
                            label="Nickname *"
                            required
                            placeholder="Your in-game name"
                          ></v-text-field>
                        </v-col>
                        <!-- Select Booster -->
                        <v-col class="d-flex" cols="12" sm="6">
                          <v-select
                            :items="boosters"
                            label="Your Booster"
                            :loading="boostersLoading"
                          ></v-select>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col cols="12" md="6">
                          <v-text-field
                            v-model="comment"
                            label="Comments NOT REQUIRED"
                            placeholder="Your comments"
                            required
                          ></v-text-field>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col cols="12" md="6">
                          <v-checkbox
                            label="Appear Offline on Chat"
                            prepend-icon="mdi-account"
                            v-model="offline"
                          ></v-checkbox>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col cols="12" md="6">
                          <v-icon>mdi-information-outline</v-icon>
                          <span>Further information will be requested after payment</span>
                        </v-col>
                      </v-row>
                    </v-container>
                  </v-form>
                  <v-btn text @click="sendResetPasswordEmail">Forgotten Password?</v-btn>
                  <v-btn color="primary" @click="login">Continue</v-btn>
                  <v-btn text>Cancel</v-btn>
                </v-stepper-content>
                <v-stepper-content key="3-content" :step="3">
                  <v-card class="mb-12" color="grey lighten-1" height="200px"></v-card>
                  <v-btn color="primary" @click="nextStep(3)">Continue</v-btn>
                  <v-btn text>Cancel</v-btn>
                </v-stepper-content>
              </v-stepper-items>
            </template>
          </v-stepper>
        </v-dialog>
      </div>
    </v-col>
  </div>
</template>

<script>
export default {};
</script>

<style>
</style>