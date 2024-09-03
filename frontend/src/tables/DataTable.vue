<script lang="ts" setup>
import { ref, onMounted, h } from 'vue'
import axios from 'axios'
import { useAuthStore } from '../stores/auth'
import { Button, Popconfirm, message } from 'ant-design-vue'
import { EditOutlined, DeleteOutlined, PauseCircleOutlined, CheckCircleOutlined } from '@ant-design/icons-vue'
import EditModal from '../modals/EditModal.vue'
import StatusDrawer from '../modals/StatusDrawer.vue'

message.config({
  top: '50px',
  duration: 5,
  maxCount: 3,
  rtl: true,
  prefixCls: 'my-message',
})

const authStore = useAuthStore()
authStore.loadToken()
const token = authStore.token

const data = ref([])
const userData = ref({})
const openEditModal = ref(false)
const openStatusDrawer = ref(false)

const columns = [
  {
    title: 'Sl',
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
      return h('div', [
        h(Button, {
          type: 'primary',
          size: 'small',
          icon: h(EditOutlined),
          onClick: () => handleEdit(record.id),
          style: { marginRight: '8px' },
        }),
        record.status === 1
          ? h(Button, {
            type: 'default',
            size: 'small',
            icon: h(CheckCircleOutlined),
            onClick: () => showDrawer(record.id),
            style: { marginRight: '8px' },
          })
          : h(Button, {
            type: 'default',
            size: 'small',
            danger: true,
            icon: h(PauseCircleOutlined),
            onClick: () => showDrawer(record.id),
            style: { marginRight: '8px' },
          }),
        h(
          Popconfirm,
          {
            title: 'Are you sure delete this task?',
            okText: 'Yes',
            cancelText: 'No',
            onConfirm: () => handleDelete(record.id),
            onCancel: cancel,
          },
          {
            default: () =>
              h(Button, {
                type: 'primary',
                size: 'small',
                danger: true,
                icon: h(DeleteOutlined),
              }),
          },
        ),
      ])
    },
  },
]

function onChange(pagination, filters, sorter, extra) {
  console.log('Table parameters changed:', { pagination, filters, sorter, extra })
}

axios.defaults.headers.common['Authorization'] = `Bearer ${token}`

async function fetchData() {
  try {
    const response = await axios.get('http://localhost:8000/api/users')
    data.value = response.data.map((item, index) => ({
      ...item,
      sl: index + 1,
      key: item.id,
    }))
  } catch (error) {
    // Handle error
  }
}

onMounted(() => {
  fetchData()
})

const handleEdit = (id: number) => {
  const selectedUser = data.value.find((item) => item.id === id)
  if (selectedUser) {
    userData.value = selectedUser
    openEditModal.value = true
  }
}


const closeEditModal = () => {
  openEditModal.value = false
  userData.value = {}
}

const showDrawer = () => {
  if (openStatusDrawer.value === false) {
    openStatusDrawer.value = true;
  } else {
    openStatusDrawer.value = false;
  }
}

const handleDelete = async (id: number) => {
  try {
    const response = await axios.delete('http://localhost:8000/api/user-delete', { params: { id: id } })
    if (response.data.status === 200) {
      message.success(response.data.message)
    } else if (response.data.status === 300) {
      message.error(response.data.message)
    }
  } catch (error) {
    console.error(error)
  }
}

const cancel = () => {
  // message.error('Click on No')
}
</script>

<template>
  <ATable :columns="columns" :data-source="data" @change="onChange" />
  <EditModal v-if="openEditModal" :edit-data="userData" @close="closeEditModal" />
  <StatusDrawer v-if="openStatusDrawer" :status-data="userData" />
</template>
