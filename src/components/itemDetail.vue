<template>
    <div class="item-detail-container">
        <div class="item-images">
            <div class="image-controls">
                <button @click="prevImage">&lt;</button>
                <button @click="nextImage">&gt;</button>
            </div>
            <div class="image-wrapper" :style="{ transform: `translateX(-${currentImageIndex * 100}%)` }">
                <img v-for="(image, index) in item.images" :key="index" :src="image" alt="Item Image" />
            </div>
        </div>
        <div class="item-content">
            <h2>{{ item.name }}</h2>
            <p>{{ item.description }}</p>
            <p>Price: {{ item.price }}</p>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
import { useRoute } from 'vue-router';

const item = ref({
    id: 0,
    name: '',
    description: '',
    price: 0,
    images: []
});

const currentImageIndex = ref(0);
const route = useRoute();

onMounted(() => {
    const itemId = Number(route.params.id);
    getItem(itemId);
});

const getItem = async (itemId: number) => {
    try {
        const response = await axios.get(`http://localhost/Server/getItem.php?id=${itemId}`);
        if (response.data.success) {
            console.log('itemDetail response:', response);
            item.value = response.data.item;
        } else {
            console.error(response.data.message);
        }
    } catch (error) {
        console.error('Error during getItem:', error);
    }
};

const nextImage = () => {
    currentImageIndex.value = (currentImageIndex.value + 1) % item.value.images.length;
};

const prevImage = () => {
    currentImageIndex.value = (currentImageIndex.value - 1 + item.value.images.length) % item.value.images.length;
};

const currentImage = computed(() => {
    return item.value.images[currentImageIndex.value] || '';
});
</script>

<style scoped src="@/assets/styles/itemDetailStyles.css"></style>