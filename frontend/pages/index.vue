<template>
    <v-layout column >
        <services-circle />
        <features />
        <customer-voice />
        <how-to-buy />
        <WhyUs />
        <Blogs />
    </v-layout>
</template>

<script>
import CustomerVoice from '~/components/CustomerVoice.vue';
import ServicesCircle from '~/components/ServicesCircle.vue';
import WhyUs from '~/components/WhyUs.vue';
import Blogs from '~/components/Blogs.vue';
import Features from '~/components/Features.vue';
import HowToBuy from '~/components/HowToBuy.vue';

export default {
    components:{
        WhyUs,
        Blogs,
        CustomerVoice,
        ServicesCircle,
        Features,
        HowToBuy
    }
}
</script>