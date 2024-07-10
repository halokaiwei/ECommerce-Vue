<template>
  <div class="profile-container">
    <h2>My Profile</h2>
    <div class="profile-details">
  <div class="user-details">
        <div>
          <label>Username: </label>
          <span>{{ user?.username }}</span>
    </div>
        <div>
          <label>Email: </label>
          <span>{{ user?.email }}</span>
        </div>
        <div>
          <label>Role: </label>
          <span>{{ user?.role }}</span>
        </div>
      </div>
          <div class="user-image">
            <label>User Image: </label>
            <img  v-if="user?.image" :src="user.image"  alt="user image">
            <span v-else>No image uploaded</span>
          </div>
    </div>
    <br />
    <div class="vertical-buttons">
      <button v-if="!showEditPassword && !showEditImage" @click="toggleEditPassword">Change Password</button>
      <form v-if="showEditPassword" @submit.prevent="updatePassword">
        <h3>Edit Password <button @click="toggleEditPassword">Cancel </button></h3>
        <br />
        <input type="password"   class="editPassword" v-model="oldPassword" placeholder="Old Password" required>
        <br />
        <input type="password" class="editPassword" v-model="password" placeholder="New Password" required>
        <br />
        <input type="password" class="editPassword" v-model="confirmPassword" placeholder="Confirm Password" required>
        <br />
        
        <div v-if="passwordError" class="error">{{ passwordError }}</div>
        <br />
        <button type="submit">Update Password</button>
      </form>

     <button v-if="!showEditPassword && !showEditImage" @click="toggleEditImage">Change Profile Picture</button>
    <form v-if="showEditImage" @submit.prevent="uploadImage" enctype="multipart/form-data">
        <h3>Upload User Image <button @click="toggleEditImage">Cancel</button></h3>
        <input type="file" accept="image/*" @change="handleFileUpload">
        <br />
        <img v-if="imagePreview" :src="imagePreview" alt="Selected Image" class="preview-image">
        <button type="submit">Upload Image</button>
    </form>

      <button v-if="!showEditPassword && !showEditImage" @click="transferToMerchant">Transfer to Merchant Role</button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';
import { useAuthStore } from '@/stores/useAuthStore';

const userStore = useAuthStore();
const user = ref<null | { id:number; username: string; email: string; role: string; image: string }>(null);

const showEditPassword = ref(false);
const showEditImage = ref(false);

const oldPassword = ref('');
const password = ref('');
const confirmPassword = ref('');

const imageFile = ref<File | null>(null);
const imagePreview = ref<string | null>(null);

const toggleEditPassword = () => {
    showEditPassword.value = !showEditPassword.value;
    showEditImage.value = false;
};

const toggleEditImage = () => {
    showEditImage.value = !showEditImage.value;
    showEditPassword.value = false;
};

const passwordError = computed(() => {
    return password.value !== confirmPassword.value ? 'Passwords do not match' : '';
});

const updatePassword = async () => {
  if (passwordError.value) {
    alert('Passwords do not match');
    return;
  }
  try {
    const response = await axios.post('http://localhost/Server/updatePw.php', {
      oldPassword: oldPassword.value,
      newPassword: password.value,
        userID: userStore.getUser?.id }
      );
    if (response.data.success) {
      alert('Password updated successfully');
        oldPassword.value = '';
        password.value = '';
        confirmPassword.value = '';
        showEditPassword.value = false;
    } 
    else {
      alert(response.data.message || 'Failed to update password');
    }
  } 
  catch (e) {
    console.error('Error updating password:', e);
    alert('Failed to update password');
  }
};

const handleFileUpload = (event: Event) => {
  const target = event.target as HTMLInputElement;
  if (target.files && target.files.length > 0) {
    imageFile.value = target.files[0];

      const reader = new FileReader();  //filereader is to show the preview photo uploaded
      reader.onload = (e) => {
          imagePreview.value = e.target?.result as string;
      };
      reader.readAsDataURL(imageFile.value);
  }
};

const uploadImage = async () => {
  if (!imageFile.value) {
    alert('Please select an image file');
    return;
  }
    const formData = new FormData();
    formData.append('image', imageFile.value);
    formData.append('user_id', String(userStore.getUser?.id));
  try {
    const response = await axios.post('http://localhost/Server/uploadImage.php', formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }});

      alert('Image uploaded successfully, refresh to view');
      showEditImage.value = false;
      imagePreview.value = null;
  } 
  catch (e) {
      console.error('Error uploading image:', e);
      alert('Failed to upload image');
  }
};

const transferToMerchant = async () => {
  try {
    await axios.post('/api/transferToMerchant', {
        username: userStore.user?.username
    });
    alert('Role transferred to Merchant successfully');
    if (userStore.user) {
        userStore.user.role = 'merchant';
    }
  }
   catch (error) {
      console.error('Error transferring role:', error);
      alert('Failed to transfer role');
  }
};

const getUser = async () => {
  try {
    const response = await axios.get('http://localhost/Server/getUser.php', {
        params: { user_id: userStore.getUser?.id }
    });
    console.log("getUser:", response);

    if (response.data.error) {
        alert(response.data.error);
    } else {
        user.value = response.data;
    }
  } 
  catch (error) {
      console.error('Error fetching user profile:', error);
      alert('Failed to fetch user profile');
  }
};

onMounted(async () => {
   getUser();
});
</script>

<style scoped src="@/assets/styles/profileStyles.css">

</style>
