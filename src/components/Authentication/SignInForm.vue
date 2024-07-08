<template>
    <div id="signInForm">
      <form @submit.prevent="submitForm">
        <h2>Sign in to purchase or selling your products!</h2>
        <input type="text" v-model="username" placeholder="Enter username" />
        <br />
        <input type="password" v-model="password" placeholder="Enter password" />
        <div id="signUp">
          Haven't have an account? <router-link :to="{name:'SignUp'}">Sign up</router-link> with us!
        </div>
        <input type="submit" />
      </form>
    </div>

  </template>
  
  <script setup lang="ts">
  import { ref } from 'vue';
  import { useRouter } from 'vue-router';
  import Header from '@/components/Header.vue'
  import Footer from '@/components/Footer.vue'
  import axios from 'axios'
  import { useAuthStore } from '@/stores/useAuthStore';
  
  const username = ref('');
  const password = ref('');
  const router = useRouter();
  const authStore = useAuthStore();
  
  const submitForm = async () => {
    try {
        const response = await axios.post('http://localhost/Server/login.php', {
            username: username.value,
            password: password.value
        });
        if (response.data.success) {
            alert(response.data.message);
            console.log('res',response.data.user);
            authStore.login(response.data.user);
            console.log("username",username.value);
            console.log("role",response.data.role);
            router.push('/home');
        } else {
            alert(response.data.message);
        }
    } catch (error) {
        console.error('Error during login:', error);
    }
};
  </script>
  
  <style scoped src="@/assets/styles/signInStyle.css"></style>
  