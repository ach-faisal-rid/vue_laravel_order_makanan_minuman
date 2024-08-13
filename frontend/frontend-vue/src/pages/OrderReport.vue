<template>
    <div class="container my-5">
        <h1>Halaman Order Report</h1>
        <div class="input-field col s12 mb-3 w-50">
            <label for="month" class="form-label">Month</label>
            <select v-model="selectedMonth" @change="getReport" class="form-control" id="month">
                <option value="" disabled selected>Choose Month Period</option>
                <option v-for="month in months" :key="month.value" :value="month.value">
                    {{ month.name }}
                </option>
            </select>
        </div>

        <div class="col-12">
            <div class="row">
                <div class="col-12 col-sm-4">
                    <div class="box">
                        <label for="title">Order Count</label> <br />
                        <label for="">Rp. {{ data.orderCount }}</label>
                    </div>
                </div>
                <div class="col-12 col-sm-4">
                    <div class="box">
                        <label for="title">Max Payment</label> <br />
                        <label for="">Rp. {{ data.maxPayment }}</label>
                    </div>
                </div>
                <div class="col-12 col-sm-4">
                    <div class="box">
                        <label for="title">Min Payment</label> <br />
                        <label for="">Rp. {{ data.minPayment }}</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="my-5">
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
                    </tr>
                    <tr v-else>
                        <td colspan="9" class="text-center">No orders found.</td>
                    </tr>
                </tbody>
            </table>
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
            selectedMonth: '', // Menyimpan nilai bulan yang dipilih
            months: [
                { value: 1, name: 'January' },
                { value: 2, name: 'February' },
                { value: 3, name: 'March' },
                { value: 4, name: 'April' },
                { value: 5, name: 'May' },
                { value: 6, name: 'June' },
                { value: 7, name: 'July' },
                { value: 8, name: 'August' },
                { value: 9, name: 'September' },
                { value: 10, name: 'October' },
                { value: 11, name: 'November' },
                { value: 12, name: 'December' },
            ],
            orders: [], // Menyimpan data order yang didapat dari API
            data: {
                orderCount: 0,
                maxPayment: 0,
                minPayment: 0,
            },
        }
    },
    mounted() {
        this.userName = localStorage.getItem('name');
        this.roleId = localStorage.getItem('role_id');

        if (!this.userName || this.userName === '' || this.userName === null) {
            router.push({ name: 'login' });
        }
    },
    methods: {
        getReport() {
            axios.get(`api/order-report?month=` + this.selectedMonth, {
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('token')}`
                }
            })
            .then((response) => {
                console.log('Orders Data:', response.data.data); // Logging data orders
                this.orders = response.data.data.orders;
                this.data.orderCount = response.data.data.orderCount;
                this.data.maxPayment = response.data.data.maxPayment;
                this.data.minPayment = response.data.data.minPayment;
            })
            .catch((error) => {
                console.error('Error fetching orders:', error);
            });
        },
        formatDate(date) {
            return new Date(date).toLocaleDateString();
        },
        formatTime(date) {
            return new Date(date).toLocaleTimeString();
        }
    }
}
</script>

<style scoped>
.container {
    max-width: 1200px;
    margin: auto;
}
.box {
    background: #f5f5f5;
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 20px;
    text-align: center;
}
.table {
    width: 100%;
}
</style>
