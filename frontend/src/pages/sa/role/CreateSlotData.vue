<template>
  <SuccessNotification v-if="success" :message="successMessage.message" />
  <AForm :model="formState" ref="formRef" @finish="onFinish">
    <a-row>
      <a-col :span="24">
        <AFormItem name="role_name" label="Role Name" :rules="[{ required: true, message: 'Role name is required' }]">
          <AInput v-model:value="formState.role_name" autocomplete="off" />
          <a-label :style="{ color: '#780650' }">{{ fieldErrors.role_name }}</a-label>
        </AFormItem>

        <a-radio-group v-model:value="formState.org_for" style=" margin-bottom: 1vh;">
          <a-radio value="all">For All Organization</a-radio>
          <a-radio value="active">For Active Organization Only</a-radio>
          <a-radio value="specific" checked style=" margin-top: 3vh;">For Specific Organization</a-radio>
        </a-radio-group>
        <AFormItem v-if="formState.org_for === 'specific'" name="org_id" label="Organizations" :rules="[{ required: true, message: 'Please select one or more organization' }]">
          <a-select v-model:value="formState.org_id" mode="multiple" placeholder="Please select organizations"
            :options="orgOptions" :loading="isLoadingOrg"></a-select>
          <a-label :style="{ color: '#780650' }">{{ fieldErrors.orgs }}</a-label>
        </AFormItem>
        <a-form-item name="status" label="Active Status">
          <a-switch v-model:checked="formState.status" />
        </a-form-item>
      </a-col>
      <a-col :span="8"></a-col>
      <a-col :span="8">
        <AFormItem>
          <AButton type="primary" html-type="submit">Submit</AButton>
          <a-button style="margin-left: 10px; background-color: #dde32d;" @click="resetForm">Reset</a-button>
        </AFormItem>
      </a-col>
      <a-col :span="8"></a-col>
    </a-row>
  </AForm>
</template>

<script lang="ts" setup>
import { ref, reactive, onMounted, watch } from 'vue'
import axios from 'axios'
import SuccessNotification from '../../../notifications/SuccessNotification.vue'

const success = ref(false);
const formRef = ref();
const orgOptions = ref([]);
const isLoadingOrg = ref(true);

const formState = reactive({
  role_name: '' as string,
  org_id: [],
  org_for: 'specific',
  status: true as boolean,
});

// For field-specific errors
const fieldErrors = reactive({
  role_name: '',
  orgs: ''
});

const resetForm = () => {
  formRef.value.resetFields();
  formState.org_for = 'specific';
  formState.org_id = [];
};

const sendToDatatable = defineEmits(['newAddedData', 'closeCreateModal']);

const props = defineProps({
  success_message: String,
  dataForCreate: Object,
})

const successMessage = reactive({
  message: props.success_message
})

const handleCancel = () => {
  sendToDatatable('closeCreateModal');
}

const org_url = props.dataForCreate?.urls.org_get_url;
async function fetchAllRoles() {
  try {
    const response = await axios.get(org_url);
    if (response.status === 200) {
      orgOptions.value = response.data.data.map((item: any) => ({
        value: item.ORG_ID,
        label: item.ORG_NAME,
      }));
    }
  } catch (error) {
    console.log(error);
  } finally {
    isLoadingOrg.value = false;
  }
}

onMounted(() => {
  fetchAllRoles();
});

const onFinish = async () => {
  try {
    const response = await axios.post(props.dataForCreate?.urls.create_url, formState);
    if (response.status === 201 || response.status === 200) {
      success.value = true
      successMessage.message = response.data.message
      sendToDatatable('newAddedData', response.data.data);
      setTimeout(function () {
        handleCancel();
      }, 0);
    }
  } catch (error) {
    if (axios.isAxiosError(error)) {
      if (error.response) {
        // console.error('Error status:', error.response.status) // Get the status code
        // console.error('Error message:', error.response.message) // Get the error response message
        // console.error('Error data:', error.response.data) // Get the error response data
        if (error.response.status === 400) {
          // console.error('Validation error:', error.response.data.validation_errors)
          const errors = error.response.data.validation_errors || {}
          // fieldErrors.employee_name = errors.emp_id ? errors.emp_id[0] : ''
          // fieldErrors.roles = errors.role_id ? errors.role_id[0] : ''
          // fieldErrors.user_name = errors.user_name ? errors.user_name[0] : ''
          // fieldErrors.email = errors.email ? errors.email[0] : ''
          // fieldErrors.password = errors.password ? errors.password[0] : ''
          // fieldErrors.status = errors.status ? errors.status[0] : ''
          // fieldErrors.organization_name = errors.org_id ? errors.org_id[0] : ''
          // Trigger form validation after setting errors
          Object.keys(fieldErrors).forEach((key) => {
            fieldErrors[key] = errors[key] ? errors[key][0] : ''
            fieldErrors.employee_name = errors.emp_id ? errors.emp_id[0] : ''
            fieldErrors.organization_name = errors.org_id ? errors.org_id[0] : ''
          })
        }
      } else if (error.request) {
        console.error('No response received:', error.request)
      } else {
        console.error('Error:', error.message)
      }
    }
    else {
      console.error('Unexpected error:', error);
    }
  }
}
</script>