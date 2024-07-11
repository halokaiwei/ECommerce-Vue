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
            <button @click="toggleWishlist(item.id)">
                <img :src="wishlistIconClass" alt="Wishlist Icon">
            </button>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
import { useRoute } from 'vue-router';
import { useAuthStore } from '@/stores/useAuthStore';
import heartSolid from '@/assets/images/heart-solid-24.png';
import heartRegular from '@/assets/images/heart-regular-24.png';

const item = ref({
    id: 0,
    name: '',
    description: '',
    price: 0,
    images: []
});

const authStore = useAuthStore();
const userId = authStore.getUser?.id;
const currentImageIndex = ref(0);
const isInWishlist = ref(false);
const route = useRoute();

const itemId = ref<number | null>(null);

onMounted(() => {
    itemId.value = Number(route.params.id);
    if (itemId.value !== null) {
        getItem(itemId.value);
        checkWishlist(itemId.value);
    }
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

const checkWishlist = async (itemId: number) => {
    try {
        const response = await axios.get(`http://localhost/Server/checkWishlist.php`, { 
            params: { user_id: userId, item_id: itemId } 
        });
        if (response.data.success) {
            isInWishlist.value = response.data.isInWishlist;
        } else {
            console.error("response checkWishlist: "+response);
        }
    } catch (error) {
        console.error('error checkWishlist:', error);
    }
};

const toggleWishlist = async (itemId: number) => {
    const action = isInWishlist.value ? 'remove' : 'add';
    try {
        console.log("userId:", userId);
        console.log("itemId:", itemId);
        const response = await axios.post('http://localhost/Server/toggleWishlist.php', { 
            user_id: userId, item_id: itemId, action: action 
        }, {
            headers: {
                'Content-Type': 'application/json'
            }
        });
        console.log(response);
        if (response.data.success) {
            console.log(`${action}ed item to wishlist successfully`);
            isInWishlist.value = !isInWishlist.value;
        } else {
            console.error(response);
        }
    } catch (error) {
        console.error(`${action}ing item to wishlist failed:`, error);
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

const wishlistIconClass = computed(() => {
    return isInWishlist.value ? heartSolid : heartRegular;
});
</script>

<style scoped src="@/assets/styles/itemDetailStyles.css"></style>
