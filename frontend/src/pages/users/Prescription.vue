<script setup>
import { ref, reactive } from 'vue'
import { PlusOutlined } from '@ant-design/icons-vue'
import { Row, Col, Button, Modal } from 'ant-design-vue'
import MedicineModal from './Medicine.vue'

const open = ref(false)
const open2 = ref(false)
// Sample item data for three datasets
const itemOneData = reactive({
  items: [
    { id: 1, name: 'Item-1 One' },
    { id: 2, name: 'Item-1 Two' },
    { id: 3, name: 'Item-1 Three' },
    { id: 4, name: 'Item-1 Four' },
    { id: 5, name: 'Item-1 Five' },
    { id: 6, name: 'Item-1 Six' },
  ],
})

const itemTwoData = reactive({
  items: [
    { id: 1, name: 'Item-2 One' },
    { id: 2, name: 'Item-2 Two' },
    { id: 3, name: 'Item-2 Three' },
    { id: 4, name: 'Item-2 Four' },
    { id: 5, name: 'Item-2 Five' },
    { id: 6, name: 'Item-2 Six' },
  ],
})

const itemThreeData = reactive({
  items: [
    { id: 1, name: 'Item-3 One' },
    { id: 2, name: 'Item-3 Two' },
    { id: 3, name: 'Item-3 Three' },
    { id: 4, name: 'Item-3 Four' },
    { id: 5, name: 'Item-3 Five' },
    { id: 6, name: 'Item-3 Six' },
  ],
})

// Track selected items for each dataset
const itemOneSelectedData = ref([])
const itemTwoSelectedData = ref([])
const itemThreeSelectedData = ref([])

const loopData = ref([])
const selectedItemIds = ref([])
const currentSelectedData = ref([])
const currentItemSet = ref(null)

const openMedicineModal = defineProps({
  doOpen: Boolean
});

const showModal1 = (itemSet) => {
  if (itemSet === 1) {
    loopData.value = itemOneData.items
    selectedItemIds.value = itemOneSelectedData.value.map((item) => item.id)
    currentSelectedData.value = itemOneSelectedData.value
    currentItemSet.value = 1
  } else if (itemSet === 2) {
    loopData.value = itemTwoData.items
    selectedItemIds.value = itemTwoSelectedData.value.map((item) => item.id)
    currentSelectedData.value = itemTwoSelectedData.value
    currentItemSet.value = 2
  } else {
    loopData.value = itemThreeData.items
    selectedItemIds.value = itemThreeSelectedData.value.map((item) => item.id)
    currentSelectedData.value = itemThreeSelectedData.value
    currentItemSet.value = 3
  }
  open.value = true
}

const handleShowModal2 = () => {
  open2.value = true
}

const handleOk = () => {
  open.value = false
}

const addItem = (itemId) => {
  const selectedItem = loopData.value.find((item) => item.id === itemId)
  if (!selectedItem) return

  if (selectedItemIds.value.includes(itemId)) {
    selectedItemIds.value = selectedItemIds.value.filter((id) => id !== itemId)
    currentSelectedData.value = currentSelectedData.value.filter((item) => item.id !== itemId)
  } else {
    selectedItemIds.value.push(itemId)
    currentSelectedData.value.push(selectedItem)
  }

  // Update the correct selected dataset
  if (currentItemSet.value === 1) {
    itemOneSelectedData.value = [...currentSelectedData.value]
  } else if (currentItemSet.value === 2) {
    itemTwoSelectedData.value = [...currentSelectedData.value]
  } else if (currentItemSet.value === 3) {
    itemThreeSelectedData.value = [...currentSelectedData.value]
  }
}
</script>

<template>
  <Row style="margin-bottom: 40px">
    <Col :xs="{ span: 5, offset: 1 }" :lg="{ span: 6, offset: 2 }">
    <strong>Main Item-1</strong>
    <Button type="primary" @click="showModal1(1)">
      <PlusOutlined />
    </Button>
    <ul>
      <li v-for="(item1) in itemOneSelectedData" :key="item1.id">{{ item1.name }}</li>
    </ul>
    </Col>
    <Col :xs="{ span: 11, offset: 1 }" :lg="{ span: 6, offset: 2 }">
    <strong>Main Item-2</strong>
    <Button type="primary" v-on:click=showModal1(2)>
      <PlusOutlined />
    </Button>
    <ul>
      <li v-for="(item3) in itemTwoSelectedData" :key="item3.id">{{ item3.name }}</li>
    </ul>
    </Col>
    <Col :xs="{ span: 5, offset: 1 }" :lg="{ span: 6, offset: 2 }">
    <strong>Main Item-3</strong>
    <Button type="primary" v-on:click=showModal1(3)>
      <PlusOutlined />
    </Button>
    <ul>
      <li v-for="(item3) in itemThreeSelectedData" :key="item3.id">{{ item3.name }}</li>
    </ul>
    </Col>
  </Row>
  <Row>
    <Col :xs="{ span: 5, offset: 1 }" :lg="{ span: 6, offset: 2 }">
    <strong>Medicine-1</strong>
    <Button type="primary" @click="handleShowModal2()">
      <PlusOutlined />
    </Button>
    </Col>
  </Row>
  <Modal v-model:open="open" title="Select Items" @ok="handleOk" @cancel="handleOk">
    <Button v-for="ld in loopData" :key="ld.id" v-on:click=addItem(ld.id) style="margin: 5px"
      :type="selectedItemIds.includes(ld.id) ? 'primary' : 'default'">
      {{ ld.name }}
    </Button>
  </Modal>
  <MedicineModal v-if="open2" />
</template>
