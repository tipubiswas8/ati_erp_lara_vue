<template>
  <div :style="parentRow">
    <div :style="parentCol1">
      <div :style="formDiv">
        <a-form @submit="onSubmit" :model="form">
          <a-form-item
            name="email"
          >
            <a-label>Email or Username:</a-label>
            <a-input v-model="form.email" placeholder="Enter your email or username" autocomplete="off" />
          </a-form-item>
          <a-form-item
            name="password"
          >
            <a-label>Password:</a-label>
            <a-input-password v-model="form.password" placeholder="Enter your password" />
          </a-form-item>
          <a-form-item>
            <a-button type="primary" html-type="submit" block :loading="loading"> Log in </a-button>
          </a-form-item>
        </a-form>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import axios from 'axios';
import { defineComponent, ref } from 'vue'; 
import type { CSSProperties } from 'vue';
import { useAuthStore } from '@/stores/auth';

export default defineComponent({
  setup() {
    const parentRow: CSSProperties = {
      width: '100%',
      height: '100vh',
      backgroundColor: '#e4ede6',
      display: 'flex',
      flexWrap: 'wrap',
      justifyContent: 'center',
      alignContent: 'center'
    }
    const parentCol1: CSSProperties = {
      height: '40%',
      minHeight: '220px',
      width: '30%',
      backgroundColor: '#cfdee3',
      border: '1px solid #c7afbe',
      borderRadius: '3%',
      display: 'flex',
      flexWrap: 'wrap',
      justifyContent: 'center',
      alignContent: 'center'
    }
    const formDiv: CSSProperties = {
      height: '80%',
      width: '80%'
    }
    const authStore = useAuthStore();
    const form = ref({
      email: '',
      password: '',
    });
    const loading = ref(false);

    const onSubmit = async () => {
      try {
        loading.value = true;
        const response = await axios.post('http://localhost:8000/api/login', form.value);
        const token = response.data.token;
        console.log('Login response:', response.data);
        authStore.setToken(token);
      //  this.$router.push({ name: 'admin' });
      } catch (error) {
        console.error('Login failed:', error);
      } finally {
        loading.value = false;
      }
    };

    return {
      form,
      loading,
      onSubmit,
      parentRow,
      parentCol1,
      formDiv
    }
  }
})
</script>
