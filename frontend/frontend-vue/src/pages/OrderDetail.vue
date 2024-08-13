<template>
    <div class="container mt-5">
        <div class="table-responsive mt-5">
            <table class="table striped highlight">
                <tbody>
                    <tr>
                        <td>Customer Name : {{ order.customer_name }}</td>
                        <td>Table No : {{ order.table_no }}</td>
                        <td>Order Date : {{ formatDate(order.created_at) }}</td>
                        <td>Status : {{ order.status }}</td>
                    </tr>
                    <tr>
                        <td>Waitress : {{ order.waitress ? order.waitress.name : "-" }}</td>
                        <td>Chasier : {{ order.chasier ? order.chasier.name : "-" }}</td>
                        <td>Order Time : {{ formatTime(order.created_at) }}</td>
                        <td>Grand Total : Rp. {{ order.total }}</td>
                    </tr>
                </tbody>
            </table>

            <table class="table striped highlight">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Item Name</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, index) in order.order_status" :key="index">
                        <td>{{ index + 1 }}</td>
                        <td>{{ item.item.name }}</td>
                        <td>Rp. {{ item.price }}</td>
                        <td>{{ item.qty }}</td>
                        <td>Rp. {{ item.price * item.qty }}</td>
                    </tr>
                </tbody>
            </table>

            <div class="mt-3">
                <!-- Show "Done" button if user is chef and order status == "ordered" -->
                <button v-if="(order.status === 'ordered') && (roleId === '2')" class="btn btn-primary"
                    @click="setAsDone(order.id)">Done</button>

                <!-- Show "Paid" button if user is cashier or manager and order status == "done" -->
                <button v-if="(order.status === 'done') && (roleId === '3' || roleId === '4')"
                    class="btn btn-success" @click="setAsPaid(order.id)">Paid</button>
            </div>
        </div>
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
            order: {}
        }
    },
    mounted() {
        this.userName = localStorage.getItem('name');
        this.roleId = localStorage.getItem('role_id');

        if (!this.userName || this.userName === '' || this.userName === null) {
            router.push({ name: 'login' });
        }
        this.getOrder();
    },
    methods: {
        getOrder() {
            axios.get(`api/detail-order/` + this.$route.params.orderId, {
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('token')}`
                }
            })
            .then((response) => {
                this.order = response.data.data;
            })
            .catch((error) => {
                console.error('Error fetching order:', error);
            });
        },
        formatDate(dateTime) {
            return new Date(dateTime).toLocaleDateString();
        },
        formatTime(dateTime) {
            return new Date(dateTime).toLocaleTimeString();
        },
        setAsDone(orderId) {
            axios.get(`/api/done-order/` + orderId + `/set-as-done`, {
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('token')}`
                }
            })
            .then((response) => {
                if (response.status === 200) {
                    alert('Update order status to "done" was successful');
                    this.getOrder(); // Refresh order data
                }
            })
            .catch((error) => {
                console.error('Error updating order status to "done":', error);
            });
        },
        setAsPaid(orderId) {
            axios.get(`/api/pay-order/` + orderId + `/payment`, {
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('token')}`
                }
            })
            .then((response) => {
                if (response.status === 200) {
                    alert('Update order status to "paid" was successful');
                    this.getOrder(); // Refresh order data
                }
            })
            .catch((error) => {
                console.error('Error updating order status to "paid":', error);
            });
        }
    }
}
</script>

<style scoped></style>
