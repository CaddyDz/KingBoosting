<template>
    <stepper />
</template>

<script>
import Stepper from '~/components/Stepper'
    export default {
        props:["service"],
        components:{
            Stepper
        }
    }
</script>
