<template>
  <div class="create-item-container">
    <h2>Create New Item</h2>
    <form @submit.prevent="createItem">
      <div>
        <label for="name">Item Name:</label>
        <input type="text" id="name" v-model="itemName" required />
      </div>
      <div>
        <label for="description">Description:</label>
        <textarea id="description" v-model="itemDescription" rows="10" required></textarea>
      </div>
      <div>
        <label for="price">Price:</label>
        <input type="number" step="0.01" id="price" v-model="itemPrice" required />
      </div>
      <div v-for="(image, index) in uploadedImages" :key="index">
        <label :for="'image-' + index">Image {{ index + 1 }}:</label>
        <input type="file" :id="'image-' + index" @change="handleFileUpload($event, index)" />
        <div v-if="image.preview" class="image-preview-container">
          <img :src="image.preview" alt="Preview Image" class="image-preview" />
        </div>
      </div>
      <div>
        <button type="button" @click="addImageInput">Add Another Image</button>
      </div>
      <button type="submit">Create Item</button>
    </form>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import axios from 'axios';

const itemName = ref('');
const itemDescription = ref('');
const itemPrice = ref(0);
const uploadedImages = ref<{ file: File | null; preview: string | null }[]>([
  { file: null, preview: null },
]);

const handleFileUpload = (event: Event, index: number) => {
  const target = event.target as HTMLInputElement;
  if (target.files && target.files.length > 0) {
    const file = target.files[0];
    uploadedImages.value[index].file = file;

    const reader = new FileReader();
    reader.onload = (e) => {
      const result = e.target?.result;
      if (result && typeof result === 'string') {
        uploadedImages.value[index].preview = result;
      }
    };
    reader.readAsDataURL(file);
  }
};

const addImageInput = () => {
  uploadedImages.value.push({ file: null, preview: null });
};

const createItem = async () => {
  const formData = new FormData();
  formData.append('name', itemName.value);
  formData.append('description', itemDescription.value);
  formData.append('price', itemPrice.value.toString());

  uploadedImages.value.forEach((image, index) => {
    if (image.file) {
      formData.append(`images[]`, image.file);
    }
  });

  try {
    const response = await axios.post('http://localhost/Server/item.php', formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    });
    if (response.data.status === 'success') {
      alert('Item created successfully');
      // Reset the form fields
      itemName.value = '';
      itemDescription.value = '';
      itemPrice.value = 0;
      uploadedImages.value = [{ file: null, preview: null }];
    } else {
      alert('Failed to create item');
    }
  } catch (error) {
    console.error('Error creating item:', error);
    alert('An error occurred while creating the item');
  }
};
</script>
<style scoped>
.create-item-container {
  width: 50%;
  margin: 0 auto;
  padding: 2rem;
  background-color: #f9f9f9;
  border: 1px solid #ddd;
  border-radius: 8px;
}

form div {
  margin-bottom: 1rem;
}

label {
  display: block;
  margin-bottom: 0.5rem;
}

input,
textarea {
  width: 80%;
  padding: 0.5rem;
  border: 1px solid #ccc;
  border-radius: 4px;
  resize: none;
}

button {
  padding: 0.5rem 1rem;
  background-color: #007bff;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

button:hover {
  background-color: #0056b3;
}

.image-preview-container {
  display: flex;
  overflow-x: auto;
  scroll-behavior: smooth;
  white-space: nowrap;
  border: 1px solid #ddd;
  padding: 10px;
  margin-bottom: 1rem;
  border-radius: 4px;
  background-color: #f9f9f9;
}

.image-preview {
  display: inline-block;
  margin-right: 10px;
  width: 100px;
  height: auto;
  border: 1px solid #ddd;
  border-radius: 4px;
}
</style>
