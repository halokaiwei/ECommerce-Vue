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
          <input type="number" id="price" v-model="itemPrice" required />
        </div>
        <div>
          <label for="images">Images:</label>
          <input type="file" id="images" @change="handleFileUpload" multiple />
        </div>
        <div v-if="uploadedImages.length > 0">
          <h3>Uploaded Images:</h3>
          <ul>
            <li v-for="(image, index) in uploadedImages" :key="index">{{ image.name }}</li>
          </ul>
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
  const itemImages = ref<File[]>([]);
  const uploadedImages = ref<File[]>([]);
  
  const handleFileUpload = (event: Event) => {
    const target = event.target as HTMLInputElement;
    if (target.files && target.files.length > 0) {
      itemImages.value = Array.from(target.files);
      uploadedImages.value = Array.from(target.files);
    }
  };
  
  const createItem = async () => {
    const formData = new FormData();
    formData.append('name', itemName.value);
    formData.append('description', itemDescription.value);
    formData.append('price', itemPrice.value.toString());
    console.log("formData",formData);
    itemImages.value.forEach((image, index) => {
      formData.append(`images[]`, image);
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
            itemImages.value = [];
            uploadedImages.value = [];
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
  
  input, textarea {
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
  </style>
  