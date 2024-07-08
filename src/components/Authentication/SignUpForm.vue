<template>
    <div id="signInForm">
        <form @submit.prevent="submitForm">
            <h2>Sign Up</h2>
            <input type="email" v-model="email" placeholder="Your email" />
            <br />
            <input type="text" v-model="username" placeholder="Your username" />
            <br />
            <input type="password" v-model="password" placeholder="Your password" />
            <br />
            <input type="password" v-model="confirmPassword" placeholder="Confirm password" />
            <br />
            <div v-if="passwordError" class="error">{{ passwordError }}</div>
            <br />
            <div id="signUp">
                Already have an account? <router-link :to="{name:'SignIn'}">Sign in</router-link> directly!
            </div>
            <input type="submit" />
        </form>
    </div>

  </template>
  
  <script setup lang="ts">
  import { ref,computed } from 'vue';
  import { useRouter } from 'vue-router';
  import Header from '@/components/Header.vue'
  import Footer from '@/components/Footer.vue'
  import axios from 'axios'
  
  const email = ref('');
  const username = ref('');
  const password = ref('');
  const confirmPassword = ref('');
  const router = useRouter();

  const passwordError = computed(() => {
        return password.value !== confirmPassword.value ? 'Passwords do not match' : '';
    });

  const submitForm = async () => {
    if (passwordError.value) {
        return;
    }
    try {
        const response = await axios.post('http://localhost/Server/signUp.php', {
            email: email.value,
            userN: username.value,
            passW: password.value,
        });
        if (response.data.success) {
            alert('Sign up successful!');
            router.push('/SignInForm'); 
        } else {
            alert('Sign up failed: ' + response.data.message);
        }
    } catch (error) {
        console.error('Error during sign up:', error);
    }
};
  </script>
  
  <style scoped src="@/assets/styles/signInStyle.css">
</style>
  