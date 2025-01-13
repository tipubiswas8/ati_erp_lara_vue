<template>
  <ADescriptions>
    <ADescriptionsItem style="display: block;" label="Permission Name">{{ props.showPermission?.name }}
    </ADescriptionsItem>
    <ADescriptionsItem style="display: block;" label="Organization Name">{{ props.showPermission?.org_name
      }}
    </ADescriptionsItem>
    <ADescriptionsItem style="display: block;" label="Permission Status">
      <a-switch :checked="status" checked-children="Active" un-checked-children="Inactive" :disabled="true" />
    </ADescriptionsItem>
  </ADescriptions>
</template>

<script lang="ts" setup>
const props = defineProps({
  showPermission: Object,
});
const activeStatuses = [1, "1", "Y", "Yes", "A", "Active", "True", "true"];
// Check if the permission status is in the active statuses array
const status = activeStatuses.includes(props.showPermission?.status);
</script>
