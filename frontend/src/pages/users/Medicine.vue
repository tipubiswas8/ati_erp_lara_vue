<script lang="ts" setup>
import { ref, reactive, defineComponent } from 'vue'
import { Button, Modal, Row, Col, Input, Select } from 'ant-design-vue'
import { SearchOutlined } from '@ant-design/icons-vue'
import styles from './css/medicine.module.css' // Import your CSS module

const open = ref<boolean>(true)

const handleOk = () => {
  open.value = false
}

// Sample item data for the dataset
const medicineOneData = reactive({
  items: [
    { id: 1, name: 'Amoxicillin' },
    { id: 2, name: 'Ibuprofen' },
    { id: 3, name: 'Atorvastatin' },
    { id: 4, name: 'Metformin' },
    { id: 5, name: 'Lisinopril' },
    { id: 6, name: 'Omeprazole' },
    { id: 7, name: 'Aspirin' },
    { id: 8, name: 'Levothyroxine' },
    { id: 9, name: 'Metoprolol' },
    { id: 10, name: 'Albuterol' },
    { id: 11, name: 'Losartan' },
    { id: 12, name: 'Hydrochlorothiazide' },
    { id: 13, name: 'Simvastatin' },
    { id: 14, name: 'Pantoprazole' },
    { id: 15, name: 'Gabapentin' },
    { id: 16, name: 'Sertraline' },
    { id: 17, name: 'Zolpidem' },
    { id: 18, name: 'Citalopram' },
    { id: 19, name: 'Fluticasone' },
    { id: 20, name: 'Furosemide' },
  ],
})

const filteredMedicines = ref(medicineOneData.items)
// Track search query
const searchQuery = ref<string>('')
// Track selected items
const medicineOneSelectedData = ref([])
const selectedMedicineId = ref([])

// Define state for the modal open status
const open2 = ref(false)
// Function to show the modal
// export const showModal2 = () => {
//   open2.value = true;
// };

// Function to add or remove items from selected data
const addMedicine = (itemId) => {
  const currentItem = medicineOneData.items.find(function (item) {
    return item.id === itemId
  })
  if (!currentItem) return

  if (selectedMedicineId.value.includes(itemId)) {
    selectedMedicineId.value = selectedMedicineId.value.filter(function (id) {
      return id !== itemId
    })
    medicineOneSelectedData.value = medicineOneSelectedData.value.filter(function (item) {
      return item.id !== itemId
    })
  } else {
    selectedMedicineId.value.push(itemId)
    medicineOneSelectedData.value.push(currentItem)
  }
}

const searchMedicine = () => {
  if (searchQuery.value) {
    filteredMedicines.value = medicineOneData.items.filter(function (item) {
      return item.name.toLowerCase().includes(searchQuery.value.toLowerCase())
    })
  } else {
    filteredMedicines.value = medicineOneData.items // Reset to all items if search query is empty
  }
}

const oneStatus = ref(0)
const twoStatus = ref(0)
const threeStatus = ref(0)
const fourStatus = ref(0)

const button2 = () => {
  oneStatus.value = 1
  twoStatus.value = 0
  threeStatus.value = 0
  fourStatus.value = 1
}
const button3 = () => {
  oneStatus.value = 1
  twoStatus.value = 1
  threeStatus.value = 0
  fourStatus.value = 1
}
const button4 = () => {
  oneStatus.value = 1
  twoStatus.value = 1
  threeStatus.value = 1
  fourStatus.value = 1
}
</script>

<template>
  <div>
    <div>
      <Modal v-model:open="open" title="Select Medicine" width="75%" @cancel="handleOk" @ok="handleOk" 
      :bodyStyle="{ height: '300px' , overflowY: 'auto' }">
        <Row justify="start">
          <Col span="4" style="background-color: #d2d3d6">
          <Input placeholder="Search medicine" v-on:input="(e) => {
            searchQuery = (e.target as HTMLInputElement).value
            searchMedicine()
          }
            "></Input>
          <Button v-for="item in filteredMedicines" :key="item.id" @click="addMedicine(item.id)"
            :style="{ margin: '1px', display: 'inline' }">
            {{ item.name }}
          </Button>
          </Col>
        </Row>
      </Modal>
    </div>
  </div>
</template>
