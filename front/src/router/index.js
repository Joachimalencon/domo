import {createRouter, createWebHistory} from 'vue-router';
import Home from '../views/Home.vue';
import Login from '../views/Login.vue';
import WaitingApproval from '../views/WaitingApproval.vue';
import {useAuthStore} from "@/stores/auth.js";

const routes = [
    {
        path: '/',
        name: 'Home',
        component: Home,
        meta: {requiresAuth: true},
    },
    {
        path: '/login',
        name: 'Login',
        component: Login,
        meta: {requiresAuth: false},
    },
    {
        path: '/waiting-approval',
        name: 'WaitingApproval',
        component: WaitingApproval,
        meta: {requiresAuth: true},
    }
];

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes,
});

router.beforeEach((to, from, next) => {
    const authStore = useAuthStore();
    if (to.meta.requiresAuth && !authStore.isAuthenticated) {
        next({path: '/login'});
    } else if (to.meta.requiresAuth && !authStore.isApproved && to.name !== 'WaitingApproval') {
        console.log('test');
        next({path: '/waiting-approval'});
    } else {
        next();
    }
});

export default router;
