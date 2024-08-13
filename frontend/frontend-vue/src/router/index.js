import { createRouter, createWebHistory } from "vue-router";
import Home from "../pages/Home.vue"
import Login from "../pages/Login.vue"
import Register from "../pages/Register.vue"
import Order from "../pages/Order.vue"
import Product from "../pages/Product.vue"
import ProductUpdate from "../pages/ProductUpdate.vue"
import ProductAdd from "../pages/ProductAdd.vue"
import OrderList from "../pages/OrderList.vue"
import OrderDetail from "../pages/OrderDetail.vue"

const routes = [
    {
        path: '/',
        name: 'Home',
        component: Home
    },
    {
        path: '/login',
        name: 'Login',
        component: Login
    },
    {
        path: '/register',
        name: 'Register',
        component: Register
    },
    {
        path: '/order',
        name: 'Order',
        component: Order,
        meta: { requiresRole: [1, 4] } // Role yang diizinkan untuk mengakses halaman ini
    },
    {
        path: '/product',
        name: 'Product',
        component: Product,
        meta: { requiresRole: [4] } // Role yang diizinkan untuk mengakses halaman ini
    },
    {
        path: '/product/:productId',
        name: 'productUpdate',
        component: ProductUpdate,
        meta: { requiresRole: [4] } // Role yang diizinkan untuk mengakses halaman ini
    },
    {
        path: '/product-add',
        name: 'productAdd',
        component: ProductAdd,
        meta: { requiresRole: [4] } // Role yang diizinkan untuk mengakses halaman ini
    },
    {
        path: '/order-list',
        name: 'orderList',
        component: OrderList,
    },
    {
        path: '/order/:orderId',
        name: 'orderDetail',
        component: OrderDetail
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

// Route Guard
router.beforeEach((to, from, next) => {
    const userRole = localStorage.getItem('role_id');

    if (to.meta.requiresRole) {
        if (!userRole || !to.meta.requiresRole.includes(Number(userRole))) {
            next({ name: 'home' }); // Redirect jika role tidak sesuai
        } else {
            next(); // Akses diizinkan
        }
    } else {
        next(); // Akses diizinkan jika tidak ada role yang dibutuhkan
    }
});

export default router;
