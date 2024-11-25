<template>
  <ADescriptions bordered :column="{ xs: 1, sm: 1, md: 1, lg: 1 }">
    <ADescriptionsItem label="Username">{{ props.selectedUserInformation?.user_name }}</ADescriptionsItem>
    <ADescriptionsItem label="Employee Name">{{ props.selectedUserInformation?.efull_name }}</ADescriptionsItem>
    <ADescriptionsItem label="Official Email">{{ props.selectedUserInformation?.ofie_email }}</ADescriptionsItem>
    <ADescriptionsItem label="Official Mobile">{{ props.selectedUserInformation?.omobile_no }}</ADescriptionsItem>
    <ADescriptionsItem label="Biometric ID">{{ props.selectedUserInformation?.biometicid }}</ADescriptionsItem>
    <ADescriptionsItem label="Active Status">
      <a-switch :checked="status" checked-children="Active" un-checked-children="Inactive" :disabled="true"/>
    </ADescriptionsItem>
  </ADescriptions>
</template>

<script lang="ts" setup>
import { defineProps } from 'vue'

const props = defineProps({
  selectedUserInformation: Object
})
const status = (props.selectedUserInformation?.status === 1) ? true : false;
</script>
