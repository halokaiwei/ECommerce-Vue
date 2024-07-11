<template>
  <!-- contain item details -->
  <div class="item-container">
    <ul class="item-list">
      <li v-for="item in items" :key="item.id" class="item-card" @click="goToItemDetail(item.id)">
        <div class="item-image">
          <img :src="item.images[0]" alt="Item Image" />
        </div>
        <div class="item-details">
          <h4>{{ item.name }}</h4>
          <p>Price: {{ item.price }}</p>
        </div>
      </li>
    </ul>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import axios from 'axios';
import { type items } from '@/types/index'
import router from '@/router';

const items = ref<items[]>([]);

onMounted(() => {
  getItems();
});

const getItems = async () => {
  try {
    const response = await axios.get('http://localhost/Server/getItems.php');
    if (response.data.success) {
      items.value = response.data.items;
      console.log("Success getting items: ", response);
    } else {
      console.error(response.data.message);
    }
  }
  catch (error) {
    console.error('Error during getItems:', error);
  }
}

const goToItemDetail = (itemId: number) => {
  router.push({ path: `/item/${itemId}` });
};
</script>

<style scoped src="@/assets/styles/itemStyles.css">
</style>
