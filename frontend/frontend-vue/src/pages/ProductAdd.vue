<template>
    <div class="container my-5">
        <h1>Add Product</h1>
        <div class="mb-3 col-lg-6">
            <form @submit.prevent="createProduct">
                <div class="mb-3">
                    <label for="name">Name</label>
                    <input type="text" id="name" v-model="name" name="name" class="form-control"/>
                </div>
                <div class="mb-3">
                    <label for="price">Price</label>
                    <input type="number" id="price" v-model="price" name="price" class="form-control"/>
                </div>
                <div class="mb-3">
                    <label for="category">Category</label>
                    <input type="text" id="category" v-model="category" name="category" class="form-control"/>
                </div>
                <div class="mb-3">
                    <label for="image">Image</label>
                    <input type="file" name="image" @change="handleFileUpload" id="image" class="form-control"/>
                </div>
                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
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
            name: '',
            price: '',
            category: ''
        }
    },
    mounted() {
        this.userName = localStorage.getItem('name')
        this.roleId = localStorage.getItem('role_id')

        if(!this.userName || this.userName == '' || this.userName == null) {
            router.push({name: 'login'})
        }
        if (this.roleId != 4) {
            router.push({name: 'home'})
        }
    },
    methods: {
        createProduct() {
            axios.post('http://localhost:8000/api/create-item', {
                name: this.name,
                price: this.price,
                category: this.category
            }, {
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('token')}`
                }
            })
            .then((response => {
                console.log(response)
            }))
            .catch(function (error) {
                console.log(error);
                console.log('error fetch items')
            })
        }
    }
}
</script>
