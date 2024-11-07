<script setup lang="ts">
import { ref, reactive } from 'vue'
import { PlusOutlined } from '@ant-design/icons-vue'

const open = ref<boolean>(false)

// Sample item data for three datasets
const itemOneData = reactive({
    items: [
        { id: 1, name: 'Item-1 One' },
        { id: 2, name: 'Item-1 Two' },
        { id: 3, name: 'Item-1 Three' },
        { id: 4, name: 'Item-1 Four' },
        { id: 5, name: 'Item-1 Five' },
        { id: 6, name: 'Item-1 Six' },
    ]
})

const itemTwoData = reactive({
    items: [
        { id: 1, name: 'Item-2 One' },
        { id: 2, name: 'Item-2 Two' },
        { id: 3, name: 'Item-2 Three' },
        { id: 4, name: 'Item-2 Four' },
        { id: 5, name: 'Item-2 Five' },
        { id: 6, name: 'Item-2 Six' },
    ]
})

const itemThreeData = reactive({
    items: [
        { id: 1, name: 'Item-3 One' },
        { id: 2, name: 'Item-3 Two' },
        { id: 3, name: 'Item-3 Three' },
        { id: 4, name: 'Item-3 Four' },
        { id: 5, name: 'Item-3 Five' },
        { id: 6, name: 'Item-3 Six' },
    ]
})

// Track selected items for each dataset
const itemOneSelectedData = ref<Array<{ id: number; name: string }>>([])
const itemTwoSelectedData = ref<Array<{ id: number; name: string }>>([])
const itemThreeSelectedData = ref<Array<{ id: number; name: string }>>([])

const loopData = ref<Array<{ id: number; name: string }>>([])
const selectedItemIds = ref<number[]>([])
const currentSelectedData = ref<Array<{ id: number; name: string }>>([])
const currentItemSet = ref<number | null>(null)

const showModal = (itemSet: number) => {
    if (itemSet === 1) {
        loopData.value = itemOneData.items
        selectedItemIds.value = itemOneSelectedData.value.map(item => item.id)
        currentSelectedData.value = itemOneSelectedData.value
        currentItemSet.value = 1
    } else if (itemSet === 2) {
        loopData.value = itemTwoData.items
        selectedItemIds.value = itemTwoSelectedData.value.map(item => item.id)
        currentSelectedData.value = itemTwoSelectedData.value
        currentItemSet.value = 2
    } else {
        loopData.value = itemThreeData.items
        selectedItemIds.value = itemThreeSelectedData.value.map(item => item.id)
        currentSelectedData.value = itemThreeSelectedData.value
        currentItemSet.value = 3
    }
    open.value = true
}

const handleOk = () => {
    open.value = false
}

const addItem = (itemId: number) => {
    const selectedItem = loopData.value.find(item => item.id === itemId)
    if (!selectedItem) return

    if (selectedItemIds.value.includes(itemId)) {
        selectedItemIds.value = selectedItemIds.value.filter(id => id !== itemId)
        currentSelectedData.value = currentSelectedData.value.filter(item => item.id !== itemId)
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
    <a-row>
        <a-col :xs="{ span: 5, offset: 1 }" :lg="{ span: 6, offset: 2 }">
            <a-level style="font-weight: bolder;">Main Item-1</a-level>
            <a-button @click="showModal(1)">
                <PlusOutlined />
            </a-button>
            <a-ul>
                <li v-for="item in itemOneSelectedData" :key="item.id">{{ item.name }}</li>
            </a-ul>
        </a-col>
        <a-col :xs="{ span: 11, offset: 1 }" :lg="{ span: 6, offset: 2 }">
            <a-level style="font-weight: bolder;">Main Item-2</a-level>
            <a-button @click="showModal(2)">
                <PlusOutlined />
            </a-button>
            <a-ul>
                <li v-for="item in itemTwoSelectedData" :key="item.id">{{ item.name }}</li>
            </a-ul>
        </a-col>
        <a-col :xs="{ span: 5, offset: 1 }" :lg="{ span: 6, offset: 2 }">
            <a-level style="font-weight: bolder;">Main Item-3</a-level>
            <a-button @click="showModal(3)">
                <PlusOutlined />
            </a-button>
            <a-ul>
                <li v-for="item in itemThreeSelectedData" :key="item.id">{{ item.name }}</li>
            </a-ul>
        </a-col>
    </a-row>

    <div>
        <a-modal v-model:open="open" title="Select Items" @ok="handleOk">
            <a-button v-for="item in loopData" :key="item.id"
                :type="selectedItemIds.includes(item.id) ? 'primary' : 'default'"
                @click="addItem(item.id)">{{ item.name }}
            </a-button>
        </a-modal>
    </div>
</template>
