import { ref, reactive, defineComponent } from 'vue';
import { Button, Modal, Row, Col, Input, Select } from 'ant-design-vue';
import { SearchOutlined  } from '@ant-design/icons-vue'
import styles from './css/medicine.module.css'; // Import your CSS module

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
});

const filteredMedicines = ref(medicineOneData.items);
// Track search query
const searchQuery = ref<string>('');
// Track selected items
const medicineOneSelectedData = ref<Array<{ id: number; name: string }>>([]);
const selectedMedicineId = ref<number[]>([]);

// Define state for the modal open status
const open2 = ref<boolean>(false);
// Function to show the modal
export const showModal2 = () => {
  open2.value = true;
};

// Function to add or remove items from selected data
 const addMedicine = (itemId: number) => {
  const currentItem = medicineOneData.items.find(function(item){
    return item.id === itemId;
  });
  if(!currentItem) return;

  if(selectedMedicineId.value.includes(itemId)){
    selectedMedicineId.value = selectedMedicineId.value.filter(function(id){
      return id !== itemId;
    });
    medicineOneSelectedData.value = medicineOneSelectedData.value.filter(function(item){
      return item.id !== itemId;
    });
  }else{
      selectedMedicineId.value.push(itemId);
      medicineOneSelectedData.value.push(currentItem);
    }
}

const searchMedicine = () => {
  if (searchQuery.value) {
    filteredMedicines.value = medicineOneData.items.filter(function(item){
      return item.name.toLowerCase().includes(searchQuery.value.toLowerCase());
    }
    );
  } else {
    filteredMedicines.value = medicineOneData.items; // Reset to all items if search query is empty
  }
};

const oneStatus = ref<number>(0);
const twoStatus = ref<number>(0);
const threeStatus = ref<number>(0);
const fourStatus = ref<number>(0);

const button2 = () => {
  oneStatus.value = 1;
  twoStatus.value = 0;
  threeStatus.value = 0;
  fourStatus.value = 1;
}
const button3 = () => {
  oneStatus.value = 1;
  twoStatus.value = 1;
  threeStatus.value = 0;
  fourStatus.value = 1;
}
const button4 = () => {
  oneStatus.value = 1;
  twoStatus.value = 1;
  threeStatus.value = 1;
  fourStatus.value = 1;
}

// Function to render selected items
const renderSelectedItem = (item: { id: number; name: string }, ind: number) => {
  return (
    <div key={item.id} style="margin-top: 8px;">
      <Row justify="start" gutter={[8, 8]}>
        <Col span={4}>
             <p style="margin-left: 3px;">{ind + 1}. {item.name}</p>
        </Col>
        <Col span={2}>
          <div style={{ display: 'flex' }}>
            <Button 
            class={[styles.customButton, styles.firstButton]} 
            onClick={button2}
            >
            2
            </Button>
            <Button 
            class={[styles.customButton, styles.secondButton]}
            onClick={button3}
            >
            3
            </Button>
            <Button 
            class={[styles.customButton, styles.thirdButton]}
            onClick={button4}
            textTextHoverColor="rgba(0, 0, 0, 0.88)"
            >
            4
            </Button>
          </div>
        </Col>
        <Col span={2}>
            <Input value={oneStatus.value ? 1 : 0}></Input>
        </Col>
        <Col span={2}>
            <Input value={twoStatus.value ? 1 : 0}></Input>
        </Col>
        <Col span={2}>
            <Input value={threeStatus.value ? 1 : 0}></Input>
        </Col>
        <Col span={2}>
            <Input value={fourStatus.value ? 1 : 0}></Input>
        </Col>
        <Col span={2}>
          <Select defaultValue="0">
            <Select.Option value="0" selected>No</Select.Option>
            <Select.Option value="1">Yes</Select.Option>
          </Select>
        </Col>
        <Col span={2}>
            <Input value="0"></Input>
        </Col>
        <Col span={2}>
          <Select style={{ width: 'auto', minWidth: '100%' }}>
            <Select.Option value="1">pcs</Select.Option>
            <Select.Option value="2">tsp</Select.Option>
            <Select.Option value="3">mg</Select.Option>
            <Select.Option value="4">ml</Select.Option>
          </Select>
        </Col>
        <Col span={4}>
            <Input></Input>
        </Col>
      </Row>
    </div>
  );
};

export const MedicineModal =  defineComponent({
  setup() {
    const handleCancel = () => {
      open2.value = false; // Close the modal
    }
    return () => (
      <div>
        <Modal
          title="Select Medicine"
          v-model:open={open2.value} // Bind to visible prop
          onCancel={handleCancel} // Handle modal close
          onOk={handleCancel} // Optionally handle OK button
          width={1050}
          bodyStyle={{ height: '300px', overflowY: 'auto' }} 
        >
        <Row justify="start">
        <Col span={4} style="background-color: #d2d3d6">
              <Input
                placeholder="Search medicine"
                prefix={<SearchOutlined style={{ color: 'rgba(0,0,0,.25)' }} />}
                onInput={(e: any) => {
                  searchQuery.value = (e.target as HTMLInputElement).value;
                  searchMedicine();
                }}
              ></Input>
              {filteredMedicines.value.map((item) => (
                <Button
                  key={item.id}
                  onClick={() => addMedicine(item.id)}
                  style={{ margin: '1px', display: 'inline' }}
                  type={selectedMedicineId.value.includes(item.id) ? 'primary' : 'default'}
                >
                  {item.name}
                </Button>
              ))}
            </Col>
          <Col span={20} style="background-color: #ebeef2">
          {selectedMedicineId.value.length > 0 && (
          <div>
            <Row  justify="start" gutter={8}>
              <Col span={4}>
              </Col>
              <Col span={2}>
              </Col>
              <Col span={2}>
                <p>Morning</p>
              </Col>
              <Col span={2}>
                <p>Lunch</p>
              </Col>
              <Col span={2}>
                <p>Evening</p>
              </Col>
              <Col span={2}>
                <p>Night</p>
              </Col>
              <Col span={2}>
                <p>Continue</p>
              </Col>
              <Col span={2}>
                <p>Duration</p>
              </Col>
              <Col span={2}>
                <p>Unit</p>
              </Col>
              <Col span={4}>
                <p>Remarks</p>
              </Col>
            </Row>
          </div>
          )}
          <div>
            {medicineOneSelectedData.value.map(function(item, index){ return renderSelectedItem(item, index)})}
          </div>
          </Col>
        </Row>
        </Modal>
      </div>
    )
  },
})
