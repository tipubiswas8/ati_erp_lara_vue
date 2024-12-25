<template>
  <ADescriptions bordered :column="{ xs: 2, sm: 2, md: 2, lg: 2 }">
    <ADescriptionsItem label="Username">{{ props.selectedUserInformation?.user_name }}</ADescriptionsItem>
    <ADescriptionsItem label="Employee Name">{{ props.selectedUserInformation?.en_full_name }}</ADescriptionsItem>
    <ADescriptionsItem label="Emoji">{{ props.selectedUserInformation?.emoji }}</ADescriptionsItem>
    <ADescriptionsItem label="Biometric ID">{{ props.selectedUserInformation?.biometicid }}</ADescriptionsItem>
    <ADescriptionsItem label="Role">
      <span v-if="isLoadingRole">Loading...</span>
      <span v-else>{{ selectedRoleName }}</span>
    </ADescriptionsItem>
    <ADescriptionsItem label="Official Email">{{ props.selectedUserInformation?.ofie_email }}</ADescriptionsItem>
    <ADescriptionsItem label="User Status">
      <a-switch :checked="status" checked-children="Active" un-checked-children="Inactive" :disabled="true" />
    </ADescriptionsItem>
    <ADescriptionsItem label="Employee Status">
      <a-switch :checked="emp_status" checked-children="Active" un-checked-children="Inactive" :disabled="true" />
    </ADescriptionsItem>
  </ADescriptions>
</template>

<script lang="ts" setup>
import { defineProps, onMounted, ref, computed } from 'vue'
import axios from 'axios';

const props = defineProps({
  selectedUserInformation: Object
});

const isLoadingRole = ref<boolean>(false);
const roleOptions = ref<{ value: number; label: string }[]>([]);
const rolesUrl = props.selectedUserInformation?.role_get_url;

const allRoles = async function fetchAllRoles() {
  try {
    if (!rolesUrl) return;
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

// Computed property to get the selected role name
const selectedRoleName = computed(() => {
  const role = roleOptions.value.find(
    (option) => option.value === props.selectedUserInformation?.role_id[0]
  );
  return role ? role.label : 'Unknown Role';
});

// Set the initial error message when the component is mounted
onMounted(() => {
  allRoles();
});

const activeStatuses = [1, "1", "Y", "Yes", "A", "Active", "True", "true"];

// Check if the user status is in the active statuses array
const status = activeStatuses.includes(props.selectedUserInformation?.status);

const emp_status = (props.selectedUserInformation?.emp_status === 1) ? true : false;
</script>
