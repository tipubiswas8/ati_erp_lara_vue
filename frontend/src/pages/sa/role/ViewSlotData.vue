<template>
  <ADescriptions>
    <ADescriptionsItem style="display: block;" label="Role Name">{{ props.viewDataOne?.name }}
    </ADescriptionsItem>
    <ADescriptionsItem style="display: block;" label="Organization Name">{{ props.viewDataOne?.org_name
      }}
    </ADescriptionsItem>
    <ADescriptionsItem style="display: block;" label="Role Status">
      <a-switch :checked="status" checked-children="Active" un-checked-children="Inactive" :disabled="true" />
    </ADescriptionsItem>
  </ADescriptions>
</template>

<script setup>
const props = defineProps({
  viewDataOne: Object,
});
const activeStatuses = [1, "1", "Y", "Yes", "A", "Active", "True", "true"];
// Check if the role status is in the active statuses array
const status = activeStatuses.includes(props.viewDataOne?.status);
</script>
