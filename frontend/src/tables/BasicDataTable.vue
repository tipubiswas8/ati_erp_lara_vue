<script lang="ts" setup>
// required
import { ref, onMounted, h, computed, watch } from 'vue'
import axios from 'axios'
import { Button, Popconfirm, message, Spin } from 'ant-design-vue'
import { EditOutlined, DeleteOutlined, PauseCircleOutlined, CheckCircleOutlined, EyeOutlined } from '@ant-design/icons-vue'
import EditModal from '@src/modals/EditModal.vue'
import StatusNotification from '@src/notifications/StatusNotification.vue'
// optional
import CreateModal from '@src/modals/CreateModal.vue'
import ViewModal from '@src/modals/ViewModal.vue'

const props = defineProps({
  // datatable props
  tableDataOne: {
    type: Object,
    required: true
  },
  // create props
  isCreateModalOpen: {
    type: Boolean
  },
  createDataOne: Object,
  createDataTwo: {
    type: Object || null || undefined
  },
  // edit props
  isEmOpen: Boolean || false,
  editDataOne: {
    type: Object
  },
  updateDataOne: Object,
  // view props
  isVMOpen: Boolean || false,
  viewDataOne: Object || null || undefined,
  // status props
  statusDataOne: {
    type: Object,
    required: true
  },
});

const setEmit = defineEmits([
  'isCreateModalClose',
  'isEditModalOpen',
  'isEditModalClose',
  'dataForUpdate',
  'isViewModalOpen',
  'dataForView',
  'isVMClose'
]);

let columns = [];
const tableHead = props.tableDataOne?.th_name;
// Create dynamic columns based on tableDataOne.th_name
if (tableHead) {
  columns = Object.entries(props.tableDataOne.th_name).map(([key, title]) => ({
    title: () => h(
      'div',
      {
        style: {
          textAlign: props.tableDataOne?.th_align || 'left',  // Header alignment
        },
      },
      title
    ),
    dataIndex: props.tableDataOne?.td_data[key],
    key,
    customRender: ({ text }) => h(
      'div',
      {
        style: {
          textAlign: props.tableDataOne?.td_align || 'left',  // Cell alignment
        },
      },
      text
    ),
  }));

  columns.push({
    title: props.tableDataOne?.last_th_name,
    align: props.tableDataOne?.last_th_align || 'center',
    customRender: ({ record }) => {
      return h(
        'div',
        {
          style: {
            display: 'flex',
            justifyContent: props.tableDataOne?.last_td_align || 'center',
            gap: '8px', // Adds space between buttons
          },
        },
        [
          h(Button, {
            type: 'primary',
            size: 'small',
            icon: h(EditOutlined),
            onClick: () => handleEdit(record.id),
            style: { display: 'flex', justifyContent: 'center', alignItems: 'center' },
          }),
          h(Button, {
            type: 'primary',
            size: 'small',
            icon: h(EyeOutlined),
            onClick: () => handleView(record.id),
            style: { display: 'flex', justifyContent: 'center', alignItems: 'center', backgroundColor: '#1234', color: 'black', borderColor: '#ff5733' },
          }),
          record.status === 1 || record.status === "1" || record.status === "Y" || record.status === "Yes" || record.status === "A" || record.status === "Active" || record.status === "True" || record.status === "true"
            ? h(Button, {
              type: 'default',
              size: 'small',
              icon: h(CheckCircleOutlined),
              onClick: () => showStatusNotification(record.id),
              style: { display: 'flex', justifyContent: 'center', alignItems: 'center', backgroundColor: 'green' },
            })
            : h(Button, {
              type: 'default',
              size: 'small',
              danger: true,
              icon: h(PauseCircleOutlined),
              onClick: () => showStatusNotification(record.id),
              style: { display: 'flex', justifyContent: 'center', alignItems: 'center', backgroundColor: 'yellow' },
            }),
          h(
            Popconfirm,
            {
              title: 'Are you sure delete this task?',
              okText: 'Yes',
              cancelText: 'No',
              onConfirm: () => handleDelete(record.id),
              onCancel: cancelDelete,
            },
            {
              default: () =>
                h(Button, {
                  type: 'primary',
                  size: 'small',
                  danger: true,
                  icon: h(DeleteOutlined),
                  style: { display: 'flex', justifyContent: 'center', alignItems: 'center' },
                }),
            },
          ),
        ]
      )
    },
  });
}

const pagination = ref({
  current: 1,          // Current page
  pageSize: 10,        // Items per page
  total: 0,            // Total items (updated from API response)
  showSizeChanger: true, // Allow data to change page size
  showQuickJumper: true, // Allow data to jump to specific page
});

function handleTableChange(paginationInfo: any) {
  pagination.value.current = paginationInfo.current;
  pagination.value.pageSize = paginationInfo.pageSize;
}

// Transform the response data to ensure "id" is consistent
const transformData = (data: any[]) => {
  return data.map(item => ({
    ...item,
    id: props.tableDataOne?.custom_id ? item[props.tableDataOne?.custom_id] : item.id,
    name: props.tableDataOne?.custom_name ? item[props.tableDataOne?.custom_name] : item.name,
    status: props.statusDataOne?.statusColumnName ? item[props.statusDataOne?.statusColumnName] : item.status,
  }));
};

const tableData = ref([]);
const isLoading = ref(false);

async function fetchData() {
  isLoading.value = true; // Start loading
  try {
    const response = await axios.get(props.tableDataOne?.urls.data_url);
    if (response.status === 200) {
      tableData.value = transformData(response.data.data.map((item: object, index: number) => ({
        ...item,
        sl: index + 1,
      })));
    }
  } catch (error) {
    console.log(error);
  } finally {
    isLoading.value = false; // End loading
  }
}

onMounted(() => {
  fetchData();
});

// for create 
// required
// Watch for new create data
watch(
  () => props.createDataOne,
  (newInformation) => {
    if (newInformation) {
      addedData(newInformation);
    } else {
      console.warn('No data provided for adding.');
      return;
    }
  },
  { deep: true }
);

// optional
const openCreateModal = ref(false);
const createComponent = props.createDataTwo?.createComponent;
const createModalConfigaration = props.createDataTwo?.config ? props.createDataTwo : undefined;

// Watch for new create data if create modal in datatable
watch(
  () => props.isCreateModalOpen,
  (doOpen) => {
    openCreateModal.value = doOpen;
  },
  { deep: true }
);

// Function to add new data
const addedData = (new_data: object | object[]) => {
  if (Array.isArray(new_data)) {
    if (!new_data) {
      console.warn('No data provided for adding.');
      return;
    }
    // If new_data is an array, add a unique serial number for each item
    new_data.forEach((item) => {
      tableData.value.push({
        ...item,
        sl: tableData.value.length + 1 // Calculate serial number dynamically
      });
    });
  } else {
    // If new_data is a single object, push it directly with a serial number
    tableData.value.push({
      ...new_data,
      sl: tableData.value.length + 1 // Calculate serial number
    });
  }
  // update id and name after create
  tableData.value = transformData(tableData.value.map((item: object, index: number) => ({
    ...item,
    sl: index + 1,
  })));
};

const closeCreateModal = () => {
  openCreateModal.value = false;
  setEmit('isCreateModalClose', openCreateModal.value);
}

// for edit
// required
const information = ref({});
const openEditModal = ref(false);
const editComponent = props.editDataOne?.editComponent;
const editModalConfigData = props.editDataOne?.sendPropDataForEM;
const othersDataForEdit = props.editDataOne?.othersData;

const handleEdit = (id: number) => {
  setEmit('isEditModalOpen', true);
  const selectedInfoForEdit = tableData.value.find((item) => item.id === id);
  if (selectedInfoForEdit) {
    information.value = {
      ...selectedInfoForEdit, // Spread selectedInfoForEdit to include all its properties
      edit_url: props.tableDataOne?.urls.edit_url, // Add or overwrite edit_url
    };
    setEmit('dataForUpdate', selectedInfoForEdit);
  }
}

watch(
  () => props.isEmOpen,
  (doEditModalOpen) => {
    openEditModal.value = doEditModalOpen;
  },
  { deep: true }
);

// Watch for updated information
watch(
  () => props.updateDataOne,
  (updatedInformation) => {
    if (updatedInformation && Object.keys(updatedInformation).length > 0) {
      // Find the index of the information in tableData based on a unique ID
      const index = tableData.value.findIndex(
        (t_data) => t_data.id === updatedInformation.id
      );

      if (index !== -1) {
        // Update the specific information in the tableData array
        tableData.value[index] = {
          ...tableData.value[index], // Keep existing properties
          ...updatedInformation, // Overwrite with updated properties
        };
      }
      responseEditData(updatedInformation);
    }
  },
  { deep: true }
);

interface EditResponseData {
  [key: string]: object; // Allow other dynamic properties
}

const responseEditData = (eRData: EditResponseData) => {
  const selectedInfoForEditResponse = tableData.value.find((item) => item.id === eRData.id);
  if (selectedInfoForEditResponse) {
    Object.assign(selectedInfoForEditResponse, eRData); // Update the selected item with the new data
  }
}

const closeEditModal = () => {
  openEditModal.value = false;
  setEmit('isEditModalClose', openEditModal.value);
  information.value = {}
}

// for view 
// required
let selectedInfoForView: any;
const handleView = (id: number) => {
  setEmit('isViewModalOpen', true);
  selectedInfoForView = tableData.value.find((item) => item.id === id)
  if (selectedInfoForView) {
    setEmit('dataForView', selectedInfoForView);
  }
}
// optional
const viewComponent = props.viewDataOne?.viewComponent;
const viewModalConfigData = props.viewDataOne?.config ? props.viewDataOne : undefined;
const openViewModal = ref(false);

// Watch for new view data if view modal in datatable
watch(
  () => props.isVMOpen,
  (doVMOpen) => {
    openViewModal.value = doVMOpen;
  },
  { deep: true }
);

const closeViewModal = () => {
  openViewModal.value = false,
    setEmit('isVMClose', openViewModal.value);
};

// for status 
// required
const openStatusNotification = ref(false);
const showStatusNotification = (id: number) => {
  selectedInfoIdForStatus.value = id;
  openStatusNotification.value = true;
}

const handleStatusUpdate = (receiveData: { statusUpdate: boolean, responseFeedback: any }) => {
  if (receiveData.statusUpdate === true) {
    const info = tableData.value.find((item) => item.id === selectedInfoIdForStatus.value);
    if (info) {
      // Update the status of the found item
      info.status = receiveData.responseFeedback;
    }
  };
  openStatusNotification.value = false;
}

const selectedInfoIdForStatus = ref<number | null>(null);
// Computed property to get the selected info based on the ID
const selectedInfoForStatusChange = computed(() => {
  const info = tableData.value.find((item) => item.id === selectedInfoIdForStatus.value) || {};
  const column_name = props.statusDataOne?.statusChangeFor;
  const c_name = column_name ? info[column_name] : info.name; // Dynamically access the property
  return {
    ...info,
    name_for_status_change: c_name,
    status_url: props.tableDataOne?.urls.status_url // Adding the additional property to selectedInfoForStatusChange
  };
});

// for delete
// required
const handleDelete = async (id: number) => {
  try {
    const response = await axios.delete(props.tableDataOne?.urls.delete_url + '/' + id);
    if (response.status === 200) {
      message.success(response.data.message)
      tableData.value = tableData.value.filter((singleItem) => singleItem.id !== id)
      // Recalculate the sl (serial numbers) for the remaining informations
      tableData.value = tableData.value.map((item, index) => ({
        ...item,
        sl: index + 1,
      }))
    }
  } catch (error) {
    console.log(error.response.data.message)
  }
}

const cancelDelete = () => {
  // message.error('Click on No')
}

// for notification
// required
message.config({
  top: '50px',
  duration: 5,
  maxCount: 3,
  rtl: true,
  prefixCls: 'my-message',
});

const nInfo = ref<object>({});
const notify = (notic: object) => {
  nInfo.value = notic;
  setTimeout(() => {
    nInfo.value = {};
  }, 0);
}

</script>

<template>
  <!-- required -->
  <Spin :spinning="isLoading" tip="Loading..." size="large">
    <ATable :columns="columns" :data-source="tableData" :pagination="{
      current: pagination.current,
      pageSize: pagination.pageSize,
      total: pagination.total,
      showSizeChanger: pagination.showSizeChanger,
      showQuickJumper: pagination.showQuickJumper,
    }" @change="handleTableChange" />
  </Spin>

  <!-- optional -->
  <CreateModal v-if="openCreateModal" :modal-config-data="createModalConfigaration" @close="closeCreateModal">
    <component :is="createComponent" :create-data-one="props.tableDataOne" @closeCreateModal="closeCreateModal"
      @newAddedData="addedData" />
  </CreateModal>

  <EditModal v-if="openEditModal" :config-data="editModalConfigData" @close="closeEditModal" :notify-data="nInfo">
    <component :is="editComponent" :edit-data-one="information" :edit-data-two="othersDataForEdit"
      @closeEM="closeEditModal" @responseData="responseEditData" @notificationInfo="notify" />
  </EditModal>

  <!-- optional -->
  <ViewModal v-if="openViewModal" :view-modal-config-data="viewModalConfigData" @closeVm="closeViewModal">
    <component :is="viewComponent" :view-data-two="selectedInfoForView" @closeVModal="closeViewModal" />
  </ViewModal>

  <StatusNotification v-if="openStatusNotification" :status-data="selectedInfoForStatusChange"
    :columns-name="props.statusDataOne" @sendToParent="handleStatusUpdate" />
</template>