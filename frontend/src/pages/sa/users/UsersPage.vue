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
    'edit_url': 'security-access/user-update',
    'status_url': 'security-access/user-status',
    'delete_url': 'security-access/user-delete'
  },
  custom_id: 'user_id',
  custom_name: 'en_full_name',
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
    '3': 'en_full_name',
    '4': 'ofie_email',
    '5': 'omobile_no',
  },
  td_align: 'left',
  last_td_align: 'center',
};

// for create table
// ================
const isCMOpen = ref(false);
const newData = ref({});
const showCreateModal = () => {
  isCMOpen.value = true
};

const closeCreateModal = () => {
  isCMOpen.value = false
};

interface User {
  [key: string]: any; // Allow other dynamic properties
}
// Push the newly added user to the data array
const addNewUser = (newUser: User) => {
  newData.value = {
    ...newUser,
    id: newUser.user_id, // Add the 'id' field
    name: newUser.en_full_name
  };
  setTimeout(() => {
    newData.value = {}; // Clear after updating
  }, 0);
}

let updatedUserInfo: object;
const updatedUser = (uUser: object) => {
  updatedUserInfo = {
    ...uUser,
    id: uUser.user_id,
  }
}

const sendUserDataToCreateForm = {
  create_url: 'security-access/user-register',
  employee_get_url: 'hr/all-employees',
  role_get_url: 'security-access/roles',
}

const sendPropDataForCM = reactive({
  config: {
    title: 'Add New User', /* modal title */
    titleBgColor: '#82c953', /* modal title */
    titleTextColor: 'white', /* title text color*/
    // height: 45, /* modal height */
    width: 60, /* modal width*/
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

const userdataForView = (userData: object) => {
  userInformation.value = {
    ...userData,
    role_get_url: 'security-access/roles'
  }
}

const sendPropDataForVM = reactive({
  config: {
    title: 'User Information', /* modal title */
    titleBgColor: '#82c953', /* modal title */
    titleTextColor: 'white', /* title text color*/
    // height: 52, /* modal height */
    width: 60, /* modal width*/
    footer: true, /* modal footer*/
    footerButtonBgColor: 'red', /* modal close button background color*/
  }
});

// for edit
const sendEditSlotToTable = {
  editComponent: EditModalSlotData, // Pass the Vue file as a prop
  sendPropDataForEM: {
    config: {
      title: 'Update User Information', /* modal title */
      titleBgColor: '#82c953', /* modal title */
      titleTextColor: 'white', /* title text color*/
      // height: 45, /* modal height */
      width: 60, /* modal width*/
      footer: true, /* modal footer*/
      footerButtonBgColor: 'red', /* modal close button background color*/
    }
  },
  othersData: {
    role_get_url: 'security-access/roles',
  }
};

// for status
const dataForStatusChange = {
  statusColumnName: 'status',
  statusChangeFor: 'user_name',
}

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

  <DataTable :request-data="sendDataToTable" :data-for-create="newData" :data-for-update="updatedUserInfo"
    :edit-data="sendEditSlotToTable" :status-data="dataForStatusChange" @isViewModalOpen="emitDataForView"
    @dataForView="userdataForView" />
  <CreateModal v-if="isCMOpen" @close="closeCreateModal" :modal-config-data="sendPropDataForCM">
    <CreateModalSlotData @closeCM="closeCreateModal" @newAddedUserData="addNewUser" @updatedUserData="updatedUser"
      :user-data="sendUserDataToCreateForm" />
  </CreateModal>
  <ViewModal v-if="isVMOpen" @closeVm="closeViewModal" :view-modal-config-data="sendPropDataForVM">
    <ViewModalSlotData :selected-user-information="userInformation" />
  </ViewModal>
</template>
