<script setup lang="ts">
import DataTable from '@src/tables/DataTable.vue'
import CreateModal from '@src/modals/CreateModal.vue'
import ViewModal from '@src/modals/ViewModal.vue'
import CreateModalSlotData from '@src/pages/sa/users/CreateSloatData.vue'
import ViewModalSlotData from '@src/pages/sa/users/ViewSloatData.vue'
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
const isCMOpen = ref(false)

const showCreateModal = () => {
  isCMOpen.value = true
};

const closeCreateModal = () => {
  isCMOpen.value = false
};

let clickOnFinished = {};
const finish = () => {
  clickOnFinished = formState;
  console.log(clickOnFinished);
}

// Push the newly added user to the data array
const addNewUser = (newUser: object) => {
  const sl = data.value.length + 1 // Generate new serial number for the user
  data.value.push({
    ...newUser,
    sl
  })
}

const formState = reactive({
  user: {
    name: '' as string,
    email: '' as string,
    phone: '' as string | number,
    address: '' as string,
    password: '' as string,
  },
})
// For field-specific errors
const fieldErrors = reactive({
  name: '',
  email: '',
  phone: '',
  address: '',
  password: ''
})

const layout = {
  labelCol: { span: 8 },
  wrapperCol: { span: 16 },
}

const validateMessages = {
  required: '${label} is required!',
  types: {
    email: '${label} is not a valid email!',
    number: '${label} is not a valid number!',
  }
};

const sendPropDataForCm = reactive({
  config: {
    title: 'Create Modal', /* modal title */
    // titleBgColor: '#82c953', /* modal title */
    // titleTextColor: 'white', /* title text color*/
    // height: 30, /* modal height */
    // width: 70, /* modal width*/
    // footer: true, /* modal footer*/
    // footerButtonBgColor: 'red', /* modal close button background color*/
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
    height: 52, /* modal height */
    width: 40, /* modal width*/
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
      <a-input v-model:value="value" placeholder="Search here" />
    </a-col>
  </a-row>

  <DataTable :request-data="sendDataToTable" @isViewModalOpen="emitDataForView" @userDataForView="userDataForViewModal" />
  <CreateModal v-if="isCMOpen" @close="closeCreateModal" @userAdded="addNewUser" :user-data="clickOnFinished"
    :user-create-modal-data="sendPropDataForCm">
    <CreateModalSlotData />
  </CreateModal>
  <ViewModal v-if="isVMOpen" @closeVm="closeViewModal" :user-view-modal-data="sendPropDataForVm">
    <ViewModalSlotData :selected-user-information="userInformation"/>
  </ViewModal>
</template>
