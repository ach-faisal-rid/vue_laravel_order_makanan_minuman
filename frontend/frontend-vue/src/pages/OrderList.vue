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
                    <th scope="col">waitress</th>
                    <th scope="col">Status</th>
                    <th scope="col">chasier</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(order, index) in orders" :key="order.id">
                    <th scope="row">{{ index + 1 }}</th>
                    <td>{{ order.customer_name }}</td>
                    <td>{{ order.total }}</td>
                    <td>{{ formatDate(order.created_at) }}</td>
                    <td>{{ formatTime(order.created_at) }}</td>
                    <td>{{ order.waitress.name }}</td>
                    <td>{{ order.status }}</td>
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

        if (!this.userName || this.userName == '' || this.userName == null) {
            router.push({ name: 'login' });
        }
        this.getOrders();
    },
    methods: {
        getOrders() {
            axios.get('api/detail-order', {
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('token')}`
                }
            })
            .then((response) => {
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

<style scoped>
/* Style for table or any other custom styling can be added here */
</style>
