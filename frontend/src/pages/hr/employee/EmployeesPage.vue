<script setup lang="ts">
import DataTable from '@src/tables/BasicDataTable.vue'
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
    'data_url': 'hr/employees',
    'view_url': 'hr/employees',
    'edit_url': 'hr/employees',
    'status_url': 'hr/employees',
    'delete_url': 'hr/employees'
  },
  custom_id: 'EMPLOYEE_ID',
  th_name: {
    '1': 'Sl',
    '2': 'Employee Name',
    '3': 'User Name',
    '4': 'Official Email',
    '5': 'Official Mobile',
    '6': 'Address',
  },
  last_th_name: 'Action',
  th_align: 'left',
  last_th_align: 'center',
  td_data: {
    '1': 'sl', // not change
    '2': 'EN_FULL_NAME',
    '3': 'USER_NAME',
    '4': 'OFIE_EMAIL',
    '5': 'OMOBILE_NO',
    '6': 'PRES_ADDRS',
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

// for status
const dataForStatusChange = {
  statusColumnName: 'ASTATUS_FG',
  statusChangeFor: 'EN_FULL_NAME',
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

  <DataTable :request-data="sendDataToTable" :status-data-one="dataForStatusChange" />
  <CreateModal v-if="isOpen" @close="closeModal" @userAdded="addNewUser" />
</template>
