<template>
    <a-breadcrumb style="margin: 8px 0">
    <a-breadcrumb-item>Project Two</a-breadcrumb-item>
    <a-breadcrumb-item>x</a-breadcrumb-item>
  </a-breadcrumb>
  <h2 style="text-align: center">User Information</h2>
  <a-row>
    <a-col :span="6">
      <router-link :to="{ name: 'create_modal' }">
        <a-button type="primary">Add New</a-button>
      </router-link>
    </a-col>
    <a-col :span="12"></a-col>
    <a-col :span="6">
      <a-input v-model:value="value" placeholder="" />
    </a-col>
  </a-row>
  <hr />

  <a-table :columns="columns" :data-source="dataSource" bordered>
    <template #bodyCell="{ column, text, record }">
      <template v-if="['name', 'age', 'address'].includes(column.dataIndex)">
        <div>
          <a-input
            v-if="editableData[record.key]"
            v-model:value="editableData[record.key][column.dataIndex]"
            style="margin: -5px 0"
          />
          <template v-else>
            {{ text }}
          </template>
        </div>
      </template>
      <template v-else-if="column.dataIndex === 'operation'">
        <div class="editable-row-operations">
          <span v-if="editableData[record.key]">
            <a-typography-link @click="save(record.key)">Save</a-typography-link>
            <a-popconfirm title="Sure to cancel?" @confirm="cancel(record.key)">
              <a>Cancel</a>
            </a-popconfirm>
          </span>
          <span v-else>
            <a @click="edit(record.key)">Edit</a>
          </span>
        </div>
      </template>
    </template>
  </a-table>
</template>
<script lang="ts" setup>
import { cloneDeep } from 'lodash-es'
import { reactive, ref } from 'vue'
import type { UnwrapRef } from 'vue'
import axios from 'axios'

const columns = [
  {
    title: 'name',
    dataIndex: 'name',
    width: '25%'
  },
  {
    title: 'email',
    dataIndex: 'email',
    width: '15%'
  },
  {
    title: 'address',
    dataIndex: 'address',
    width: '40%'
  },
  {
    title: 'phone',
    dataIndex: 'phone'
  }
]
interface DataItem {
  key: string
  name: string
  age: number
  address: string
}

// var token = '2|7ZPwn6egUhX91Wr5daUXLA7SsS8rK5L5D1XJMCN4'
// axios.defaults.headers.common = {
//   Authorization: 'Bearer ' + token
// }

// axios
//   .get('http://127.0.0.1:8000/api/users')
//   .then(function (response) {
//     console.log(response.data)
//   })
//   .catch(function (error) {
//     console.log(error)
//   })

const data = [
  {
    id: 1,
    name: 'John Doe',
    email: 'john.doe@example.com',
    address: '123 Main St, Springfield',
    phone: '123-456-7890'
  },
  {
    id: 2,
    name: 'Jane Smith',
    email: 'jane.smith@example.com',
    address: '456 Oak St, Metropolis',
    phone: '987-654-3210'
  },
  {
    id: 3,
    name: 'Michael Johnson',
    email: 'michael.johnson@example.com',
    address: '789 Pine St, Gotham',
    phone: '555-555-5555'
  },
  {
    id: 4,
    name: 'Emily Davis',
    email: 'emily.davis@example.com',
    address: '321 Maple St, Star City',
    phone: '222-333-4444'
  },
  {
    id: 5,
    name: 'David Brown',
    email: 'david.brown@example.com',
    address: '654 Cedar St, Central City',
    phone: '444-555-6666'
  },
  {
    id: 6,
    name: 'Sophia Martinez',
    email: 'sophia.martinez@example.com',
    address: '987 Birch St, Coast City',
    phone: '777-888-9999'
  },
  {
    id: 7,
    name: 'James Wilson',
    email: 'james.wilson@example.com',
    address: '147 Elm St, Blüdhaven',
    phone: '111-222-3333'
  },
  {
    id: 8,
    name: 'Isabella Anderson',
    email: 'isabella.anderson@example.com',
    address: '258 Oakwood St, Smallville',
    phone: '444-777-8888'
  },
  {
    id: 9,
    name: 'Ethan Thomas',
    email: 'ethan.thomas@example.com',
    address: '369 Chestnut St, National City',
    phone: '999-000-1111'
  },
  {
    id: 10,
    name: 'Mia Lee',
    email: 'mia.lee@example.com',
    address: '741 Ash St, Keystone City',
    phone: '222-444-6666'
  },
  {
    id: 11,
    name: 'Alexander Harris',
    email: 'alexander.harris@example.com',
    address: '852 Willow St, Hub City',
    phone: '888-555-4444'
  },
  {
    id: 12,
    name: 'Olivia Clark',
    email: 'olivia.clark@example.com',
    address: '963 Sycamore St, Coast City',
    phone: '777-666-5555'
  },
  {
    id: 13,
    name: 'Benjamin Rodriguez',
    email: 'benjamin.rodriguez@example.com',
    address: '174 Redwood St, Fawcett City',
    phone: '333-444-5555'
  },
  {
    id: 14,
    name: 'Emma Lewis',
    email: 'emma.lewis@example.com',
    address: '285 Spruce St, Midway City',
    phone: '999-888-7777'
  },
  {
    id: 15,
    name: 'William Walker',
    email: 'william.walker@example.com',
    address: '396 Cypress St, Ivy Town',
    phone: '555-666-7777'
  },
  {
    id: 16,
    name: 'Ava Hall',
    email: 'ava.hall@example.com',
    address: '417 Magnolia St, Happy Harbor',
    phone: '111-222-4444'
  },
  {
    id: 17,
    name: 'Lucas Allen',
    email: 'lucas.allen@example.com',
    address: '528 Aspen St, Gateway City',
    phone: '333-666-9999'
  },
  {
    id: 18,
    name: 'Charlotte King',
    email: 'charlotte.king@example.com',
    address: '639 Palm St, New Carthage',
    phone: '888-999-7777'
  },
  {
    id: 19,
    name: 'Henry Wright',
    email: 'henry.wright@example.com',
    address: '741 Dogwood St, St. Roch',
    phone: '000-111-2222'
  },
  {
    id: 20,
    name: 'Amelia Scott',
    email: 'amelia.scott@example.com',
    address: '852 Sequoia St, Central City',
    phone: '555-444-3333'
  },
  {
    id: 21,
    name: 'Mason Green',
    email: 'mason.green@example.com',
    address: '963 Fir St, Blüdhaven',
    phone: '222-333-1111'
  },
  {
    id: 22,
    name: 'Sophia Carter',
    email: 'sophia.carter@example.com',
    address: '174 Poplar St, Gotham',
    phone: '333-222-1111'
  },
  {
    id: 23,
    name: 'Liam Young',
    email: 'liam.young@example.com',
    address: '285 Hickory St, Star City',
    phone: '888-111-0000'
  },
  {
    id: 24,
    name: 'Isabella Hernandez',
    email: 'isabella.hernandez@example.com',
    address: '396 Cedar St, Coast City',
    phone: '777-555-4444'
  },
  {
    id: 25,
    name: 'James White',
    email: 'james.white@example.com',
    address: '417 Birch St, Metropolis',
    phone: '999-000-2222'
  },
  {
    id: 26,
    name: 'Emily Thomas',
    email: 'emily.thomas@example.com',
    address: '528 Walnut St, Ivy Town',
    phone: '444-777-6666'
  },
  {
    id: 27,
    name: 'Daniel Martinez',
    email: 'daniel.martinez@example.com',
    address: '639 Oak St, Happy Harbor',
    phone: '555-111-2222'
  },
  {
    id: 28,
    name: 'Olivia Moore',
    email: 'olivia.moore@example.com',
    address: '741 Maple St, National City',
    phone: '777-333-8888'
  },
  {
    id: 29,
    name: 'Michael Anderson',
    email: 'michael.anderson@example.com',
    address: '852 Ash St, Fawcett City',
    phone: '111-444-5555'
  },
  {
    id: 30,
    name: 'Sophia Garcia',
    email: 'sophia.garcia@example.com',
    address: '963 Pine St, Central City',
    phone: '333-888-9999'
  },
  {
    id: 31,
    name: 'Benjamin Martinez',
    email: 'benjamin.martinez@example.com',
    address: '174 Elm St, Star City',
    phone: '000-222-3333'
  },
  {
    id: 32,
    name: 'Charlotte Miller',
    email: 'charlotte.miller@example.com',
    address: '285 Willow St, Midway City',
    phone: '999-555-6666'
  },
  {
    id: 33,
    name: 'Lucas Lee',
    email: 'lucas.lee@example.com',
    address: '396 Sycamore St, Keystone City',
    phone: '111-333-2222'
  },
  {
    id: 34,
    name: 'Amelia Davis',
    email: 'amelia.davis@example.com',
    address: '417 Cedar St, Blüdhaven',
    phone: '444-111-7777'
  },
  {
    id: 35,
    name: 'Henry Rodriguez',
    email: 'henry.rodriguez@example.com',
    address: '528 Spruce St, Hub City',
    phone: '333-555-8888'
  },
  {
    id: 36,
    name: 'Emily Harris',
    email: 'emily.harris@example.com',
    address: '639 Oak St, Gateway City'
  }
]

const dataSource = ref<object>(data)
const editableData: UnwrapRef<Record<string, DataItem>> = reactive({})

const edit = (key: string) => {
  editableData[key] = cloneDeep(dataSource.value.filter((item) => key === item.key)[0])
}
const save = (key: string) => {
  Object.assign(dataSource.value.filter((item) => key === item.key)[0], editableData[key])
  delete editableData[key]
}
const cancel = (key: string) => {
  delete editableData[key]
}
</script>
<style scoped>
.editable-row-operations a {
  margin-right: 8px;
}
</style>
