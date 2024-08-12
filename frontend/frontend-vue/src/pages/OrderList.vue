<template>
    <div class="container mt-5">
        <h1>order list</h1>

        <table class="table mt-5">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Customer Name</th>
                    <th scope="col">Total</th>
                    <th scope="col">order date</th>
                    <th scope="col">order time</th>
                    <th scope="col">Status</th>
                </tr>
            </thead>
            <tbody>

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
        }
    },
    mounted() {
        this.userName = localStorage.getItem('name')
        this.roleId = localStorage.getItem('role_id')

        if (!this.userName || this.userName == '' || this.userName == null) {
            router.push({ name: 'login' });
        }
        this.getOrders()
    },
    methods: {
        getOrders() {
            alert('test');
        }
    }
}
</script>

<style></style>
