<template>
    <div>
        <div class="title">
            <div class="title-id"><h2> {{step.id}} </h2></div>
            <h2 class="title-txt"> {{step.title}} </h2>
        </div>
        <v-card raised class="mb-4" >
            <v-container>
                <v-row align="center" justify="center">
                    <v-col md="3">
                        <img src="/img/divisions/diamond/II.webp" alt="tier.name" loading="lazy" width="200" />
                    </v-col>
                    <v-col md="9" class="have-selectors">
                    <v-container>
                        <v-row >
                            <v-col>
                                <v-select
                                :items="tiers"
                                label="Current tier"
                                dense
                                solo
                                v-model="leagueConfig.tier"
                                @change="selectChangeHandler({target:'tier' , value:$event})"
                                ></v-select>
                            </v-col>
                            <v-col v-if="hasDivisions">
                                <v-select
                                :items="divisions"
                                label="Current division"
                                dense
                                solo
                                v-model="leagueConfig.division"
                                @change="selectChangeHandler({target:'division' , value:$event})"
                                ></v-select>
                            </v-col>
                        </v-row>
                        <v-select
                            :items="servers"
                            label="Select your server"
                            dense
                            solo
                            v-model="leagueConfig.server"
                            @change="selectChangeHandler({target:'server' , value:$event})"
                        ></v-select>
                    </v-container>
                    </v-col>
                </v-row>
            </v-container>
        </v-card>
    </div>
</template>

<script>
export default {
    props: ["step"],
    data(){
        return {
            tiers: ["Iron","Bronze","Silver","Gold","Platinum","Diamond","Master","Grandmaster","Challenger"],
            divisions: ["Division I","Division II","Division III","Division IV"],
            servers: ["North America" ,"EU-West" ,"EU-Nordic & East" ,"Turkey","Russia","Brazil","Latin America North" ,"Latin America South","Oceania","Korea","PBE"],
            hasDivisions:true,
            leagueConfig :{
                tier: "Platinum",
                division :"Division II",
                server:"EU-West"
            }
        }
    },
    methods:{
        commitToStore(c){
            this.$store.commit("boosting_order/setSelector",c);
        },
        selectChangeHandler(e){
            this.leagueConfig[e.target] = e.value;
            this.commitToStore(this.leagueConfig);
        }
    },
    mounted(){
        this.commitToStore(this.leagueConfig);
    }
}
</script>

<style scoped>
.have-selectors {
	padding-right: 20px;
	padding-left: 20px;
}

.title {
	display: flex;
	flex-direction: row;
	align-items: center;
	margin-bottom: 20px;
}

.title-txt {
	margin-left: 20px;
}

.title .title-id {
	color: #181818;
}

.title-id {
	width: 50px;
	height: 50px;
	border-radius: 50%;
	background: linear-gradient(#673ab7, #9c27b0);
	display: flex;
	align-items: center;
	justify-content: center;
}
</style>
