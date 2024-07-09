<template>
      <!-- Item container -->
      <div class="item-container">
      <ul class="item-list">
        <li v-for="item in items" :key="item.id" class="item-card">
          <div class="item-image">
            <img :src="item.images" alt="Item Image" />
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
import {ref,onMounted} from 'vue'
import axios from 'axios';
import {type items} from '@/types/index'

const items = ref<items[]>([]);

onMounted(() => {
  getItems();
});

const getItems = async() => {
  try {
    const response = await axios.get('http://localhost/Server/getItems.php');
    if (response.data.success) {
          items.value = response.data.items;
          console.log("Success getting items: ", response);
      } else {
          console.error(response.data.message);
        }
      } catch (error) {
      console.error('Error during getItems:', error);
    }
}

</script>
<style>
  .item-container {
    margin:0 auto;
  }
  
  @media screen and (max-width: 1200px) {
    .item-list {
      width: 80%;
    }
  }
  
  @media screen and (max-width: 992px) {
    .item-list {
      width: 80%;
    }
  }
  
  @media screen and (max-width: 767px) {
    .item-list {
      width: 80%;
    }
  }
  
  @media screen and (max-width: 576px) {
    .item-list {
      width: 100%;
    }
  }
  
  @media screen and (max-width: 500px) {
    .item-list {
      width: 100%;
    }
  }
  
  @media screen and (max-width: 414px) {
    .item-list {
      width: 100%;
    }
  }
  
  @media screen and (max-width: 300px) {
    .item-list {
      width: 100%;
    }
  }
  .item-list {
    display: flex;
    flex-wrap: wrap;
    margin:0 auto;
    list-style-type: none;
    padding: 0;
    width: 80%;
  }
  
  .item-card {
    width: calc(20% - 20px); /* 20% width with margin */
    margin-right: 1rem;
    margin-bottom: 1rem;
    height: 20rem;
    background-color: #f0f0f0;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
    overflow: hidden;
    position: relative;
  }

  .item-card:first-child, .item-card:nth-child(5n + 6) {
    margin-left: 1rem; /* Apply margin-left only to the first item */
  }
  
  .item-card:hover {
    border-color: #007bff;
    cursor: pointer;
  }
  
  .item-image img {
    width: 100%;
    height: auto;
    display: block;
  }
  
  .item-details {
    padding: 10px;
  }
  
</style>