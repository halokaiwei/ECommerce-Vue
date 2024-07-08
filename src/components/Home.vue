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
              {{ category }}
            </li>
          </ul>
        </div>
          <div class="content">
            <transition name="fade"><img :src="selectedImage" alt="Example" /></transition>
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
  
  // Banner images
  onMounted(() => {
    startImageInterval();
  })
  const categories = ['Category 1', 'Category 2', 'Category 3','Category 4','Category 5','Category 6','Category 7'];
  const images = [
      wallpaper1,wallpaper2,wallpaper3
  ];
  const selectedImage = ref(images[0]);

  function selectCategory(index:number) {
      selectedImage.value = images[index];
  }

  const searchText = ref('');
  
  const wallpapers = ref([]);
  
  let intervalId;

  const startImageInterval = () => {
    let currentIndex = 0;
    intervalId = setInterval(() => {
      currentIndex = (currentIndex + 1) % images.length;
      selectedImage.value = images[currentIndex];
  }, 5000); // 5000 milliseconds = 5 seconds
};

  // Fetch wallpapers data
  // const fetchWallpapers = () => {
  //   // Perform AJAX request to fetch wallpapers data (simulate PHP logic)
  //   // Replace this with actual AJAX request using Axios or fetch API in real scenario
  //   wallpapers.value = [
  //     { id: 1, image: 'data:image/jpeg;base64,...' }, // Replace '...' with actual base64 encoded image data
  //     { id: 2, image: 'data:image/jpeg;base64,...' },
  //     { id: 3, image: 'data:image/jpeg;base64,...' },
  //     // Add more wallpapers as needed
  //   ];
  // };
  
  
  // Search wallpapers function
  const searchItems = () => {
    console.log('Searching for:', searchText.value);
  };
  
  
  </script>
  
  <style scoped src="@/assets/styles/homeStyles.css">
</style>
    