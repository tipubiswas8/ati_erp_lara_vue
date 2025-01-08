<template>
  <AForm :model="formData" ref="formRef" @finish="onFinish">
    <ARow :style="{ flexDirection: 'column' }">
      <AFormItem name="role_name" label="Role Name" :rules="[{ required: true, message: 'Role name is required' }]">
        <AInput v-model:value="formData.role_name" autocomplete="off" />
        <a-label :style="{ color: '#780650' }">{{ fieldErrors.role_name }}</a-label>
      </AFormItem>

      <AFormItem name="org_name" label="Organization"
        :rules="disableValidation ? [] : [{ required: true, message: 'Organization is required' }]">
        <a-select v-model:value="formData.org_name" placeholder="Please select organization" :options="orgOptions"
          :loading="isLoadingOrg"></a-select>
        <a-label :style="{ color: '#780650' }">{{ fieldErrors.org_name }}</a-label>
      </AFormItem>

      <a-form-item name="status" label="Active Status">
        <a-switch v-model:checked="formData.status" />
      </a-form-item>
    </ARow>

    <ARow :style="{ display: 'flex', alignItems: 'center', justifyContent: 'center', gap: '8px' }">
      <AFormItem>
        <a-button style="background-color: #dde32d;" @click="resetForm">Reset</a-button>
      </AFormItem>
      <AFormItem>
        <AButton type="primary" html-type="submit">Update</AButton>
      </AFormItem>
    </ARow>
  </AForm>
</template>

<script lang="ts" setup>
import { reactive, ref, onMounted } from 'vue'
import axios from 'axios'

const formRef = ref();
const isLoadingOrg = ref<boolean>(false);
const orgOptions = ref([]);
const disableValidation = ref(false);

const props = defineProps({
  editDataOne: {
    type: Object,
  },
  editDataTwo: Object
});
const emit = defineEmits(['closeEM', 'responseData', 'notificationInfo']);

const handleCancel = () => {
  emit('closeEM') // Emit event to close edit modal
}

const resetForm = () => {
  formRef.value.resetFields();
};

const formData = reactive({
  role_id: props.editDataOne?.id || 0,
  role_name: props.editDataOne?.name || '',
  org_name: props.editDataOne?.org_id || '',
  status: props.editDataOne?.status === 1,
});

// For field-specific errors
const fieldErrors = reactive({
  role_name: '',
  org_name: ''
});

// Set the initial error message when the component is mounted
onMounted(() => {
  allOrg();
});

const orgsUrl = props.editDataTwo?.org_get_url;
const allOrg = async function fetchAllOrganizations() {
  try {
    const response = await axios.get(orgsUrl);
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

const edit_url = props.editDataOne?.edit_url + '/' + formData.role_id;
const onFinish = async () => {
  try {
    const response = await axios.patch(edit_url, formData);
    if (response.status === 204 || response.status === 200) {
      const responseStatusAndMessage = {
        success: response.data.status,
        message: response.data.message
      }
      // send response data to data table
      const successResponseData = [];
      successResponseData.push({
        ...response.data.data,
        id: response.data.data.id,
        name: response.data.data.role_name,
      });
      emit('responseData', successResponseData[0]);
      emit('notificationInfo', responseStatusAndMessage);
      setTimeout(() => {
        handleCancel();
      }, 0);
    }
  } catch (error) {
    if (axios.isAxiosError(error)) {
      if (error.response) {
        // console.error('Error status:', error.response.status) // Get the status code
        // console.error('Error message:', error.response.message) // Get the error response message
        // console.error('Error data:', error.response.data) // Get the error response data
        if (error.response.status === 400 || error.response.status === 422) {
          // console.error('Validation error:', error.response.data.validation_errors)
          const errors = error.response.data.validation_errors || {}
          Object.keys(fieldErrors).forEach((key) => {
            fieldErrors[key] = errors[key] ? errors[key][0] : ''
          });
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
  } finally {
  }
}  
</script>
