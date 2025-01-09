<script setup lang="ts">
import DataTable from '../../tables/BasicDataTable.vue'
import CreateModal from '../../modals/CreateModal.vue'
import { ref } from 'vue'

const isOpen = ref(false)

const showModal = () => {
  isOpen.value = true
}

const closeModal = () => {
  isOpen.value = false
}

const sendDataToTable = {
  'url': 'users'
};


// Push the newly added user to the data array
const addNewUser = (newUser: object) => {
  const sl = data.value.length + 1 // Generate new serial number for the user
  data.value.push({
    ...newUser,
    sl
  })
}
</script>

<template>
  <a-row>
    <a-col :span="2">
      <a-button type="primary" block @click="showModal">Add</a-button>
    </a-col>
    <a-col :span="16"></a-col>
    <a-col :span="6">
      <a-input v-model:value="value" placeholder="Search here" />
    </a-col>
  </a-row>

  <DataTable :request-data="sendDataToTable"/>
  <CreateModal v-if="isOpen" @close="closeModal" @userAdded="addNewUser" />
</template>
