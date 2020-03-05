<template>
  <div>
    <h1> #{{slug.name}}</h1>
    <stepper />
  </div>
</template>

<script>
import Stepper from '~/components/Stepper' 
export default {
    props:["slug"],
    components:{
        Stepper
    }
}
</script>

<style>

</style>