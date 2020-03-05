<template>
  <div>
    <h1> #{{service}}</h1>
    <stepper />
  </div>
</template>

<script>
import Stepper from '~/components/Stepper' 
export default {
    components:{
        Stepper
    },
    data(){
      return {
        service: ""
      }
    },
    mounted(){
      this.service = this.$attrs.service
    }
}
</script>

<style>

</style>