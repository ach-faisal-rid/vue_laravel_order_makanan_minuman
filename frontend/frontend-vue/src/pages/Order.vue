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
                                    <button @click="addToOrder(item)" class="btn btn-primary">Order</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Order item -->
            <div class="col-12 col-sm-4 mb-3 border rounded p-3">
                <h2 class="text-center">Order List</h2>
                <div class="mb-5">
                    <div class="mb-3">
                        <label for="customerName" class="form-label">Customer Name</label>
                        <input type="text" v-model="customerName" class="form-control" id="customerName">
                    </div>
                    <div class="mb-3">
                        <label for="tabelNo" class="form-label">Table No.</label>
                        <input type="text" v-model="tabelNo" class="form-control" id="tabelNo">
                    </div>
                </div>
                <hr>
                <div v-if="orderItems.length === 0" class="text-center">No items ordered</div>
                <div v-else>
                    <div v-for="(order, index) in orderItems" :key="order.item.id" class="d-flex justify-content-between align-items-center">
                        <div>
                            <button @click="decreaseQuantity(index)" class="btn btn-outline-warning btn-sm"> - </button>
                            <span class="mx-2">{{ order.quantity }}</span>
                            <button @click="increaseQuantity(index)" class="btn btn-outline-success btn-sm"> + </button>
                        </div>
                        <span>{{ order.item.name }}</span>
                        <span>Rp. {{ order.item.price * order.quantity }}</span>
                        <button @click="removeFromOrder(index)" class="btn btn-danger btn-sm">Remove</button>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between font-weight-bold">
                        <span>Total</span>
                        <span>Rp. {{ totalAmount }}</span>
                    </div>
                </div>
                <button type="submit" class="btn btn-success form-control" :disabled="processing" @click="submitOrder()">{{ processing ? 'Processing order...' : 'Submit' }}</button>
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
const orderItems = ref([]);
const customerName = ref('');
const tabelNo = ref('');
const router = useRouter();
const requiredRoles = [1, 4]; // Role yang diizinkan mengakses halaman ini
const processing = ref(false);

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

const addToOrder = (item) => {
    const existingOrder = orderItems.value.find(order => order.item.id === item.id);

    if (existingOrder) {
        existingOrder.quantity++;
    } else {
        orderItems.value.push({ item, quantity: 1 });
    }
};

const increaseQuantity = (index) => {
    orderItems.value[index].quantity++;
};

const decreaseQuantity = (index) => {
    if (orderItems.value[index].quantity > 1) {
        orderItems.value[index].quantity--;
    } else {
        removeFromOrder(index);
    }
};

const removeFromOrder = (index) => {
    orderItems.value.splice(index, 1); // Menghapus item dari daftar pesanan berdasarkan index
};

const totalAmount = computed(() => {
    return orderItems.value.reduce((total, order) => total + order.item.price * order.quantity, 0);
});

const submitOrder = () => {
    if (customerName.value === '' || tabelNo.value === '') {
        alert('Customer name and table no cannot be empty');
        return;
    }

    processing.value = true;

    const orderData = {
        customer_name: customerName.value,
        table_no: tabelNo.value,
        items: orderItems.value.map(order => ({
            id: order.item.id,
            qty: order.quantity,
        }))
    };

    axios.post('/api/create-order', orderData, {
        headers: {
            'Authorization': `Bearer ${localStorage.getItem('token')}`
        }
    })
    .then(response => {
        console.log('Order submitted successfully:', response.data);
        alert('Order submitted successfully!');
        orderItems.value = []; // Kosongkan daftar pesanan setelah submit berhasil
        customerName.value = ''; // Kosongkan nama customer
        tabelNo.value = ''; // Kosongkan nomor meja
    })
    .catch(error => {
        console.error('Error submitting order:', error);
        alert('Error submitting order. Please try again.');
    })
    .finally(() => {
        processing.value = false;
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
        router.push({ name: 'Home' }); // Redirect jika role tidak sesuai
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

.btn-sm {
    padding: 0.25rem 0.5rem;
}

.mx-2 {
    margin-left: 0.5rem;
    margin-right: 0.5rem;
}
</style>
