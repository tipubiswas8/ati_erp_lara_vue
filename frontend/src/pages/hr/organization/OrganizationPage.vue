<script setup lang="ts">
import DataTable from '@/tables/BasicDataTable.vue'
import CreateModal from '@/modals/CreateModal.vue'
import CreateModalSlotData from '@/pages/sa/users/CreateSlotData.vue'
import EditModalSlotData from '@/pages/sa/users/EditSlotData.vue'
import ViewModal from '@/modals/ViewModal.vue'
import ViewModalSlotData from '@/pages/sa/users/ViewSlotData.vue'
import { ref, reactive } from 'vue'

// for data table
// ==============
const sendDataToTable = {
  urls: {
    'data_url': 'hr/organizations',
    'view_url': 'hr/organizations',
    'edit_url': 'hr/organizations',
    'status_url': 'hr/organizations',
    'delete_url': 'hr/organizations'
  },
  custom_id: 'ORG_ID',
  custom_name: 'en_full_name',
  th_name: {
    '1': 'Sl',
    '2': 'ORG ID',
    '3': 'Name',
    '4': 'ABBR',
    '5': 'EMAIL',
    '6': 'PHONE',
    '7': 'WEBSITE',
  },
  last_th_name: 'Action',
  th_align: 'left',
  last_th_align: 'center',
  td_data: {
    '1': 'sl', // not change
    '2': 'ORG_ID',
    '3': 'ORG_NAME',
    '4': 'ORG_ABBR',
    '5': 'ORG_EMAIL',
    '6': 'ORG_PHONE',
    '7': 'ORG_WEBSITE'
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

const userDataForViewModal = (userData: object) => {
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
  statusColumnName: 'ORG_STATUS',
  statusChangeFor: 'ORG_NAME',
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

  <DataTable :table-data-one="sendDataToTable" :data-for-create="newData" :update-data-one="updatedUserInfo"
    :edit-data="sendEditSlotToTable" :status-data-one="dataForStatusChange" @isViewModalOpen="emitDataForView"
    @dataForViewModal="userDataForViewModal" />
  <CreateModal v-if="isCMOpen" @close="closeCreateModal" :modal-config-data="sendPropDataForCM">
    <CreateModalSlotData @closeCM="closeCreateModal" @newAddedUserData="addNewUser" @updatedUserData="updatedUser"
      :user-data="sendUserDataToCreateForm" />
  </CreateModal>
  <ViewModal v-if="isVMOpen" @closeVm="closeViewModal" :user-view-modal-data="sendPropDataForVM">
    <ViewModalSlotData :selected-user-information="userInformation" />
  </ViewModal>
</template>
