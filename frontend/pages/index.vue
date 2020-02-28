<template>
  <v-layout
    column
  >
    <customer-voice />
    <why-us />
    <blogs />
  </v-layout>
</template>

<script>
import Blogs from '~/components/Blogs.vue'
import WhyUs from '~/components/WhyUs.vue'
import CustomerVoice from '~/components/CustomerVoice.vue'

export default {
  components: {
    Blogs,
    WhyUs,
    CustomerVoice
  }
}
</script>
