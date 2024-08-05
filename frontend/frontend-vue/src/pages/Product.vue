<template>
    <div class="container">
        <h1 class="my-5">halaman product</h1>

        <!-- Search box -->
        <div class="col-4 mt-2">
            <input type="text" class="form-control" v-model="keyword" placeholder="Search here...!">
        </div>

        <table class="table table-striped my-5">

            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Price</th>
                    <th scope="col">Image</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(item, index) in filteredItems" :key="item.id">
                    <th scope="row">{{ index + 1 }}</th>
                    <td>{{ item.name }}</td>
                    <td>Rp. {{ item.price }}</td>
                    <td>
                        <img :src="getItemImageUrl(item.image)" alt="Product Image" width="150">
                    </td>
                    <td>
                        <a class="btn btn-primary" href="#">edit</a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';

const items = ref([]);
const keyword = ref('');
const router = useRouter();
const requiredRoles = [4]; // Role yang diizinkan mengakses halaman ini

const getItemImageUrl = (imageName) => {
    return `http://localhost:8000/storage/${imageName.replace('public/', '')}`;
};

const getItem = () => {
    axios.get('/api/index-item', {
        headers: {
            'Authorization': `Bearer ${localStorage.getItem('token')}`
        }
    })
        .then((response) => {
            items.value = response.data.data;
        })
        .catch((error) => {
            console.log(error);
            console.log('Error fetching items');
        });
};

// Computed property untuk memfilter item berdasarkan kata kunci pencarian
const filteredItems = computed(() => {
    return items.value.filter(item =>
        item.name.toLowerCase().includes(keyword.value.toLowerCase())
    );
});

onMounted(() => {
    const userRole = localStorage.getItem('role_id');

    if (!userRole || !requiredRoles.includes(Number(userRole))) {
        router.push({ name: 'home' }); // Redirect jika role tidak sesuai
    } else {
        getItem();
    }
});

</script>
