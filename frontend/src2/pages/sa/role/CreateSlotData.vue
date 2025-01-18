<template>
  <SuccessNotification v-if="successNotify" :message="successMessage.message" />
  <ErrorNotification v-if="errorNotify" :message="errorMessage" />
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

        <AFormItem v-if="formState.org_for === 'specific'" name="org_id" label="Organizations"
          :rules="[{ required: true, message: 'Please select one or more organization' }]">
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
import { ref, reactive, onMounted } from 'vue'
import axios from 'axios'
import SuccessNotification from '../../../notifications/SuccessNotification.vue'
import ErrorNotification from '../../../notifications/ErrorNotification.vue'

const props = defineProps({
  createDataOne: Object,
});
const sendToDatatable = defineEmits(['newAddedData', 'closeCreateModal']);

const successNotify = ref(false);
const errorNotify = ref(false);
const formRef = ref();
const orgOptions = ref([]);
const isLoadingOrg = ref(true);

const resetForm = () => {
  formRef.value.resetFields();
  formState.org_for = 'specific';
  formState.org_id = [];
};

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

const successMessage = reactive({
  message: ''
});

const errorMessage = ref('');

const handleCancel = () => {
  sendToDatatable('closeCreateModal');
}

const org_url = props.createDataOne?.urls.org_get_url;
async function fetchAllOrganization() {
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
  fetchAllOrganization();
});

const onFinish = async () => {
  try {
    const response = await axios.post(props.createDataOne?.urls.create_url, formState);
    if (response.status === 201 || response.status === 200) {
      successNotify.value = true;
      successMessage.message = response.data.message;
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
        if (error.response.status === 400 || error.response.status === 422) {
          // console.error('Validation error:', error.response.data.validation_errors)
          const errors = error.response.data.validation_errors || {}
          fieldErrors.role_name = errors.name ? errors.name[0] : ''
          fieldErrors.orgs = errors.org_for ? errors.org_for[0] : ''
        } else if (error.response.status === 409) {
          errorNotify.value = true
          errorMessage.value = error.response.data.message
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