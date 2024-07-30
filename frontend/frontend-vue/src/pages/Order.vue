<template>
    <div class="mt-5 container-fluid">
        <h1>Show Order Product</h1>
        <div class="row">
            <!-- Item list -->
            <div class="col-12 col-sm-8 mb-3 border rounded">
                <!-- Search box -->
                <div class="col-4 mt-2">
                    <input type="text" class="form-control" v-model="keyword" placeholder="Search here...!">
                </div>

                <hr/>

                <!-- Item list box -->
                <div class="col-12">
                    <div class="row">
                        <!-- Card box -->
                        <div v-for="item in filteredItems" :key="item.id" class="col-12 mt-3 col-sm-6 col-md-4 col-lg-3 mb-3">
                            <div class="card h-100">
                                <img :src="getItemImageUrl(item.image)" class="card-img-top object-fit-cover"
                                    :alt="item.name">
                                <div class="card-body">
                                    <h5 class="card-title">{{ item.name }}</h5>
                                    <p class="card-text">Rp. {{ item.price }}</p>
                                </div>
                                <div class="card-body">
                                    <a href="#" class="card-link">Card link</a>
                                    <a href="#" class="card-link">Another link</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Order item -->
            <div class="col-12 col-sm-4 mb-3 border rounded">
                adsada
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';

const items = ref([]);
const keyword = ref('');
const router = useRouter();
const requiredRoles = [1, 4]; // Role yang diizinkan mengakses halaman ini

const getItemImageUrl = (imageName) => {
    return `http://localhost:8000/storage/${imageName.replace('public/', '')}`;
};

const getItem = () => {
    axios.get('/api/item', {
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

<style scoped>
.card {
    width: 100%;
    max-width: 18rem;
}

.card-img-top {
    height: 200px;
    object-fit: cover;
}
</style>
