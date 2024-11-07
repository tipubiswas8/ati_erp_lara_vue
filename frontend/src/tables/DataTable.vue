<script lang="ts" setup>
import { ref, onMounted, h, computed } from 'vue'
import axios from 'axios'
import { Button, Popconfirm, message } from 'ant-design-vue'
import { EditOutlined, DeleteOutlined, PauseCircleOutlined, CheckCircleOutlined } from '@ant-design/icons-vue'
import EditModal from '@src/modals/EditModal.vue'
import StatusUpdate from '@src/modals/StatusUpdate.vue'

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
  },
})

const tableData = ref([])
const userData = ref({})
const openEditModal = ref(false)
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
const selectedUserForStatusChange = computed(() =>
  tableData.value.find((user) => user.id === selectedUserId.value) || {}
);

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


async function fetchData() {
  try {
    const response = await axios.get(props.requestData?.data_source.url)
    if (response.status === 200) {
      tableData.value = response.data.data.map((item: object, index: number) => ({
        ...item,
        sl: index + 1,
      }))
      console.log(tableData.value);
    }
  } catch (error) {
    console.log(error)
  }
}

onMounted(() => {
  fetchData()
})

const handleEdit = (id: number) => {
  console.log(id);
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

const handleDelete = async (id: number) => {
  try {
    const response = await axios.delete('/user-delete', { params: { id: id } })
    if (response.status === 200) {
      message.success(response.data.message)
      tableData.value = tableData.value.filter((user) => user.id !== id)
      // Recalculate the sl (serial numbers) for the remaining users
      console.log(tableData.value);
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
  console.log('status')
  selectedUserId.value = id
  openStatusUpdate.value = !openStatusUpdate.value
}

const handleStatusUpdate = (status: boolean) => {
  openStatusUpdate.value = status
}
function handleTableChange(paginationInfo: any) {
  pagination.value.current = paginationInfo.current;
  pagination.value.pageSize = paginationInfo.pageSize;
}
</script>

<template>
  <ATable :columns="columns" :data-source="tableData" :pagination="{
    current: pagination.current,
    pageSize: pagination.pageSize,
    total: pagination.total,
    showSizeChanger: pagination.showSizeChanger,
    showQuickJumper: pagination.showQuickJumper,
  }" @change="handleTableChange" />
  <EditModal v-if="openEditModal" :edit-data="userData" @close="closeEditModal" />
  <StatusUpdate v-if="openStatusUpdate" :status-data="selectedUserForStatusChange" @statusUpdate="handleStatusUpdate" />
</template>