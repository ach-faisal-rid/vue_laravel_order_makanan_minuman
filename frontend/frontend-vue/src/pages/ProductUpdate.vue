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
            item: [],
            url: 'http://localhost:8000/public/storage/uploads/items/',
            productId: ''
        }
    },
    mounted() {
        this.productId = this.$route.params.productId
        this.userName = localStorage.getItem('name');
        this.roleId = localStorage.getItem('role_id');

        if (!this.userName || this.userName === '' || this.userName === null) {
            router.push({ name: 'login' });
        }
        if (this.roleId != 4) {
            router.push({ name: 'home' });
        }
        this.showItem()
    },
    methods: {
        showItem() {
            axios.get('/api/show-item/'+this.productId, {
                headers: {
                    "Authorization" : `Bearer ${localStorage.getItem('token')}`
                }
            })
            .then((response) => {
                this.items = response.data.data
            })
            .catch(function (error) {
                console.log(error);
                console.log('error fetch items')
            })
        }
    }
}
</script>
