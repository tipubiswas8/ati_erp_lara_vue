<template>
  <div>
    <AButton type="primary" @click="showModal">Open Modal</AButton>
    <AModal v-model:open="open" title="Basic Modal" @ok="handleOk">
      <p>Some contents...</p>
      <p>Some contents...</p>
      <p>Some contents...</p>
    </AModal>
  </div>
</template>
<script lang="ts" setup>
import { ref } from 'vue'
const open = ref<boolean>(false)

const showModal = () => {
  open.value = true
}

const handleOk = (e: MouseEvent) => {
  console.log(e)
  open.value = false
}
</script>
