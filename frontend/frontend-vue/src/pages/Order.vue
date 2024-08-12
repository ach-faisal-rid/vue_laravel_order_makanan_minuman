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

                <hr />

                <!-- Item list box -->
                <div class="col-12">
                    <div class="row">
                        <!-- Card box -->
                        <div v-for="item in filteredItems" :key="item.id"
                            class="col-12 mt-3 col-sm-6 col-md-4 col-lg-3 mb-3">
                            <div class="card h-100">
                                <img :src="getItemImageUrl(item.image)" class="card-img-top object-fit-cover"
                                    :alt="item.name">
                                <div class="card-body">
                                    <h5 class="card-title">{{ item.name }}</h5>
                                    <p class="card-text">Rp. {{ item.price }}</p>
                                </div>
                                <div class="card-body">
                                    <a href="#" @click="addToOrder(item)" class="btn btn-warning card-link">order</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Order item -->
            <div class="col-12 col-sm-4 mb-3 border rounded p-3">
                <h2 class="text-center">Order List</h2>
                <hr>
                <div v-if="orderItems.length === 0" class="text-center">No items ordered</div>
                <div v-else>
                    <div v-for="order in orderItems" :key="order.item.id" class="d-flex justify-content-between">
                        <span>{{ order.item.name }} ({{ order.quantity }})</span>
                        <span>Rp. {{ order.item.price * order.quantity }}</span>
                        <button @click="removeFromOrder(index)" class="btn btn-danger btn-sm">Remove</button>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between font-weight-bold">
                        <span>Total</span>
                        <span>Rp. {{ totalAmount }}</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';

const items = ref([]); // tampilkan semua makanan
const keyword = ref(''); // cari makanan
const router = useRouter();
const requiredRoles = [1, 4]; // Role yang diizinkan mengakses halaman ini
const orderItems = ref([]); // order item

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

// menambahkan item ke order
const addToOrder = (item) => {
    const existingOrder = orderItems.value.find(order => order.item.id === item.id);

    if (existingOrder) {
        existingOrder.quantity++;
    } else {
        orderItems.value.push({ item, quantity: 1 });
    }
};

// hapus item
const removeFromOrder = (index) => {
    orderItems.value.splice(index, 1); // Menghapus item dari daftar pesanan berdasarkan index
};

// menjumlahkan item
const totalAmount = computed(() => {
    return orderItems.value.reduce((total, order) => total + order.item.price * order.quantity, 0);
});

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
