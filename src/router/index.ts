import SignInForm from '@/components/Authentication/SignInForm.vue'
import SignUpForm from '@/components/Authentication/SignUpForm.vue'
import Profile from '@/components/myProfile.vue'
import Wishlist from '@/components/Wishlist.vue'
import Home from '@/components/Home.vue'
import { createRouter, createWebHistory } from 'vue-router'

 const router = createRouter({
    history:createWebHistory(),
    routes: [
        {
            name:'SignIn',
            path:'/sign-in',
            component:SignInForm
        },
        {
            name:'SignUp',
            path:'/sign-up',
            component:SignUpForm
        },
        {
            name:'Profile',
            path:'/profile',
            component:Profile
        },
        {
            name:'Wishlist',
            path:'/wishlist',
            component:Wishlist
        },
        {
            name:'Settings',
            path:'/settings',
            component:Wishlist
        },
        {
            name:'Home',
            path:'/home',
            component:Home
        },



    ]
})

export default router