<script lang="ts" setup>
import { ref, onMounted, h, computed, watch } from 'vue'
import axios from 'axios'
import { Button, Popconfirm, message, Spin } from 'ant-design-vue'
import { EditOutlined, DeleteOutlined, PauseCircleOutlined, CheckCircleOutlined, EyeOutlined } from '@ant-design/icons-vue'
import EditModal from '@src/modals/EditModal.vue'
import StatusUpdate from '@src/modals/StatusUpdate.vue'

const setEmit = defineEmits(['isViewModalOpen', 'userDataForView']);

message.config({
  top: '50px',
  duration: 5,
  maxCount: 3,
  rtl: true,
  prefixCls: 'my-message',
})

const props = defineProps({
  requestData: {
    type: Object,
    required: true
  },
  editData: {
    type: Object,
    required: true,
  },
  newUserData: Object, // New user data
  updatedUserData: Object, // updated user data
})

const tableData = ref([])
const userData = ref({})
const openEditModal = ref(false)
const isLoading = ref(false)
const openStatusUpdate = ref(false)
const pagination = ref({
  current: 1,          // Current page
  pageSize: 10,        // Items per page
  total: 0,            // Total items (updated from API response)
  showSizeChanger: true, // Allow user to change page size
  showQuickJumper: true, // Allow user to jump to specific page
});

const selectedUserId = ref<number | null>(null)

// Computed property to get the selected user based on the ID
// eslint-disable-next-line prettier/prettier
const selectedUserForStatusChange = computed(() => {
  const user = tableData.value.find((user) => user.id === selectedUserId.value) || {};
  return {
    ...user,
    status_url: props.requestData?.urls.status_url // Adding the additional property to selectedUserForStatusChange
  };
});


let columns = [];
const tableHead = props.requestData?.th_name;
// Create dynamic columns based on requestData.th_name
if (tableHead) {
  columns = Object.entries(props.requestData.th_name).map(([key, title]) => ({
    title: () => h(
      'div',
      {
        style: {
          textAlign: props.requestData?.th_align || 'left',  // Header alignment
        },
      },
      title
    ),
    dataIndex: props.requestData?.td_data[key],
    key,
    customRender: ({ text }) => h(
      'div',
      {
        style: {
          textAlign: props.requestData?.td_align || 'left',  // Cell alignment
        },
      },
      text
    ),
  }));

  columns.push({
    title: props.requestData?.last_th_name,
    align: props.requestData?.last_th_align || 'center',
    customRender: ({ record }) => {
      return h(
        'div',
        {
          style: {
            display: 'flex',
            justifyContent: props.requestData?.last_td_align || 'center',
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
          record.status === 1
            ? h(Button, {
              type: 'default',
              size: 'small',
              icon: h(CheckCircleOutlined),
              onClick: () => showStatusUpdater(record.id),
              style: { display: 'flex', justifyContent: 'center', alignItems: 'center', backgroundColor: 'green' },
            })
            : h(Button, {
              type: 'default',
              size: 'small',
              danger: true,
              icon: h(PauseCircleOutlined),
              onClick: () => showStatusUpdater(record.id),
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


// Transform the response data to ensure "id" is consistent
const transformData = (data: any[]) => {
  return data.map(item => ({
    ...item,
    id: props.requestData?.custom_id ? item[props.requestData?.custom_id] : item.id,
    name: props.requestData?.custom_name ? item[props.requestData?.custom_name] : item.name,
  }));
};

// Watch for new user data
watch(
  () => props.newUserData,
  (newUserInfo) => {
    if (newUserInfo && Object.keys(newUserInfo).length > 0) {
      const sl = tableData.value.length + 1; // Generate serial number
      tableData.value.push({
        ...newUserInfo,
        sl,
      });
    }
  },
  { deep: true }
);

// Watch for updated user data
watch(
  () => props.updatedUserData,
  (updatedUserInformation) => {
    if (updatedUserInformation && Object.keys(updatedUserInformation).length > 0) {
      // Find the index of the user in tableData based on a unique ID
      const index = tableData.value.findIndex(
        (user) => user.id === updatedUserInformation.user_id
      );

      if (index !== -1) {
        // Update the specific user in the tableData array
        tableData.value[index] = {
          ...tableData.value[index], // Keep existing properties
          ...updatedUserInformation, // Overwrite with updated properties
        };
      }
    }
  },
  { deep: true }
);




async function fetchData() {
  isLoading.value = true; // Start loading
  try {
    const response = await axios.get(props.requestData?.urls.data_url);
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
  fetchData()
})

const handleEdit = (id: number) => {
  const selectedUser = tableData.value.find((item) => item.id === id)
  if (selectedUser) {
    userData.value = selectedUser
    openEditModal.value = true
  }
}

const closeEditModal = () => {
  openEditModal.value = false
  userData.value = {}
}

const handleView = (id: number) => {
  setEmit('isViewModalOpen', true);
  const selectedUser = tableData.value.find((item) => item.id === id)
  if (selectedUser) {
    setEmit('userDataForView', selectedUser);
  }
}

const handleDelete = async (id: number) => {
  try {
    const response = await axios.delete(props.requestData?.urls.delete_url + '/' + id);
    if (response.status === 200) {
      message.success(response.data.message)
      tableData.value = tableData.value.filter((user) => user.id !== id)
      // Recalculate the sl (serial numbers) for the remaining users
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

const showStatusUpdater = (id: number) => {
  selectedUserId.value = id
  openStatusUpdate.value = !openStatusUpdate.value
}

const handleStatusUpdate = (receiveData: object) => {
  // console.log(receiveData.responseFeedback);
  openStatusUpdate.value = receiveData.statusUpdate
}
function handleTableChange(paginationInfo: any) {
  pagination.value.current = paginationInfo.current;
  pagination.value.pageSize = paginationInfo.pageSize;
}

const editComponent = props.editData?.editComponent;

</script>

<template>
  <Spin :spinning="isLoading" tip="Loading..." size="large">
    <ATable :columns="columns" :data-source="tableData" :pagination="{
      current: pagination.current,
      pageSize: pagination.pageSize,
      total: pagination.total,
      showSizeChanger: pagination.showSizeChanger,
      showQuickJumper: pagination.showQuickJumper,
    }" @change="handleTableChange" />
  </Spin>
  <EditModal v-if="openEditModal" :edit-data="userData" @close="closeEditModal">
    <component :is="editComponent" />
  </EditModal>
  <StatusUpdate v-if="openStatusUpdate" :status-data="selectedUserForStatusChange" @sendToParent="handleStatusUpdate" />
</template>