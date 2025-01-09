<template>
  <ADescriptions>
    <ADescriptionsItem style="display: block;" label="Role Name">{{ props.viewDataTwo?.name }}
    </ADescriptionsItem>
    <ADescriptionsItem style="display: block;" label="Organization Name">{{ props.viewDataTwo?.org_name
      }}
    </ADescriptionsItem>
    <ADescriptionsItem style="display: block;" label="Role Status">
      <a-switch :checked="status" checked-children="Active" un-checked-children="Inactive" :disabled="true" />
    </ADescriptionsItem>
  </ADescriptions>
</template>

<script setup>
const props = defineProps({
  viewDataTwo: Object,
});
const activeStatuses = [1, "1", "Y", "Yes", "A", "Active", "True", "true"];
// Check if the role status is in the active statuses array
const status = activeStatuses.includes(props.viewDataTwo?.status);
</script>
