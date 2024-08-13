<template>
    <div class="container my-5">
        <h1>Product Update</h1>
        <div class="mb-3 col-lg-6">
            <form @submit.prevent="updateProduct">
                <div class="mb-3">
                    <label for="name">Name</label>
                    <input type="text" id="name" v-model="item.name" name="name" class="form-control"/>
                </div>
                <div class="mb-3">
                    <label for="price">Price</label>
                    <input type="number" id="price" v-model="item.price" name="price" class="form-control"/>
                </div>
                <div class="mb-3">
                    <label for="category">Category</label>
                    <input type="text" id="category" v-model="item.category" name="category" class="form-control"/>
                </div>
                <div class="mb-3">
                    <label for="image">Current Image</label>
                    <img v-if="item.image" :src="formattedImageUrl" class="object-fit-cover" style="width: 100px; height: 100px;">
                    <img v-else src="../../src/assets/images/no-image.png" class="object-fit-cover" style="width: 100px; height: 100px;">
                </div>
                <div class="mb-3">
                    <label for="image">New Image (Optional)</label>
                    <input type="file" @change="handleFileUpload($event)" id="image" class="form-control" >
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
            item: {
                name: '',
                price: '',
                category: '',
                image: ''
            },
            productId: '',
            file: null,
            url: 'http://localhost:8000/storage/',
        }
    },
    mounted() {
        this.productId = this.$route.params.productId;
        this.userName = localStorage.getItem('name');
        this.roleId = localStorage.getItem('role_id');

        if (!this.userName || this.userName === '' || this.userName === null) {
            router.push({ name: 'login' });
        }
        if (this.roleId != 4) {
            router.push({ name: 'Home' });
        }

        this.showItem();
    },
    computed: {
        formattedImageUrl() {
            if (this.item.image) {
                // Cek apakah URL gambar sudah mengandung url base, jika tidak, tambahkan url base
                return this.item.image.includes(this.url)
                    ? this.item.image
                    : this.url + this.item.image.replace('public/', '');
            } else {
                return '';
            }
        }
    },
    methods: {
        showItem() {
            axios.get('/api/show-item/' + this.productId, {
                headers: {
                    "Authorization": `Bearer ${localStorage.getItem('token')}`
                }
            })
            .then((response) => {
                this.item = response.data.data;
            })
            .catch((error) => {
                console.error('Error fetching item:', error);
            });
        },
        updateProduct() {
            if (this.item.name === '' || this.item.price === '') {
                alert('Data cannot be empty');
                return;
            }

            let formData = new FormData();
            formData.append('name', this.item.name);
            formData.append('price', this.item.price);
            formData.append('category', this.item.category);
            if (this.file) {
                formData.append('image', this.file); // Ubah dari 'image_file' ke 'image' sesuai backend
            }

            axios.post(`http://localhost:8000/api/update-item/${this.productId}`, formData, {
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('token')}`,
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then((response) => {
                console.log('Product updated:', response);
                router.push({ name: 'product' });
            })
            .catch((error) => {
                console.error('Error updating product:', error);
            });
        },
        handleFileUpload(event) {
            this.file = event.target.files[0];
            console.log('File selected:', this.file);
        }
    }
}
</script>
