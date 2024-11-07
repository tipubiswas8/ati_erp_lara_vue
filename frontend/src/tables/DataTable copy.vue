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

const selectedUserId = ref<number | null>(null)

// Computed property to get the selected user based on the ID
// eslint-disable-next-line prettier/prettier
const selectedUserForStatusChange = computed(() =>
tableData.value.find((user) => user.id === selectedUserId.value) || {}
);


const columnsTitle = props.requestData?.columns_name['1'];


console.log(props.requestData?.columns_name);



const columns = [
  {
    title: columnsTitle,
    dataIndex: 'sl',
  },
  {
    title: 'Name',
    dataIndex: 'name',
  },
  {
    title: 'Email',
    dataIndex: 'email',
    sorter: (a, b) => a.email.localeCompare(b.email),
  },
  {
    title: 'Phone',
    dataIndex: 'phone',
    sorter: (a, b) => a.phone.localeCompare(b.phone),
  },
  {
    title: 'Address',
    dataIndex: 'address',
    sorter: (a, b) => a.address.localeCompare(b.address),
  },
  {
    title: 'Action',
    customRender: ({ record }) => {
      return h(
        'div', 
        {
          style: { 
            display: 'flex', 
            justifyContent: 'flex-start', 
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
  },
]

function onChange(pagination, filters, sorter, extra) {
  console.log('Table parameters changed:', { pagination, filters, sorter, extra })
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
</script>

<template>
  <ATable :columns="columns" :data-source="tableData" @change="onChange" />
  <EditModal v-if="openEditModal" :edit-data="userData" @close="closeEditModal" />
  <StatusUpdate v-if="openStatusUpdate" :status-data="selectedUserForStatusChange" @statusUpdate="handleStatusUpdate" />
</template>
