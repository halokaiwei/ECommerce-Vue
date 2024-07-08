<!-- src/components/Header.vue -->
<script setup lang="ts">
import { computed } from 'vue';
import { RouterLink, useRouter } from 'vue-router';
import comp_logo from '@/assets/images/crayon-shinchan.jpg';
import user_logo from '@/assets/images/user-logo.png';
import { useAuthStore } from '@/stores/useAuthStore';

const authStore = useAuthStore();
const isAuthenticated = computed(() => authStore.isAuthenticated);
const getUser = computed(() => authStore.getUser);
const isUser = computed(() => getUser.value?.role=== 'user');
const isMerchant = computed(() => getUser.value?.role === 'merchant');
const router = useRouter();

const categories = [
  { name: 'Lifestyle', path: '../horizontal/itemList_nature.php' },
  { name: 'Electric Gadgets', path: '../horizontal/itemList_animal.php' },
  { name: 'Fashion', path: '../horizontal/itemList_disney.php' },
  { name: 'For Pets', path: '../horizontal/itemList_car.php' },
  { name: 'Foods', path: '../horizontal/itemList_food.php' },
  { name: 'Services', path: '../horizontal/itemList_doodle.php' },
];

const logout = () => {
  authStore.logout();
};
</script>

<template>
    <div id="header">     
                <div id="compLogo"> 
                    <router-link :to="{name:'Home'}"><img :src="comp_logo" id="comp-Logo" alt="Company Logo"/> </router-link>
                </div>
                <ul id="headerNav">
                    <li v-if="isAuthenticated" id="Login"><router-link :to="{name:'Profile'}">PROFILE</router-link></li>
                    <li v-if="isAuthenticated && isUser" id="Wishlist">
                        <router-link :to="{name:'Wishlist'}">WISHLIST</router-link>    
                    </li>
                    <li v-if="isAuthenticated && isUser" class="Settings">
                        <router-link :to="{name:'Settings'}">SETTINGS</router-link>
                    </li>
                    <li v-if="isAuthenticated && isMerchant" id="myProducts">
                        <router-link to="/Profile">MY PRODUCTS</router-link>
                        
                    </li>
                    <li v-if="isAuthenticated && isMerchant" class="Settings">
                        <router-link to="/Profile">SETTINGS</router-link>
                    </li>
                    <li v-if="isAuthenticated" id="Logout">
                        <button @click="logout">LOG OUT</button>
                    </li>
                    <li v-else>
                        <router-link :to="{ name: 'SignIn' }">LOG IN</router-link>
                    </li>
                </ul>
        </div>
        <!-- <div class="nav">
            <div class="nav-item">
                <img :src="user_logo" alt="">
                <div class="nav-submenu">
                    <?php if(isset($_SESSION['loggedIn']) && $_SESSION['loggedIn']): ?>
                        <a href="../Upload/index.php"> <?php echo $_SESSION['userN']; ?></a>
                    <?php else: ?>
                        <a href="../signIn/index.php">Log in</a>
                    <?php endif; ?>
                    <?php if(isset($_SESSION['loggedIn']) && $_SESSION['loggedIn']): ?>
                        <a href="../wishList/index.php">WishList</a>
                    <?php else: ?>
                    <?php endif; ?>
                    <a href="#">Settings</a> 
                    <?php if(isset($_SESSION['loggedIn']) && $_SESSION['loggedIn']): ?>
                        <a href="#"><button @click="logout"></button>Log out</a>
                    <?php endif; ?> 
                </div>
            </div>
        </div> -->
</template>

<style scoped src="@/assets/styles/headerStyles.css">
</style>
