import { createRouter, createWebHistory } from "vue-router";
import Home from "../pages/Home.vue";
import Login from "../pages/Login.vue";
import Register from "../pages/Register.vue";
import Order from "../pages/Order.vue";
import Product from "../pages/Product.vue";
import ProductUpdate from "../pages/ProductUpdate.vue";
import ProductAdd from "../pages/ProductAdd.vue";
import OrderList from "../pages/OrderList.vue";
import OrderDetail from "../pages/OrderDetail.vue";

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
        meta: { requiresRole: [1, 2, 3, 4], canUpdate: false }
    },
    {
        path: '/order/:orderId',
        name: 'orderDetail',
        component: OrderDetail,
        meta: { requiresRole: [1, 2, 3, 4], canUpdate: true }
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

// Route Guard
router.beforeEach((to, from, next) => {
    const userRole = Number(localStorage.getItem('role_id')); // Convert role_id to Number
    console.log("User Role:", userRole); // Logging role ID

    if (to.meta.requiresRole) {
        console.log("Required Roles:", to.meta.requiresRole); // Logging required roles
        if (!userRole || !to.meta.requiresRole.includes(userRole)) {
            console.log("Redirecting to Home");
            next({ name: 'Home' }); // Redirect if role does not match
        } else {
            if (to.meta.canUpdate && ![1, 2, 3, 4].includes(userRole)) {
                console.log("Redirecting to Order List");
                next({ name: 'orderList' }); // Redirect to order list if user can't update
            } else {
                console.log("Access allowed");
                next(); // Access allowed
            }
        }
    } else {
        console.log("No role required, access allowed");
        next(); // Access allowed if no role required
    }
});

export default router;
