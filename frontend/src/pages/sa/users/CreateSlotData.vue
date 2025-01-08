<template>
  <SuccessNotification v-if="success" :message="successMessage.message" />
  <AForm :model="formState" :rules="rules" v-bind="layout" name="nest-messages" :validate-messages="validateMessages"
    ref="formRef" @finish="onFinish">

    <a-row>
      <a-col :span="12">
        <AFormItem :name="['user', 'emp_id']" label="Employee"
          :rules="[{ required: true, message: 'Please select an employee!' }]">
          <a-select v-model:value="formState.user.emp_id" show-search placeholder="Please Select an employee"
            :options="employeeOptions" @change="handleEmployeeChange" :loading="isLoadingEmp"></a-select>
          <a-label :style="{ color: '#780650' }">{{ fieldErrors.employee_name }}</a-label>
        </AFormItem>

        <AFormItem :name="['user', 'role_id']" label="Role" :rules="[{ required: true }]">
          <a-select v-model:value="formState.user.role_id" mode="multiple" placeholder="Please select roles"
            :options="roleOptions" @change="handleRoleChange" :loading="isLoadingRole"></a-select>
          <a-label :style="{ color: '#780650' }">{{ fieldErrors.roles }}</a-label>
        </AFormItem>

        <AFormItem :name="['user', 'user_name']" label="Username" :rules="[{ required: true }]">
          <AInput v-model:value="formState.user.user_name" autocomplete="off" />
          <a-label :style="{ color: '#780650' }">{{ fieldErrors.user_name }}</a-label>
        </AFormItem>

        <a-form-item has-feedback label="Password" name="password">
          <a-input v-model:value="formState.password" type="password" autocomplete="off" />
          <a-label :style="{ color: '#780650' }">{{ fieldErrors.password }}</a-label>
        </a-form-item>

        <a-form-item has-feedback label="Confirm Password" name="checkPass">
          <a-input v-model:value="formState.checkPass" type="password" autocomplete="off" />
          <a-label :style="{ color: '#780650' }">{{ fieldErrors.checkPass }}</a-label>
        </a-form-item>

        <a-form-item :name="['user', 'status']" label="Active Status">
          <a-switch v-model:checked="formState.user.status" />
          <a-label :style="{ color: '#780650' }">{{ fieldErrors.status }}</a-label>
        </a-form-item>
      </a-col>
      <a-col :span="12">
        <AFormItem :name="['user', 'email']" label="Email">
          <AInput v-model:value="formState.user.email" disabled />
          <a-label :style="{ color: '#780650' }">{{ fieldErrors.email }}</a-label>
        </AFormItem>

        <AFormItem :name="['user', 'phone']" label="Phone">
          <AInput type="number" v-model:value="formState.user.phone"
            style="pointer-events: none; background-color: #f5f5f5;" />
        </AFormItem>

        <AFormItem :name="['user', 'organization_name']" label="Organization">
          <AInput v-model:value="formState.user.organization_name" disabled />
          <a-label :style="{ color: '#780650' }">{{ fieldErrors.organization_name }}</a-label>
        </AFormItem>

        <AFormItem :name="['user', 'department_name']" label="Department">
          <AInput v-model:value="formState.user.department_name"
            style="pointer-events: none; background-color: #f5f5f5;" />
        </AFormItem>

        <AFormItem :name="['user', 'designation_name']" label="Designation">
          <AInput v-model:value="formState.user.designation_name"
            style="pointer-events: none; background-color: #f5f5f5;" />
        </AFormItem>
      </a-col>
    </a-row>

    <AFormItem :name="['user', 'org_id']" label="Organization Id" :style="{ display: 'none' }">
      <AInput v-model:value="formState.user.org_id" />
      <a-label :style="{ color: '#780650' }">{{ fieldErrors.organization_name }}</a-label>
    </AFormItem>

    <AFormItem :wrapper-col="{ ...layout.wrapperCol, offset: 8 }">
      <AButton type="primary" html-type="submit">Submit</AButton>
      <a-button style="margin-left: 10px; background-color: #dde32d;" @click="resetForm">Reset</a-button>
    </AFormItem>
  </AForm>
</template>

<script lang="ts" setup>
import { ref, reactive, onMounted } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'
import SuccessNotification from '../../../notifications/SuccessNotification.vue'

const employeeOptions = ref<EmployeeOption[]>([]);
const emp_full_name = ref<string | undefined>(employeeOptions.value[0]?.value); // Set default if available
const role_name = ref<string | undefined>(undefined);
const roleOptions = ref([]);
const success = ref(false);
const isLoadingEmp = ref(true);
const isLoadingRole = ref(true);
const formRef = ref();

const sendToUserPage = defineEmits(['newAddedUserData', 'updatedUserData', 'closeCM']);

const handleCancel = () => {
  sendToUserPage('closeCM');
}

const props = defineProps({
  userData: Object,
  success_message: String,
});

interface EmployeeOption {
  value: string; // ID of the employee
  label: string; // Name of the employee
  phone: string; // Phone number
  email: string; // Email address
  organization_id?: string; // Optional company ID
  organization_name?: string; // Optional company ID
  department_name?: string; // Optional company ID
  designation_name?: string; // Optional company ID
}

const formState = reactive({
  user: {
    emp_id: null, // Selected employee
    role_id: [], // Selected roles
    phone: '' as string | number,
    user_name: '' as string,
    password: '' as string,
    password_confirmation: '' as string,
    email: '' as string,
    org_id: '' as string | null,
    organization_name: '' as string | null,
    department_name: '' as string | null,
    designation_name: '' as string | null,
    status: true as boolean
  },
  password: '' as string,
  checkPass: '' as string,
})

// For field-specific errors
const fieldErrors = reactive({
  employee_name: '',
  user_name: '',
  roles: '',
  status: '',
  password: '',
  checkPass: '',
  email: '',
  organization_name: '',
})


const successMessage = reactive({
  message: props.success_message
})

const router = useRouter();

const layout = {
  labelCol: { span: 8 },
  wrapperCol: { span: 16 },
}

const validateMessages = {
  required: '${label} is required!'
};

const updatePassword = () => {
  formState.user.password = formState.password;
  formState.user.password_confirmation = formState.checkPass;
};

const validatePass = async (_rule: any, value: any) => {
  if (value === '') {
    return Promise.reject('Please input the password');
  } else if (value.length < 6) {
    return Promise.reject('Password must be at least 6 characters long');
  } else {
    if (formState.checkPass !== '') {
      formRef.value.validateFields('checkPass');
    }
    return Promise.resolve();
  }
};

const validatePass2 = async (_rule: any, value: any) => {
  if (value === '') {
    return Promise.reject('Please input the password again');
  } else if (value !== formState.password) {
    return Promise.reject("Two inputs don't match!");
  } else {
    return Promise.resolve();
  }
};

const rules = {
  password: [{ required: true, validator: validatePass, trigger: 'change' }],
  checkPass: [{ validator: validatePass2, trigger: 'change' }]
};

const resetForm = () => {
  formRef.value.resetFields();
  emp_full_name.value = undefined; // Reset employee selection
  role_name.value = undefined; // Reset role selection
};

const handleEmployeeChange = (eId: string) => {
  const selectedEmployee = employeeOptions.value.find((employee) => employee.value === eId);
  if (selectedEmployee) {
    formState.user.email = selectedEmployee.email;
    formState.user.user_name = selectedEmployee.email.split('@')[0]; // Extract user_name
    formState.user.phone = selectedEmployee.phone;
    formState.user.org_id = selectedEmployee.organization_id ?? '';
    formState.user.organization_name = selectedEmployee.organization_name ?? '';
    formState.user.department_name = selectedEmployee.department_name ?? '';
    formState.user.designation_name = selectedEmployee.designation_name ?? '';
    formRef.value.clearValidate(); // Clear after changes
  } else {
    formState.user.emp_id = null;
  }
}

const handleRoleChange = (rId: string[]) => {
  // console.log('Roles selected:', rId);
}

async function fetchAllEmployees() {
  try {
    const response = await axios.get(props.userData?.employee_get_url);
    if (response.status === 200) {
      employeeOptions.value = response.data.data.map((item: any) => ({
        value: item.EMPLOYEE_ID,
        label: item.EN_FULL_NAME,
        phone: item.OMOBILE_NO,
        email: item.OFIE_EMAIL,
        organization_id: item.ORG_ID,
        organization_name: item.ORG_NAME,
        department_name: item.DEPT_NAME,
        designation_name: item.DESIG_NAME
      }));
    }

  } catch (error) {
    console.log(error);
  } finally {
    isLoadingEmp.value = false;
  }
}

async function fetchAllRoles() {
  try {
    const response = await axios.get(props.userData?.role_get_url);
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

onMounted(() => {
  fetchAllRoles();
  fetchAllEmployees();
});

const onFinish = async () => {
  try {
    updatePassword();
    const response = await axios.post(props.userData?.create_url, formState.user);
    if (response.status === 201) {
      success.value = true
      successMessage.message = response.data.message
      sendToUserPage('newAddedUserData', response.data.data);
      setTimeout(function () {
        handleCancel();
      }, 0);
    } else if (response.status === 200) {
      success.value = true
      successMessage.message = response.data.message;
      sendToUserPage('updatedUserData', response.data.data);
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
            fieldErrors.roles = errors.role_id ? errors.role_id[0] : ''
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
  } finally {
    router.push({ name: 'users' })
  }
}
</script>