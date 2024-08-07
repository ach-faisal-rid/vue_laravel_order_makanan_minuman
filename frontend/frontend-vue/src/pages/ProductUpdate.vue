<template>
    <div class="my-5">
        <h1>borneo anjing</h1>
    </div>
</template>

<script>
import router from '../router';
import axios from 'axios';

export default {
    data() {
        return {
            userName: '',
            roleId: '',
            items: [],
            url: 'http://localhost:8000/public/storage/uploads/items/',
        }
    },
    mounted() {
        this.userName = localStorage.getItem('name');
        this.roleId = localStorage.getItem('role_id');

        if (!this.userName || this.userName === '' || this.userName === null) {
            router.push({ name: 'login' });
        }
        if (this.roleId != 4) {
            router.push({ name: 'home' });
        }
    },
}
</script>
