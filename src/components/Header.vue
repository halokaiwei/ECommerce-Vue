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
a {
    text-decoration: none;
    color:black;
}
#header {
    position: sticky;
    margin:0 auto;
    top: 0.08rem;
    width: 80%;
    height: 3.5rem;
    border-radius: 0.30rem;
    background-color: rgba(238, 238, 238, 0.3);
    z-index:5;
}
#header #compLogo {
    float: left;
    width: 8%;
    height: 100%;
    display: flex; /* Use flexbox for centering */
    justify-content: center; /* Horizontally center the content */
    align-items: center; /* Vertically center the content */
}

#header #compLogo img {
    border-radius: 50%;
    width: 3rem;
    height: 2.5rem;
}

#header #compLogo img:hover {
    cursor:pointer;
}
#header #headerNav {
    float:right;
    display:block;
}
#header #headerNav > li {
    float:left;
    margin-right:0.7rem;
    padding: 0rem 2rem;
    list-style: none;
    font-size:1rem;
    border: 0.0133rem black solid;
    border-radius: 20px;
    cursor:pointer;
}

/* #header #headerNav #Profile ul {
    display: none; 
    position: absolute;
    top: 100%; 
    left: -2.5rem;
    width: 100%;
} */
/* #header #headerNav #Categories:hover ul {
    display: block;
}
#header #headerNav #Categories ul li {
    margin: 0;
    padding: 0.5rem 2rem;
    background-color: rgba(238, 238, 238, 0.4);
    border-radius: 1rem;
    border: 1px solid black;
    list-style: none;
    text-align: center;
    opacity: 0; 
    animation: fade-in 0.5s ease forwards;
}

#header #headerNav #Categories ul li:hover {
    cursor:pointer;
    background-color: rgba(188, 238, 238, 0.3);
}

#header #headerNav #Categories ul li:nth-child(1)  {
    animation-delay: 0.1s; 
}

#header #headerNav #Categories ul li:nth-child(2) {
    animation-delay: 0.2s;
}
#header #headerNav #Categories ul li:nth-child(3) {
    animation-delay: 0.3s;
}
#header #headerNav #Categories ul li:nth-child(4) {
    animation-delay: 0.4s;
}
#header #headerNav #Categories ul li:nth-child(5) {
    animation-delay: 0.5s;
}
#header #headerNav #Categories ul li:nth-child(6) {
    animation-delay: 0.6s;
} */
/* @keyframes fade-in {
    from {
        opacity: 0;
        transform: translateY(-1rem);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
} */
@media screen and (max-width: 875px) {
    #header #headerNav .Settings{
        display: none;
    }
}

@media screen and (max-width: 700px) {
    #header #headerNav #Logout {
        display: none;
    }
}

/* Media query to hide #Categories */
@media screen and (max-width: 579px) {
    #header #headerNav #Login {
        display: none;
    }
}

/* Media query to only show #Login */
@media screen and (max-width: 414px) {
    #header #headerNav #Login {
        display: block;
    }
    #header #headerNav li:not(#Login) {
        display: none;
    }
}

@media screen and (max-width: 300px) {
    #header #headerNav #Login {
        display: none;
    }
}
.nav {
    position: fixed;
    top: 15%;
    right: 0;
    transform: translateY(-50%);
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    color: #fff;
    z-index:5;
}
.nav img {
    width: 0.5rem;
    height: 0.6rem;
    border-radius: 50%;
}
.nav-item {
    position: relative;
    padding: 0 20px;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    cursor: pointer;
}
.nav-submenu {
    display: none;
    position: absolute;
    top: 100%;
    right: 0.5rem;
    z-index: 5;
    opacity: 0; 
    animation: fade-in 0.5s ease forwards;
}
.nav .nav-item:hover .nav-submenu {
    display: block;
}
.nav-submenu a {
    display: block;
    padding: 10px;
    background-color: rgba(238, 238, 238, 0.8);
    color: black;
    text-align: center;
    text-decoration: none;
}
.nav-submenu a:nth-child(1) {
    animation-delay:0.1s;
}
.nav-submenu a:nth-child(2) {
    animation-delay:0.2s;
}
.nav-submenu a:nth-child(3) {
    animation-delay:0.3s;
}
.nav-submenu a:nth-child(4) {
    animation-delay:0.4s;
    border-bottom-right-radius: 15px;
    border-bottom-left-radius: 15px;
}
.nav-submenu a:hover {
    background-color: rgba(188, 238, 238, 0.8);
}




</style>
