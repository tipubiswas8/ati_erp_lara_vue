<script setup lang="ts">
import DataTable from '@/tables/BasicDataTable.vue'
import CreateModal from '@/modals/CreateModal.vue'
import CreateModalSlotData from '@/pages/sa/permission/CreateSlotData.vue'
import EditModal from '@/modals/EditModal.vue'
import EditSlot from '@/pages/sa/permission/EditSlotData.vue'
import ViewModal from '@/modals/ViewModal.vue'
import ViewModalSlot from '@/pages/sa/permission/ViewSlotData.vue'
import { ref, reactive } from 'vue'

// for data table
// ==============
const sendDataToTable = {
  // required
  urls: {
    'data_url': 'security-access/permissions',
    'edit_url': 'security-access/permissions',
    'status_url': 'security-access/permission-status',
    'delete_url': 'security-access/permissions'
  },
  custom_name: 'permission_name',
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
const showCreateModal = () => {
  isCMOpen.value = true
};

const closeCreateModal = () => {
  isCMOpen.value = false
};

// Push the newly added permission to the data array
let newCreatedData: object;
// Push the newly added permission to the data array
const addedNewPermission = (newPermission: object) => {
  newCreatedData = newPermission;
};

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

// for edit
const openEditModal = ref(false);
const doNowOpenEditModal = () => {
  openEditModal.value = true
}

const editModalConfigData = {
  config: {
    title: 'Update Permission', /* modal title */
    titleBgColor: '#82c953', /* modal title */
    titleTextColor: 'white', /* title text color*/
    // height: 45, /* modal height */
    // width: 60, /* modal width*/
    footer: true, /* modal footer*/
    footerButtonBgColor: 'red', /* modal close button background color*/
  }
};

let selectedPermissionForUpdate = {};
const permissionDataForUpdate = (permissionData: object) => {
  selectedPermissionForUpdate = {
    ...permissionData,
    edit_url: 'security-access/permissions',
    org_get_url: 'hr/organizations'
  }
}

let updatedResponseData = {};
const responseEditData = (eRData: object) => {
  updatedResponseData = eRData;
}

const nInfo = ref<object>({});
const notify = (notic: object) => {
  nInfo.value = notic;
  setTimeout(() => {
    nInfo.value = {};
  }, 0);
}

const closeEditModal = () => {
  openEditModal.value = false;
}

// for view modal
// ==============
const isVMOpen = ref(false)

const doViewModalOpen = () => {
  isVMOpen.value = true
};

const doViewModalClose = () => {
  isVMOpen.value = false
};

const emitDataForView = () => {
  doViewModalOpen();
}

const viewModalConfigData = {
  config: {
    title: 'Show Permission', /* modal title */
    titleBgColor: '#82c953', /* modal title */
    titleTextColor: 'white', /* title text color*/
    // height: 45, /* modal height */
    // width: 60, /* modal width*/
    footer: true, /* modal footer*/
    footerButtonBgColor: 'red', /* modal close button background color*/
  }
};

const selectedPermissionForView = ref({});
const permissionDataForView = (permissionData: object) => {
  selectedPermissionForView.value = {
    ...permissionData,
  }
}

// for status
const statusChangeData = {
  statusColumnName: 'status',
  statusChangeFor: 'name',
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

  <DataTable :table-data-one="sendDataToTable" :status-data-one="statusChangeData" :create-data-one="newCreatedData"
    @isEditModalOpen="doNowOpenEditModal" :update-data-one="updatedResponseData" @isViewModalOpen="emitDataForView"
    @dataForUpdate="permissionDataForUpdate" @dataForView="permissionDataForView" />
  <CreateModal v-if="isCMOpen" @close="closeCreateModal" :modal-config-data="sendConfigDataToCM">
    <CreateModalSlotData @closeCModal="closeCreateModal" @newAddedData="addedNewPermission"
      :permission-data="sendPermissionDataToCreateForm" />
  </CreateModal>
  <EditModal v-if="openEditModal" :config-data="editModalConfigData" @close="closeEditModal" :notify-data="nInfo">
    <EditSlot :permission-data="selectedPermissionForUpdate" @responseData="responseEditData" @notificationInfo="notify"
      @closeEM="closeEditModal" />
  </EditModal>
  <ViewModal v-if="isVMOpen" @closeVm="doViewModalClose" :view-modal-config-data="viewModalConfigData">
    <ViewModalSlot :show-permission="selectedPermissionForView" />
  </ViewModal>
</template>
