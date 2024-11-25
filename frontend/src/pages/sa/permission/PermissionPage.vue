<script setup lang="ts">
import DataTable from '@src/tables/DataTable.vue'
import CreateModal from '@src/modals/CreateModal.vue'
import { ref } from 'vue'

const isOpen = ref(false)

const showModal = () => {
  isOpen.value = true
};

const closeModal = () => {
  isOpen.value = false
};

const sendDataToTable = {
  urls: {
    'data_url': 'security-access/permissions',
    'view_url': 'security-access/permissions',
    'edit_url': 'security-access/permissions',
    'status_url': 'security-access/permissions',
    'delete_url': 'security-access/permissions'
  },
  custom_id: 'id',
  th_name: {
    '1': 'Sl',
    '2': 'Name',
  },
  last_th_name: 'Action',
  th_align: 'left',
  last_th_align: 'center',
  td_data: {
    '1': 'sl', // not change
    '2': 'permission_name',
  },
  td_align: 'left',
  last_td_align: 'center',
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

  <DataTable :request-data="sendDataToTable" />
  <CreateModal v-if="isOpen" @close="closeModal" @userAdded="addNewUser" />
</template>
