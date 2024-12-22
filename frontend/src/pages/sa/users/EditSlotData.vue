<template>
  <AForm :model="formData" ref="formRef" :validate-messages="validateMessages" @finish="onFinish">
    <ARow>
      <ACol :span="12">
        <AFormItem :name="['user', 'emp_name']" label="Employee Name"
          :rules="disableValidation ? [] : [{ required: true }]">
          <AInput v-model:value="formData.user.emp_name" />
          <a-label :style="{ color: '#780650' }">{{ fieldErrors.emp_name }}</a-label>
        </AFormItem>

        <AFormItem :name="['user', 'official_mob']" label="Official Phone No" :rules="disableValidation ? [] : [
          { required: true },
          {
            validator: validatePersonalPhone,
            message: 'Personal Phone Number must be exactly 11 digits!',
          }
        ]">
          <AInput v-model:value="formData.user.official_mob" type="number" />
          <a-label :style="{ color: '#780650' }">{{ fieldErrors.official_mob }}</a-label>
        </AFormItem>

        <AFormItem :name="['user', 'official_email']" label="Official Email"
          :rules="disableValidation ? [] : [{ required: true, type: 'email' }]">
          <AInput v-model:value="formData.user.official_email" />
          <a-label :style="{ color: '#780650' }">{{ fieldErrors.official_email }}</a-label>
        </AFormItem>

        <a-form-item has-feedback label="Old Password" name="old_password">
          <a-input v-model:value="formData.old_password" type="password" autocomplete="off" />
          <a-label :style="{ color: '#780650' }">{{ fieldErrors.old_password }}</a-label>
        </a-form-item>

        <AFormItem :name="['user', 'role_name']" label="Role" :rules="disableValidation ? [] : [{ required: true }]">
          <a-select v-model:value="formData.user.role_name" mode="multiple" placeholder="Please select roles"
            :options="roleOptions" :loading="isLoadingRole"></a-select>
          <a-label :style="{ color: '#780650' }">{{ fieldErrors.role_name }}</a-label>
        </AFormItem>

        <a-form-item :name="['user', 'status']" label="Active Status">
          <a-switch v-model:checked="formData.user.status" />
          <a-label :style="{ color: '#780650' }">{{ fieldErrors.status }}</a-label>
        </a-form-item>

      </ACol>

      <ACol :span="12">
        <AFormItem :name="['user', 'user_name']" label="Username"
          :rules="disableValidation ? [] : [{ required: true }]">
          <AInput v-model:value="formData.user.user_name" />
          <a-label :style="{ color: '#780650' }">{{ fieldErrors.user_name }}</a-label>
        </AFormItem>

        <AFormItem :name="['user', 'personal_mob']" label="Personal Phone No">
          <AInput v-model:value="formData.user.personal_mob" type="number" @keypress="handleKeyPress"
            @input="onInput" />
          <a-label :style="{ color: '#780650' }">{{ fieldErrors.personal_mob }}</a-label>
        </AFormItem>

        <AFormItem :name="['user', 'personal_email']" label="Personal Email"
          :rules="disableValidation ? [] : [{ type: 'email' }]">
          <AInput v-model:value="formData.user.personal_email" />
          <a-label :style="{ color: '#780650' }">{{ fieldErrors.personal_email }}</a-label>
        </AFormItem>

        <a-form-item has-feedback label="New Password" name="new_password"
          :rules="disableValidation ? [] : [{ validator: validatePass }]">
          <a-input v-model:value="formData.new_password" type="password" autocomplete="off" />
          <a-label :style="{ color: '#780650' }">{{ fieldErrors.new_password }}</a-label>
        </a-form-item>

        <a-form-item has-feedback label="Confirm Password" name="checkPass"
          :rules="disableValidation ? [] : [{ validator: validatePass2 }]">
          <a-input v-model:value="formData.checkPass" type="password" autocomplete="off" />
        </a-form-item>
      </ACol>
      <AInput type="hidden" v-model:value="formData.user.id" />
    </ARow>

    <ARow>
      <ACol :span="9">
      </ACol>
      <ACol :span="3">
        <AFormItem>
          <a-button style="background-color: #dde32d;" @click="resetForm">Reset</a-button>
        </AFormItem>
      </ACol>
      <ACol :span="3">
        <AFormItem>
          <AButton type="primary" html-type="submit">Update</AButton>
        </AFormItem>
      </ACol>
      <ACol :span="9">
      </ACol>
    </ARow>
  </AForm>
</template>

<script lang="ts" setup>
import { reactive, ref, watch, onMounted, nextTick } from 'vue'
import axios from 'axios'

const formRef = ref();
const isLoadingRole = ref<boolean>(false);
const roleOptions = ref([]);
const disableValidation = ref(false);

const props = defineProps({
  editData: {
    type: Object,
  },
  odfe: Object,
  myData: Object
});

console.log(props.myData);
const emit = defineEmits(['closeEM', 'responseData', 'notificationInfo']);

const handleCancel = () => {
  emit('closeEM') // Emit event to close edit modal
}

const resetForm = () => {
  formRef.value.resetFields();
};

const validateMessages = {
  required: '${label} is required!',
  types: {
    email: '${label} is not a valid email!',
  },
};

// Custom validator function
const validatePersonalPhone = (_rule: any, value: any) => {
  return new Promise<void>((resolve, reject) => {
    if (!value || value.toString().length !== 11) {
      reject('Personal Phone Number must be exactly 11 digits');
    } else {
      resolve();
    }
    // If error is rejected, show message for 3 seconds and then clear it
    setTimeout(() => {
      resolve(); // Clear the error message after 3 seconds
    }, 3000);
  });
};

// Handle input to restrict the length to 11 digits
const onInput = (e: Event) => {
  const input = e.target as HTMLInputElement;
  const value = input.value.replace(/\D/g, ''); // Remove non-numeric characters
  if (value.length > 11) {
    input.value = value.slice(0, 11); // Truncate to 11 digits
  }
  formData.user.personal_mob = input.value; // Sync value with formData
};

// Function to block invalid keypresses
const handleKeyPress = (e: KeyboardEvent) => {
  const char = String.fromCharCode(e.which || e.keyCode);
  if (!/^\d$/.test(char)) {
    e.preventDefault(); // Block non-numeric keys
  }
};

const formData = reactive({
  user: {
    id: props.editData?.id || 0,
    user_id: props.editData?.id || 0,
    emp_id: props.editData?.employee_id || 0,
    user_name: props.editData?.user_name || '',
    emp_name: props.editData?.name || '',
    role_name: props.editData?.role_id || [],
    official_email: props.editData?.ofie_email || '',
    personal_email: props.editData?.ppo_hemail || '',
    official_mob: props.editData?.omobile_no || '',
    personal_mob: props.editData?.pmobile_no || '',
    status: props.editData?.status === 1,
  },
  new_password: null,
  old_password: null,
  checkPass: null
});

// For field-specific errors
const fieldErrors = reactive({
  emp_name: '',
  user_name: '',
  role_name: '',
  status: '',
  old_password: '',
  new_password: '',
  official_email: '',
  personal_email: '',
  official_mob: '',
  personal_mob: '',
});

const validatePass = async (_rule: any, value: any) => {
  if (value !== null) {
    if (value === '') {
      return Promise.reject('Please input the password');
    } else if (value.length < 6) {
      return Promise.reject('Password must be at least 6 characters long');
    } else {
      if (formData.checkPass !== '') {
        formRef.value.validateFields('checkPass');
      }
      return Promise.resolve();
    }
  }
};

const validatePass2 = async (_rule: any, value: any) => {
  if (value === '') {
    return Promise.reject('Please input the password again');
  } else if (value !== formData.new_password) {
    return Promise.reject("Two inputs don't match!");
  } else {
    return Promise.resolve();
  }
};

// Watcher for the `personal_mob` field to validate length
watch(() => formData.user.personal_mob, (newValue) => {
  if (newValue.length < 11) {
    fieldErrors.personal_mob = 'Phone number must be exactly 11 digits';
  } else if (newValue.length === 11) {
    fieldErrors.personal_mob = ''; // Clear error if length is valid
  }
});

// Set the initial error message when the component is mounted
onMounted(() => {
  if (formData.user.personal_mob.length < 11) {
    fieldErrors.personal_mob = 'Phone number must be exactly 11 digits';
  } else if (formData.user.personal_mob.length === 11) {
    fieldErrors.personal_mob = ''; // Clear error if length is valid
  }
  nextTick(() => {
    if (formData.user.personal_mob.length < 11) {
      fieldErrors.personal_mob = 'Phone number must be exactly 11 digits';
    } else if (formData.user.personal_mob.length === 11) {
      fieldErrors.personal_mob = ''; // Clear error if length is valid
    }

    // Trigger the custom validator for personal_mob initially
    formRef.value?.validateFields()
      .then(() => {
        // console.log('Validation passed for initial value');
      })
      .catch((error) => {
        // console.log('Validation failed for initial value', error);
      });
  });
  allRoles();
});

const rolesUrl = props.odfe?.role_get_url;
const allRoles = async function fetchAllRoles() {
  try {
    const response = await axios.get(rolesUrl);
    if (response.status === 200) {
      roleOptions.value = response.data.data.map((item: any) => ({
        value: item.id,
        label: item.role_name,
      }));
    }
  } catch (error) {
    console.log(error);
  } finally {
    isLoadingRole.value = false;
  }
}

const edit_url = props.editData?.edit_url;
const onFinish = async () => {
  try {
    Object.assign(formData.user, {
      new_password: formData.new_password,
      old_password: formData.old_password,
      confirm_password: formData.checkPass,
    });

    const response = await axios.put(edit_url, formData.user);
    if (response.status === 204 || response.status === 200) {
      const responseStatusAndMessage = {
        success: response.data.status,
        message: response.data.message
      }
      // send response data to data table
      const successResponseData = [];
      successResponseData.push({
        ...response.data.data,
        id: response.data.data.user_id,
        name: response.data.data.en_full_name,
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
        if (error.response.status === 400) {
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
