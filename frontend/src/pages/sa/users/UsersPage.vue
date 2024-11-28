<script setup lang="ts">
import DataTable from '@src/tables/DataTable.vue'
import CreateModal from '@src/modals/CreateModal.vue'
import CreateModalSlotData from '@src/pages/sa/users/CreateSlotData.vue'
import EditModalSlotData from '@src/pages/sa/users/EditSlotData.vue'
import ViewModal from '@src/modals/ViewModal.vue'
import ViewModalSlotData from '@src/pages/sa/users/ViewSlotData.vue'

import { ref, reactive } from 'vue'


// for data table
// ==============
const sendDataToTable = {
  urls: {
    'data_url': 'security-access/users',
    'view_url': 'security-access/users',
    'edit_url': 'security-access/users',
    'status_url': 'security-access/user-status',
    'delete_url': 'security-access/user-delete'
  },
  custom_id: 'user_id',
  custom_name: 'efull_name',
  th_name: {
    '1': 'Sl',
    '2': 'Username',
    '3': 'Name',
    '4': 'Official Email',
    '5': 'Official Mobile',
  },
  last_th_name: 'Action',
  th_align: 'left',
  last_th_align: 'center',
  td_data: {
    '1': 'sl', // not change
    '2': 'user_name',
    '3': 'efull_name',
    '4': 'ofie_email',
    '5': 'omobile_no',
  },
  td_align: 'left',
  last_td_align: 'center',
};


// for create table
// ================
const isCMOpen = ref(false);
const uData = ref({});
const showCreateModal = () => {
  isCMOpen.value = true
};

const closeCreateModal = () => {
  isCMOpen.value = false
};


// Push the newly added user to the data array
const addNewUser = (newUser: object) => {
  uData.value = newUser;
  const sl = data.value.length + 1 // Generate new serial number for the user
  data.value.push({
    ...newUser,
    sl
  })
}

const sendUserDataToCreateForm = {
  create_url: 'security-access/user-register',
  employee_get_url: 'hr/all-employees',
  role_get_url: 'security-access/roles',
  }

const sendPropDataForCm = reactive({
  config: {
    title: 'Add New User', /* modal title */
    titleBgColor: '#82c953', /* modal title */
    titleTextColor: 'white', /* title text color*/
    // height: 45, /* modal height */
    // width: 35, /* modal width*/
    footer: true, /* modal footer*/
    footerButtonBgColor: 'red', /* modal close button background color*/
  }
});

// for view modal 
// ==============

const isVMOpen = ref(false)

const showViewModal = () => {
  isVMOpen.value = true
};

const closeViewModal = () => {
  isVMOpen.value = false
};

const emitDataForView = () => {
  showViewModal();
}

const userInformation = ref({});

const userDataForViewModal = (userData: object) => {
  userInformation.value = userData;
}

const sendPropDataForVm = reactive({
  config: {
    title: 'User Information', /* modal title */
    titleBgColor: '#82c953', /* modal title */
    titleTextColor: 'white', /* title text color*/
    // height: 52, /* modal height */
    // width: 40, /* modal width*/
    footer: true, /* modal footer*/
    footerButtonBgColor: 'red', /* modal close button background color*/
  }
});

let clickOnFinished = {};


// for edit
const sendEditSlotToTable = {
  editComponent: EditModalSlotData, // Pass the Vue file as a prop
};


</script>

<template>
  <a-row>
    <a-col :span="2">
      <a-button type="primary" block @click="showCreateModal">Add</a-button>
    </a-col>
    <a-col :span="16"></a-col>
    <a-col :span="6">
      <!-- <a-input v-model:value="value" placeholder="Search here" /> -->
    </a-col>
  </a-row>

  <DataTable :request-data="sendDataToTable" :edit-data="sendEditSlotToTable" @isViewModalOpen="emitDataForView"
    @userDataForView="userDataForViewModal" />
  <CreateModal v-if="isCMOpen" @close="closeCreateModal" :modal-config-data="sendPropDataForCm">
    <CreateModalSlotData @formData="addNewUser" :user-data="sendUserDataToCreateForm" />
  </CreateModal>
  <ViewModal v-if="isVMOpen" @closeVm="closeViewModal" :user-view-modal-data="sendPropDataForVm">
    <ViewModalSlotData :selected-user-information="userInformation" />
  </ViewModal>
</template>
