<template>
  <div class="profile-container">
    <h2>My Profile</h2>
    <div>
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
      <div>
        <label>User Image: </label>
        <img v-if="user?.image" src="" alt="User Image" style="max-width: 200px; height: auto;">
        <span v-else>No image uploaded</span>
      </div>
      <br/>
      <div class="vertical-buttons">
        <button @click="toggleEditPassword">Change Password</button>
        <form v-if="showEditPassword" @submit.prevent="updatePassword">
          <h3>Edit Password</h3>
          <input type="password" class="editPassword" v-model="password" placeholder="New Password" required>
          <br>
          <input type="password" class="editPassword" v-model="confirmPassword" placeholder="Confirm Password" required>
          <br>
          <button type="submit">Update Password</button>
        </form>
        <button @click="toggleEditImage">Change Profile Picture</button>
        <form v-if="showEditImage" @submit.prevent="uploadImage" enctype="multipart/form-data">
          <h3>Upload User Image</h3>
          <input type="file" accept="image/*" @change="handleFileUpload">
          <br>
          <button type="submit">Upload Image</button>
        </form>
        <button @click="transferToMerchant">Transfer to Merchant Role</button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue';
import axios from 'axios';
import { useAuthStore } from '@/stores/useAuthStore';

const userStore = useAuthStore();
const user = computed(() => userStore.getUser);
const showEditPassword = ref(false);
const showEditImage = ref(false);
const password = ref('');
const confirmPassword = ref('');
const imageFile = ref<File | null>(null);

const toggleEditPassword = () => {
  showEditPassword.value = !showEditPassword.value;
};

const toggleEditImage = () => {
  showEditImage.value = !showEditImage.value;
};

const updatePassword = async () => {
  if (password.value !== confirmPassword.value) {
    alert('Passwords do not match');
    return;
  }

  try {
    await axios.post('/api/updatePassword', {
      password: password.value
    });
    alert('Password updated successfully');
  } catch (error) {
    console.error('Error updating password:', error);
    alert('Failed to update password');
  }
};

const handleFileUpload = (event: Event) => {
  const target = event.target as HTMLInputElement;
  if (target.files && target.files.length > 0) {
    imageFile.value = target.files[0];
  }
};

const uploadImage = async () => {
  if (!imageFile.value) {
    alert('Please select an image file');
    return;
  }

  const formData = new FormData();
  formData.append('image', imageFile.value);

  try {
    const response = await axios.post('/api/uploadImage', formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    });
    alert('Image uploaded successfully');
    if (userStore.user) {
      userStore.user.image = response.data.imageUrl; // Assuming imageUrl is returned from server
    }
  } catch (error) {
    console.error('Error uploading image:', error);
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
  } catch (error) {
    console.error('Error transferring role:', error);
    alert('Failed to transfer role');
  }
};
</script>

<style scoped>
.profile-container {
  max-width: 40%;
  margin: auto;
  padding: 2rem;
  background-color: #f0f0f0;
  border: 1px solid #ddd;
  border-radius: 8px;
}

.vertical-buttons {
  display: flex;
  flex-direction: column;
  width: 30%;
  gap: 1rem;
}

button {
  padding: 10px;
  background-color: #007bff;
  color: white;
  border: none;
  cursor: pointer;
  border-radius: 4px;
  font-size: 0.8rem;
}

button:hover {
  background-color: #0056b3;
}

@media (max-width: 600px) {
  .profile-container {
    width: 100%;
    max-width: 100%;
  }
  .home-container {
    width: 100%;
  }
  .navigation {
    width: 30%;
    box-shadow: none;
    margin-bottom: 20px;
  }
  .editPassword {
    width: 120%;
    padding: 0.6rem;
    margin-bottom: 0.6rem;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
    font-size: 1rem;
  }
  .editPassword:focus {
    outline: none;
    border-color: #007bff;
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
  }
  button[type="submit"] {
    padding: 10px;
    background-color: #007bff;
    color: white;
    border: none;
    cursor: pointer;
    border-radius: 4px;
  }
  button[type="submit"]:hover {
    background-color: #0056b3;
  }
}
</style>
