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
                    <input type="file" name="image" @change="handleFileUpload($event)" id="image" class="form-control"/>
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
            category: '',
            file: null // Perubahan dari '' menjadi null untuk file
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
    methods: {
        createProduct() {
            if (this.name === '' || this.price === '' || this.category === '') {
                alert('Data cannot be empty');
                return;
            }

            let formData = new FormData(); // Perubahan dari formData menjadi FormData
            formData.append('name', this.name);
            formData.append('price', this.price);
            formData.append('category', this.category);
            formData.append('image_file', this.file); // Perubahan 'image_file' menjadi 'image'

            axios.post('http://localhost:8000/api/create-item', formData, {
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('token')}`,
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then(response => {
                console.log(response);
                router.push({name: 'product'})
                // Redirect atau tindakan lain setelah berhasil
            })
            .catch(error => {
                console.log(error);
                console.log('Error creating product');
            });
        },
        handleFileUpload(event) {
            let file = event.target.files[0];
            this.file = file;
            console.log(file);
        }
    }
}
</script>
