<template>
  <div>
    <!-- Header -->

    <!-- Search banner -->
    <div id="banner">
      <div id="bannerWord">
        <h3>Welcome to our digital marketplace!</h3>
      </div>
      <div id="searchMid">
        <label for="search">
          <input v-model="searchText" type="text" id="search" placeholder="Search items here...">
        </label>
      </div>
    </div>

    <!-- Banner+navigation -->
    <div class="home-container">
      <div class="navigation">
        <ul>
          <li v-for="(category, index) in categories" :key="index" @click="selectCategory(index)">
            {{ category.name }}
          </li>
        </ul>
      </div>
      <div class="content">
        <div class="image-controls">
          <button @click="prevImage">&lt;</button>
          <button @click="nextImage">&gt;</button>
        </div>
        <div class="image-wrapper" :style="{ transform: `translateX(-${currentImageIndex * 100}%)` }">
          <img v-for="(category, index) in categories" :key="index" :src="category.image" alt="Example" />
        </div>
      </div>
    </div>

    <Items></Items>

    <!-- Footer -->
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'
import Header from '@/components/Header.vue'
import Footer from '@/components/Footer.vue'
import Items from '@/components/Items.vue'
import wallpaper1 from '@/assets/images/wallpaper1.jpg'
import wallpaper2 from '@/assets/images/wallpaper2.jpg'
import wallpaper3 from '@/assets/images/wallpaper3.jpg'

const categories = ref([
  { name: 'Category 1', image: wallpaper1 },
  { name: 'Category 2', image: wallpaper2 },
  { name: 'Category 3', image: wallpaper3 },
  { name: 'Category 4', image: wallpaper1 },
  { name: 'Category 5', image: wallpaper2 },
  { name: 'Category 6', image: wallpaper3 },
  { name: 'Category 7', image: wallpaper1 }
]);

const currentImageIndex = ref(0);
const selectedImage = ref(categories.value[0].image);

const searchText = ref('');

let intervalId: any;

const startImageInterval = () => {
  intervalId = setInterval(() => {
    nextImage();
  }, 5000);
};

const stopImageInterval = () => {
  clearInterval(intervalId);
};

const selectCategory = (index: number) => {
  stopImageInterval();
  currentImageIndex.value = index;
  selectedImage.value = categories.value[index].image;
  startImageInterval();
};

const nextImage = () => {
  currentImageIndex.value = (currentImageIndex.value + 1) % categories.value.length;
  selectedImage.value = categories.value[currentImageIndex.value].image;
};

const prevImage = () => {
  currentImageIndex.value = (currentImageIndex.value - 1 + categories.value.length) % categories.value.length;
  selectedImage.value = categories.value[currentImageIndex.value].image;
};

onMounted(() => {
  startImageInterval();
});

onUnmounted(() => {
  stopImageInterval();
});

</script>

<style scoped src="@/assets/styles/homeStyles.css">
</style>
