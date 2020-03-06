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
                        <img src="/img/blogPosts/Mistakes-blog.jpg" alt="tier.name" loading="lazy" width="200" />
                    </v-col>
                    <v-col md="9" class="have-selectors">
                    <v-container>
                        <v-row >
                            <v-col>
                                <v-select
                                :items="items"
                                label="Standard"
                                dense
                                solo
                                ></v-select>
                            </v-col>
                            <v-col v-if="hasDivisions">
                                <v-select
                                :items="items"
                                label="Solo field"
                                dense
                                solo
                                ></v-select>
                            </v-col>
                        </v-row>
                        <v-select
                            :items="items"
                            label="Standard"
                            dense
                            solo
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
            items: ['Foo', 'Bar', 'Fizz', 'Buzz'],
            hasDivisions:true
        }
    }
}
</script>

<style scoped>
    .have-selectors{
        padding-right: 20px;
        padding-left: 20px;
    }

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