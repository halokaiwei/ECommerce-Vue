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
          <img v-if="user?.image" :src="user.image" alt="User Image">
          <span v-else>No image uploaded</span>
      </div>
    </div>
    <br/>
    <div class="vertical-buttons">
      <button v-if="!showEditPassword && !showEditImage" @click="toggleEditPassword">Change Password</button>
      <form v-if="showEditPassword" @submit.prevent="updatePassword">
          <h3>Edit Password <button @click="toggleEditPassword">Cancel</button></h3>
          <br>
          <input type="password" class="editPassword" v-model="oldPassword" placeholder="Old Password" required>
          <br>
          <input type="password" class="editPassword" v-model="password" placeholder="New Password" required>
          <br>
          <input type="password" class="editPassword" v-model="confirmPassword" placeholder="Confirm Password" required>
          <br>
          <div v-if="passwordError" class="error">{{ passwordError }}</div>
          <br>
          <button type="submit">Update Password</button>
      </form>

      <button v-if="!showEditPassword && !showEditImage" @click="toggleEditImage">Change Profile Picture</button>
      <form v-if="showEditImage" @submit.prevent="uploadImage" enctype="multipart/form-data">
          <h3>Upload User Image<button @click="toggleEditImage">Cancel</button></h3>
          <input type="file" accept="image/*" @change="handleFileUpload">
          <br>
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
const user = ref<null | { username: string; email: string; role: string; image: string }>(null);

const showEditPassword = ref(false);
const showEditImage = ref(false);
const oldPassword = ref('');
const password = ref('');
const confirmPassword = ref('');
const imageFile = ref<File | null>(null);

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
        username: user.value?.username,
    });
    if (response.data.success) {
        alert('Password updated successfully');
        oldPassword.value = '';
        password.value = '';
        confirmPassword.value = '';
        showEditPassword.value = false;
    } else {
        alert(response.data.message || 'Failed to update password');
    }
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
  formData.append('user_name', String(user.value?.username)); // Append the user_id to the form data

  try {
    const response = await axios.post('http://localhost/Server/uploadImage.php', formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    });
    alert('Image uploaded successfully, refresh to view');
    showEditImage.value = false;

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

const getUser = async() => {
  try {
    const response = await axios.get('http://localhost/Server/getUser.php', {
      params: { username: userStore.getUser?.username } // replace 'your-username' with the actual username
    });
    console.log("getUser:",response);
    if (response.data.error) {
      alert(response.data.error);
    } else {
      user.value = response.data;
    }
  } catch (error) {
    console.error('Error fetching user profile:', error);
    alert('Failed to fetch user profile');
  }
}

onMounted(async() => {
  getUser();
});
</script>

<style scoped>
@media screen and (max-width: 300px) {
    html {
        font-size:6px;
    }
}
@media screen and (max-width: 414px) {
    html {
        font-size:8px;
    }
}
@media screen and (max-width: 576px) {
    html {
        font-size:12px;
    }
}
@media screen and (max-width: 767px) {
    html {
        font-size:14px;
    }
}
@media screen and (max-width: 768px) {
    html {
        font-size:14px;
    }
}
@media screen and (max-width: 992px) {
    html {
        font-size:16px;
    }
}
@media screen and (max-width: 1200px) {
    html {
        font-size:18px;
    }
} 
  .profile-container {
    max-width: 40%;
    margin: auto;
    padding: 2rem;
    background-color: #f0f0f0;
    border: 1px solid #ddd;
    border-radius: 8px;
  }

  .profile-details {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
  }

  .user-details {
    flex: 1;
    padding-right: 0.5rem;
  }

  .user-image {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
  }

  .user-image img {
    width: 10rem;
    height: auto;
  }

  .vertical-buttons {
    display: flex;
    flex-direction: column;
    width: 30%;
    gap: 1rem;
  }

  button {
    padding: 1rem;
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

  @media (max-width: 700px) {
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
.error {
    color: red;
}
</style>
