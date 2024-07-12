<template>
    <div class="item-container">
        <ul class="item-list">
            <li v-for="item in items" :key="item.item_id" class="item-card" @click="goToItemDetail(item.item_id)">
                <div class="item-image">
                    <img v-if="item.images.length > 0" :src="item.images[0]" alt="Item Image" />
                </div>
                <div class="item-details">
                    <h4>{{ item.item_name }}</h4>
                    <p>Price: {{ item.price }}</p>
                </div>
            </li>
        </ul>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useAuthStore } from '@/stores/useAuthStore';
import router from '@/router';

interface Item {
    user_id: number;
    item_id: number;
    item_name: string;
    price: number;
    images: string[];
}

const items = ref<Item[]>([]);

const authStore = useAuthStore();
const userId = authStore.getUser?.id;

onMounted(() => {
    if (userId) {
        getItems(userId);
    }
});

const getItems = async (userId: number) => {
    try {
        const response = await axios.get('http://localhost/Server/getWishlistItems.php', {
            params: { user_id: userId }
        });
        if (response.data.success) {
            items.value = response.data.items;
        } else {
            console.error(response.data.message);
        }
    } catch (error) {
        console.error('Error during getItems:', error);
    }
};

const goToItemDetail = (itemId: number) => {
    router.push({ path: `/item/${itemId}` });
};
</script>

<style scoped>
.item-container {
    margin: 0 auto;
}

.item-list {
    display: flex;
    flex-wrap: wrap;
    margin: 0 auto;
    list-style-type: none;
    padding: 0;
    width: 80%;
}

.item-card {
    width: calc(20% - 20px);
    margin-right: 1rem;
    margin-bottom: 1rem;
    height: auto;
    background-color: #f0f0f0;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
    overflow: hidden;
    position: relative;
}

.item-card:first-child,
.item-card:nth-child(5n + 6) {
    margin-left: 1rem;
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

@media (max-width: 1200px) {
    .item-card {
        width: calc(25% - 20px);
    }

    .item-card {
        margin-left: 0;
        margin-right: 0;
    }

    .item-card:first-child,
    .item-card:nth-child(5n + 6) {
        margin-left: 0rem;
    }
}

@media (max-width: 992px) {
    .item-card {
        width: calc(33.33% - 20px);
    }
}

@media (max-width: 767px) {
    .item-card {
        width: calc(50% - 20px);
    }
}

@media (max-width: 576px) {
    .item-card {
        width: calc(100% - 20px);
        margin-left: 0;
        margin-right: 0;
    }
}

@media (max-width: 500px) {
    #banner {
        margin: 0;
        width: 100%;
    }

    .home-container {
        width: 100%;
        flex-direction: column;
    }

    .navigation {
        width: 100%;
        box-shadow: none;
        margin-bottom: 20px;
    }

    .content {
        width: 100%;
    }
}
</style>