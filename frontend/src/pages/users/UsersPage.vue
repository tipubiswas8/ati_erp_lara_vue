<script setup lang="ts">
import DataTable from '../../tables/DataTable.vue'
import CreateModal from '../../modals/CreateModal.vue'
import { ref, reactive } from 'vue'

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
  console.log(newUser);

}

// script for create modal


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

const sendPropData = reactive({
  config: {
    title: 'Create Modal', /* modal title */
    // titleBgColor: '#82c953', /* modal title */
    // titleTextColor: 'white', /* title text color*/
    // height: 30, /* modal height */
    // width: 70, /* modal width*/
    // footer: true, /* modal footer*/
    // footerButtonBgColor: 'red', /* modal close button background color*/
  }
})

let clickOnFinished = {};
const finish = () => {
  clickOnFinished = formState;
  console.log(clickOnFinished);
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
  <CreateModal v-if="isOpen" @close="closeModal" @userAdded="addNewUser" :user-data="clickOnFinished"
    :user-modal-data="sendPropData">
    <AForm :model="formState" v-bind="layout" name="nest-messages" :validate-messages="validateMessages"
      @finish="finish">
      <AFormItem :name="['user', 'name']" label="Name" :rules="[{ required: true }]">
        <AInput v-model:value="formState.user.name" autocomplete="off" />
        <a-label :style="{ color: '#780650' }">{{ fieldErrors.name }}</a-label>
      </AFormItem>

      <AFormItem :name="['user', 'email']" label="Email" :rules="[{ type: 'email' }]">
        <AInput v-model:value="formState.user.email" />
        <a-label :style="{ color: '#780650' }">{{ fieldErrors.email }}</a-label>
      </AFormItem>

      <AFormItem :name="['user', 'phone']" label="Phone" :rules="[{ validator: validatePhone }]">
        <AInput type="number" v-model:value="formState.user.phone" @keydown="blockNonNumericKeys"
          @input="castToNumber" />
        <a-label :style="{ color: '#780650' }">{{ fieldErrors.phone }}</a-label>
      </AFormItem>

      <AFormItem :name="['user', 'address']" label="Address">
        <AInput v-model:value="formState.user.address" />
        <a-label :style="{ color: '#780650' }">{{ fieldErrors.address }}</a-label>
      </AFormItem>

      <AFormItem :name="['user', 'password']" label="Password">
        <AInput v-model:value="formState.user.password" />
        <a-label :style="{ color: '#780650' }">{{ fieldErrors.password }}</a-label>
      </AFormItem>

      <AFormItem :wrapper-col="{ ...layout.wrapperCol, offset: 8 }">
        <AButton type="primary" html-type="submit">Submit</AButton>
      </AFormItem>
    </AForm>
  </CreateModal>
</template>
