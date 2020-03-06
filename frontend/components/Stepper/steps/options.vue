<template>
    <div>
        <div class="title">
            <div class="title-id"><h2> {{step.id}} </h2></div>
            <h2 class="title-txt"> {{step.title}} </h2>
        </div>
        <v-card raised class="mb-4" >
            <v-container class="container">
                <v-row>
                    <v-col class="left-col">
                        <v-checkbox v-model="checkbox1" label="Specific champions at +20% cost"></v-checkbox>
                        <v-checkbox v-model="checkbox2" label="Priority order (2x speed) at +25% cost"></v-checkbox>
                        <v-checkbox v-model="checkbox3" label="With Streaming +15% cost"></v-checkbox>
                    </v-col>
                    <v-col class="right-col">
                        <h5>yak</h5>
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
            checkbox1: false,
            checkbox2: false,
            checkbox3: false
        }
    }
}
</script>

<style scoped>
    .container{
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    .right-col{}

    .title{
        display: flex;
        flex-direction: row;
        align-items: center;
        margin-bottom: 20px;
    }

    .title-txt{
        margin-left: 20px;
    }

    .title .title-id{
        color: #181818;
    }

    .title-id{
        width: 50px;
        height: 50px;
        border-radius: 50%;
    	background: linear-gradient(#673ab7, #9c27b0);
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>