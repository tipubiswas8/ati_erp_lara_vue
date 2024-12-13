<template>
  <SuccessNotification v-if="success" :message="successMessage.message" />
  <AForm :model="formState" :rules="rules" v-bind="layout" name="nest-messages" :validate-messages="validateMessages"
    ref="formRef" @finish="onFinish">

    <a-row>
      <a-col :span="12">
        <AFormItem :name="['user', 'employee_id']" label="Employee"
          :rules="[{ required: true, message: 'Please select an employee!' }]">
          <a-select v-model:value="formState.user.employee_id" show-search placeholder="Please Select an employee"
            :options="employeeOptions" @change="handleEmployeeChange" :loading="isLoadingEmp"></a-select>
        </AFormItem>

        <AFormItem :name="['user', 'roles']" label="Role" :rules="[{ required: true }]">
          <a-select v-model:value="formState.user.roles" mode="multiple" placeholder="Please select roles"
            :options="roleOptions" @change="handleRoleChange" :loading="isLoadingRole"></a-select>
        </AFormItem>

        <AFormItem :name="['user', 'username']" label="Username" :rules="[{ required: true }]">
          <AInput v-model:value="formState.user.username" autocomplete="off" />
          <a-label :style="{ color: '#780650' }">{{ fieldErrors.username }}</a-label>
        </AFormItem>

        <a-form-item has-feedback label="Password" name="password">
          <a-input v-model:value="formState.password" type="password" autocomplete="off" />
        </a-form-item>

        <a-form-item has-feedback label="Confirm Password" name="checkPass">
          <a-input v-model:value="formState.checkPass" type="password" autocomplete="off" />
        </a-form-item>

        <a-form-item :name="['user', 'status']" label="Active Status">
          <a-switch v-model:checked="formState.user.status" />
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
          <a-label :style="{ color: '#780650' }">{{ fieldErrors.phone }}</a-label>
        </AFormItem>

        <AFormItem :name="['user', 'organization_name']" label="Organization">
          <AInput v-model:value="formState.user.organization_name" disabled />
          <a-label :style="{ color: '#780650' }">{{ fieldErrors.organization_name }}</a-label>
        </AFormItem>

        <AFormItem :name="['user', 'department_name']" label="Department">
          <AInput v-model:value="formState.user.department_name"
            style="pointer-events: none; background-color: #f5f5f5;" />
          <a-label :style="{ color: '#780650' }">{{ fieldErrors.department_name }}</a-label>
        </AFormItem>

        <AFormItem :name="['user', 'designation_name']" label="Designation">
          <AInput v-model:value="formState.user.designation_name"
            style="pointer-events: none; background-color: #f5f5f5;" />
          <a-label :style="{ color: '#780650' }">{{ fieldErrors.designation_name }}</a-label>
        </AFormItem>
      </a-col>
    </a-row>

    <AFormItem :name="['user', 'organization_id']" label="Organization Id" :style="{ display: 'none' }">
      <AInput v-model:value="formState.user.organization_id" />
      <a-label :style="{ color: '#780650' }">{{ fieldErrors.organization_id }}</a-label>
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

const isLoadingEmp = ref(true);
const isLoadingRole = ref(true);
const formRef = ref();
const resetForm = () => {
  formRef.value.resetFields();
  emp_full_name.value = undefined; // Reset employee selection
  role_name.value = undefined; // Reset role selection
};

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

const employeeOptions = ref<EmployeeOption[]>([]);
const emp_full_name = ref<string | undefined>(employeeOptions.value[0]?.value); // Set default if available
const role_name = ref<string | undefined>(undefined);
const roleOptions = ref([]);

const handleEmployeeChange = (eId: string) => {
  const selectedEmployee = employeeOptions.value.find((employee) => employee.value === eId);
  if (selectedEmployee) {
    formState.user.email = selectedEmployee.email;
    formState.user.username = selectedEmployee.email.split('@')[0]; // Extract username
    formState.user.phone = selectedEmployee.phone;
    formState.user.organization_id = selectedEmployee.organization_id ?? '';
    formState.user.organization_name = selectedEmployee.organization_name ?? '';
    formState.user.department_name = selectedEmployee.department_name ?? '';
    formState.user.designation_name = selectedEmployee.designation_name ?? '';
    formRef.value.clearValidate(); // Clear after changes
  } else {
    formState.user.employee_id = null;
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

const sendToUserPage = defineEmits(['formData'])

const formState = reactive({
  user: {
    employee_id: null, // Selected employee
    roles: [], // Selected roles
    phone: '' as string | number,
    username: '' as string,
    password: '' as string,
    password_confirmation: '' as string,
    email: '' as string,
    organization_id: '' as string | null,
    organization_name: '' as string | null,
    department_name: '' as string | null,
    designation_name: '' as string | null,
    status: '' as string | number
  },
  password: '' as string,
  checkPass: '' as string,
})

// For field-specific errors
const fieldErrors = reactive({
  email: '',
  phone: '',
  username: '',
  password: '',
  checkPass: '',
  organization_id: '',
  organization_name: '',
  department_name: '',
  designation_name: '',
})

const updatePassword = () => {
  formState.user.password = formState.password;
  formState.user.password_confirmation = formState.checkPass;
};

const layout = {
  labelCol: { span: 8 },
  wrapperCol: { span: 16 },
}

const validateMessages = {
  required: '${label} is required!'
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

const props = defineProps({
  success_message: String,
  userData: Object,
})

const success = ref(false);

const router = useRouter()
const successMessage = reactive({
  message: props.success_message
})

const onFinish = async () => {
  try {
    updatePassword();
    const response = await axios.post(props.userData?.create_url, formState.user);
    console.log(response);
    if (response.status === 201) {
      success.value = true
      successMessage.message = response.data.message
      // handleCancel()
    }
  } catch (error) {
    console.log(error);

    if (axios.isAxiosError(error)) {
      if (error.response) {
        // console.error('Error status:', error.response.status) // Get the status code
        // console.error('Error data:', error.response.data) // Get the error response data
        if (error.response.status === 400) {
          // console.error('Validation error:', error.response.data.message)
          const errors = error.response.data.message || {}
          // fieldErrors.name = errors.name ? errors.name[0] : ''
          // fieldErrors.email = errors.email ? errors.email[0] : ''
          // fieldErrors.phone = errors.phone ? errors.phone[0] : ''
          // fieldErrors.address = errors.address ? errors.address[0] : ''
          // fieldErrors.password = errors.password ? errors.password[0] : ''
          // Trigger form validation after setting errors
          Object.keys(fieldErrors).forEach((key) => {
            fieldErrors[key] = errors[key] ? errors[key][0] : ''
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
