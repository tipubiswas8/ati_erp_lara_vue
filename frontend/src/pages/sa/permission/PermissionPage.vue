<script setup lang="ts">
import DataTable from '@src/tables/DataTable.vue'
import CreateModal from '@src/modals/CreateModal.vue'
import CreateModalSlotData from '@src/pages/sa/permission/CreateSlotData.vue'
import { ref, reactive } from 'vue'

// for data table
// ==============
const sendDataToTable = {
  // required
  urls: {
    'data_url': 'security-access/permissions',
    'view_url': 'security-access/permissions',
    'edit_url': 'security-access/permissions',
    'status_url': 'security-access/permissions',
    'delete_url': 'security-access/permissions'
  },
  th_name: {
    '1': 'Sl',
    '2': 'Permission Name',
    '3': 'Organization Name',
  },
  last_th_name: 'Action',
  // optional
  th_align: 'left',
  last_th_align: 'center',
  // required
  td_data: {
    '1': 'sl', // not change
    '2': 'permission_name',
    '3': 'org_name',
  },
  // optional
  td_align: 'left',
  last_td_align: 'center',
};


// for create table
// ================
// required
const isCMOpen = ref(false);
const newData = ref({});
const showCreateModal = () => {
  isCMOpen.value = true
};

const closeCreateModal = () => {
  isCMOpen.value = false
};

interface Permission {
  [key: string]: any; // Allow other dynamic properties
}
// Push the newly added permission to the data array
const addedNewPermission = (newPermission: Permission) => {
  newData.value = {
    ...newPermission
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

const sendPermissionDataToCreateForm = {
  create_url: 'security-access/permissions',
  org_get_url: 'hr/organizations'
}

const sendConfigDataToCM = reactive({
  // required
  config: {
    // optional
    title: 'Add New Permission', /* modal title */
    titleBgColor: '#82c953', /* modal title */
    titleTextColor: 'white', /* title text color*/
    // height: 45, /* modal height */
    // width: 60, /* modal width*/
    footer: true, /* modal footer*/
    footerButtonBgColor: 'red', /* modal close button background color*/
  }
});

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

  <DataTable :table-data-one="sendDataToTable" :create-data-one="updatedUserInfo" />
  <CreateModal v-if="isCMOpen" @close="closeCreateModal" :modal-config-data="sendConfigDataToCM">
    <CreateModalSlotData @closeCModal="closeCreateModal" @newAddedData="addedNewPermission" @updatedUserData="updatedUser"
      :permission-data="sendPermissionDataToCreateForm" />
  </CreateModal>
</template>
