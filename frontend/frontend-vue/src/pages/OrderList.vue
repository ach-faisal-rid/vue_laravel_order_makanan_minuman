<template>
    <div class="container mt-5">
        <h1>Order List</h1>

        <table class="table table-striped highlight responsive-table mt-5">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Customer Name</th>
                    <th scope="col">Total</th>
                    <th scope="col">Order Date</th>
                    <th scope="col">Order Time</th>
                    <th scope="col">Waitress</th>
                    <th scope="col">Status</th>
                    <th scope="col">Cashier</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <tr v-if="orders.length" v-for="(order, index) in orders" :key="order.id">
                    <th scope="row">{{ index + 1 }}</th>
                    <td>{{ order.customer_name }}</td>
                    <td>{{ order.total }}</td>
                    <td>{{ formatDate(order.created_at) }}</td>
                    <td>{{ formatTime(order.created_at) }}</td>
                    <td>{{ order.waitress ? order.waitress.name : '-' }}</td>
                    <td>{{ order.status }}</td>
                    <td>{{ order.cashier ? order.cashier.name : '-' }}</td>
                    <td>
                        <RouterLink class="btn btn-primary" :to="{name: 'orderDetail', params: {orderId: order.id}}">View Detail</RouterLink>
                    </td>
                </tr>
                <tr v-else>
                    <td colspan="9" class="text-center">No orders found.</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
import axios from 'axios';
import router from '../router';

export default {
    data() {
        return {
            userName: '',
            roleId: '',
            orders: [],
        }
    },
    mounted() {
        this.userName = localStorage.getItem('name')
        this.roleId = localStorage.getItem('role_id')

        if (!this.userName || !this.roleId) {
            router.push({ name: 'login' });
        } else {
            this.getOrders();
        }
    },
    methods: {
        getOrders() {
            axios.get('api/detail-order', {
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('token')}`
                }
            })
            .then((response) => {
                console.log('Orders Data:', response.data.data); // Logging data orders
                this.orders = response.data.data;
            })
            .catch((error) => {
                console.error('Error fetching orders:', error);
            });
        },
        formatDate(dateTime) {
            return new Date(dateTime).toLocaleDateString();
        },
        formatTime(dateTime) {
            return new Date(dateTime).toLocaleTimeString();
        }
    }
}
</script>

<style scoped></style>
