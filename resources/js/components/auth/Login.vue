<template>
    <v-dialog max-width="800px" v-model="showLogin">
        <template v-slot:activator="{ on }">
            <v-btn 
                class="ma-2" outlined 
                color="white"
                @click="showLogin = true"
            >
                Members Area
            </v-btn>
        </template>
        <v-card class="px-5" color="#303030">
            <v-container>
                <v-row>
                    <v-col cols="7">
                        <v-form>
                            <v-layout column>
                                <v-flex>
                                    <v-text-field
                                        label="Email address *"
                                        placeholder="Your email address"
                                    ></v-text-field>
                                </v-flex>
                                <v-flex>
                                    <v-text-field
                                        type="password"
                                        label="Password *"
                                        placeholder="Your password"
                                    ></v-text-field>
                                </v-flex>
                                <v-flex>
                                    <v-row dark class="ml-2 mt-2">
                                        <v-btn text small>FORGOTTEN PASSWORD?</v-btn>
                                        <v-btn color="#673AB7" small>CONTINUE</v-btn>
                                        <v-btn text small>CANCEL</v-btn>
                                    </v-row>
                                </v-flex>
                            </v-layout>
                            
                        </v-form>
                    </v-col>
                    <v-col cols="5">
                        <v-card color="black">
                            <v-row class="pa-3"> 
                                <v-col class="pb-1">
                                    <div class="font-weight-medium">
                                        <v-icon small>mdi-account-edit</v-icon>
                                        <span>New Customer</span>
                                    </div>
                                </v-col>
                                <v-col class="pb-1">
                                    <v-btn color="#2C98F0" block class="py-1">
                                        <v-icon small>mdi-facebook</v-icon>
                                        <span>LOGIN WITH FACEBOOK</span>
                                    </v-btn>
                                </v-col>
                                <v-col class="pb-1">
                                    <v-btn color="#F2453D" block class="py-1">
                                        <v-icon small>mdi-google</v-icon>
                                        <span>LOGIN WITH GOOGLE</span>
                                    </v-btn>
                                </v-col>
                                <v-col class="pb-1">
                                    <signup :closeLogin="() => showLogin = false"></signup>
                                </v-col>
                                <v-col class="pb-1">
                                    <v-btn @click="showLogin = false" color="#9E9E9E" block class="py-1">
                                        <v-icon small>mdi-account-circle</v-icon>
                                        <span>CONTINUE AS GUEST</span>
                                    </v-btn>
                                </v-col>
                            </v-row>
                        </v-card>
                    </v-col>
                </v-row>
            </v-container>
        </v-card>
    </v-dialog>    
</template>

<script>
export default {
    data: () => {
        return {
            showLogin: false
        }
    }
}
</script>

<style scoped>
    .v-btn:hover:before {
        content: none;
    }
</style>
