<template>
  <ATable :columns="columns" :data-source="data" @change="onChange" />
  <EditModal v-if="openEditModal" @close="closeEditModal" />
</template>

<script lang="ts" setup>
import { ref, onMounted, h } from 'vue'
import axios from 'axios'
import { useAuthStore } from '../stores/auth'
import { Button } from 'ant-design-vue'
import { EditOutlined, DeleteOutlined } from '@ant-design/icons-vue'
import EditModal from '../modals/EditModal.vue'

const authStore = useAuthStore()
authStore.loadToken()
const token = authStore.token

const data = ref([])
const openEditModal = ref(false)
const editData = ref(null)

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
          icon: h(EditOutlined),
          onClick: () => handleEdit(record.id),
          style: { marginRight: '8px' }, // Add some margin between buttons
        }),
        h(Button, {
          type: 'primary',
          danger: true,
          icon: h(DeleteOutlined),
          onClick: () => handleDelete(record.id),
        }),
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
    // console.log('Response:', response.data)
    // Add serial number to each data item
    data.value = response.data.map((item, index) => ({
      ...item,
      sl: index + 1,
      key: item.id,
    }))
  } catch (error) {
    // console.error('Error fetching data:', error)
  }
}

onMounted(() => {
  fetchData()
})

const handleEdit = (id: number) => {
  const user = data.value.find((item) => item.id === id)
  openEditModal.value = true;
  editData.value = user;
}

const closeEditModal = () => {
  openEditModal.value = false
  editData.value = false;
}

const handleDelete = (id: number) => {
  console.log(`Delete user with id: ${id}`)
}
</script>
