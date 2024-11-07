import { ref, reactive, defineComponent } from 'vue'
import { PlusOutlined } from '@ant-design/icons-vue'
import { Row, Col, Button, Modal } from 'ant-design-vue'
import { showModal2, MedicineModal } from './Medicine2'

const MyComponent = defineComponent({
  setup() {
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
    const itemOneSelectedData = ref<Array<{ id: number; name: string }>>([])
    const itemTwoSelectedData = ref<Array<{ id: number; name: string }>>([])
    const itemThreeSelectedData = ref<Array<{ id: number; name: string }>>([])

    const loopData = ref<Array<{ id: number; name: string }>>([])
    const selectedItemIds = ref<number[]>([])
    const currentSelectedData = ref<Array<{ id: number; name: string }>>([])
    const currentItemSet = ref<number | null>(null)

    const showModal1 = (itemSet: number) => {
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
      showModal2(); // Opens the modal for medicine set 1
    }

    const handleOk = () => {
      open.value = false
    }

    const addItem = (itemId: number) => {
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

    return () => (
      <div>
       <Row style={{ marginBottom: '40px'}}>
          <Col xs={{ span: 5, offset: 1 }} lg={{ span: 6, offset: 2 }}>
            <strong>Main Item-1</strong>
            <Button type="primary" onClick={() => showModal1(1)}>
              <PlusOutlined />
            </Button>
            <ul>
              {itemOneSelectedData.value.map((item) => (
                <li key={item.id}>{item.name}</li>
              ))}
            </ul>
          </Col>
          <Col xs={{ span: 11, offset: 1 }} lg={{ span: 6, offset: 2 }}>
            <strong>Main Item-2</strong>
            <Button type="primary" onClick={() => showModal1(2)}>
              <PlusOutlined />
            </Button>
            <ul>
              {itemTwoSelectedData.value.map((item) => (
                <li key={item.id}>{item.name}</li>
              ))}
            </ul>
          </Col>
          <Col xs={{ span: 5, offset: 1 }} lg={{ span: 6, offset: 2 }}>
            <strong>Main Item-3</strong>
            <Button type="primary" onClick={() => showModal1(3)}>
              <PlusOutlined />
            </Button>
            <ul>
              {itemThreeSelectedData.value.map((item) => (
                <li key={item.id}>{item.name}</li>
              ))}
            </ul>
          </Col>
        </Row>
        <Row>
          <Col xs={{ span: 5, offset: 1 }} lg={{ span: 6, offset: 2 }}>
            <strong>Medicine-1</strong>
            <Button type="primary" onClick={() => handleShowModal2() }>
              <PlusOutlined />
              <MedicineModal />
            </Button>
          </Col>
        </Row>
        <Modal v-model:open={open.value} title="Select Items" onOk={handleOk} onCancel={handleOk}>
          {loopData.value.map((item) => (
            <Button
              key={item.id}
              type={selectedItemIds.value.includes(item.id) ? 'primary' : 'default'}
              onClick={() => addItem(item.id)}
              style={{ margin: '5px' }}
            >
              {item.name}
            </Button>
          ))}
        </Modal>
      </div>
    )
  },
})

export default MyComponent
