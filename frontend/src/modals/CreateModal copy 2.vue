<template>
  <div class="modal-overlay" v-if="modalOpen">
    <div class="modal">
      <h2>Create Item</h2>
      <form @submit.prevent="onFinish">
        <div class="form-group">
          <label for="name">Name</label>
          <input type="text" v-model="formState.user.name" id="name" />
          <span class="error">{{ fieldErrors.name }}</span>
        </div>

        <div class="form-group">
          <label for="address">Address</label>
          <input type="text" v-model="formState.user.address" id="address" />
          <span class="error">{{ fieldErrors.address }}</span>
        </div>

        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" v-model="formState.user.password" id="password" />
          <span class="error">{{ fieldErrors.password }}</span>
        </div>

        <div class="form-group">
          <label for="image">Image</label>
          <input type="file" @change="handleFileUpload" id="image" />
          <span class="error">{{ fieldErrors.image }}</span>
        </div>

        <div class="form-group">
          <button type="submit">Submit</button>
        </div>
      </form>
      <button @click="handleCancel" class="cancel-button">Cancel</button>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { reactive, ref } from 'vue'
import axios from 'axios'

const modalOpen = ref(true); // Manage modal visibility

const formState = reactive({
  user: {
    name: '',
    address: '',
    password: '',
    image: null, // Set to null initially for file handling
  },
});

// For field-specific errors
const fieldErrors = reactive({
  name: '',
  address: '',
  password: '',
  image: '',
});

// Handle file upload
const handleFileUpload = (event: Event) => {
  const target = event.target as HTMLInputElement;
  if (target.files && target.files.length > 0) {
    formState.user.image = target.files[0]; // Store the uploaded file
  }
};

const onFinish = async () => {
  try {
    // Create FormData to handle file uploads
    const formData = new FormData();
    formData.append('name', formState.user.name);
    formData.append('address', formState.user.address);
    formData.append('password', formState.user.password);
    formData.append('image', formState.user.image); // Append image file
    const response = await axios.post('/user-register', formData, {
      headers: {
        'Content-Type': 'multipart/form-data', // Important for file upload
      },
    });
    console.log(response);
  } catch (error) {
    console.log(error);
  }
};

const handleCancel = () => {
  modalOpen.value = false; // Close modal
}
</script>

<style scoped>
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5); /* Dark overlay */
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000; /* High z-index to overlay other content */
  animation: fadeIn 0.3s ease-in; /* Fade-in animation */
}

.modal {
  background: white;
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
  width: 400px;
  max-width: 90%;
  animation: scaleIn 0.3s ease-in-out; /* Scale-in animation */
}

@keyframes fadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

@keyframes scaleIn {
  from {
    transform: scale(0.8);
  }
  to {
    transform: scale(1);
  }
}

.form-group {
  margin-bottom: 16px;
}

.error {
  color: red; /* Change to desired error color */
}

input[type="text"],
input[type="email"],
input[type="number"],
input[type="password"] {
  width: 100%; /* Full width */
  padding: 8px; /* Padding for better touch */
  border: 1px solid #ccc; /* Light border */
  border-radius: 4px; /* Rounded corners */
  box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1); /* Subtle inner shadow */
  transition: border-color 0.3s; /* Smooth transition for border color */
}

input[type="text"]:focus,
input[type="email"]:focus,
input[type="number"]:focus,
input[type="password"]:focus {
  border-color: #780650; /* Change border color on focus */
  outline: none; /* Remove default outline */
}

.cancel-button {
  margin-top: 10px;
  background: transparent;
  border: none;
  color: #780650; /* Cancel button color */
  cursor: pointer;
}
</style>
