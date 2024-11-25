<template>
<div class="container">
<div class="col-md-12">
  
</div>
</div>
</template>

<script lang="ts" setup>
import { defineProps, defineEmits } from 'vue'

const props = defineProps({
  selectedUserInformation: Object
})

const user = props.selectedUserInformation;

console.log(user);

// const emit = defineEmits(['closeVm', 'userAdded'])
// const handleVMCancel = () => {
//   emit('closeVm')
// }
</script>
