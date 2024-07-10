<template>
    <div class="item-detail-container">
      <div class="item-images">
        <img v-for="image in item.images" :key="image" :src="image" alt="Item Image" />
      </div>
      <div class="item-content">
        <h2>{{ item.name }}</h2>
        <p>{{ item.description }}</p>
        <p>Price: {{ item.price }}</p>
      </div>
    </div>
  </template>
  
  <script setup lang="ts">
  import { ref, onMounted } from 'vue';
  import axios from 'axios';
  import { useRoute } from 'vue-router';
  
  const item = ref({
    id: 0,
    name: '',
    description: '',
    price: 0,
    images: []
  });
  
  const route = useRoute();
  
  onMounted(() => {
    const itemId = Number(route.params.id);
    getItem(itemId);
  });
  
  const getItem = async(itemId: number) => {
    try {
      const response = await axios.get(`http://localhost/Server/getItem.php?id=${itemId}`);
      if (response.data.success) {
        console.log('itemDetail response:',response);
        item.value = response.data.item;
      } else {
        console.error(response.data.message);
      }
    } catch (error) {
      console.error('Error during getItem:', error);
    }
  };
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
  .item-detail-container {
    margin:0 auto;
    width: 80%;
    display: flex;
    flex-direction: row;
  }
  
  .item-images {
    flex: 1;
  }
  
  .item-images img {
    width: 100%;
    height: auto;
  }
  
  .item-content {
    flex: 1;
    padding: 20px;
  }
  </style>
  