<script setup>
import axios from 'axios'
import { ref, onMounted } from 'vue';
import { useRouter, useRoute, RouterLink } from 'vue-router';

// Inisialisasi nilai ref untuk menyimpan nama pengguna
const userName = ref('');
const roleId = ref('');

// Gunakan useRouter untuk navigasi
const router = useRouter();

// Ambil nilai dari localStorage saat komponen dimuat
onMounted(() => {
    userName.value = localStorage.getItem('name') || '';
    roleId.value = localStorage.getItem('role_id')|| '';

    // Periksa apakah userName kosong atau tidak ada, lalu arahkan ke halaman login
    if (!userName.value) {
        router.push({ name: 'login' });
    }

    if (roleId.value != 4 && roleId.value != 1) {
        router.push({ name: 'home' });
    }
});

// Fungsi logout untuk menghapus data pengguna dari localStorage dan mengarahkan ke halaman login
const logout = () => {
    axios.post('/api/logout', {}, {
        headers: {
            'Authorization': `Bearer ${localStorage.getItem('token')}`
        }
    })
    .then(function (response) {
        localStorage.removeItem('email');
        localStorage.removeItem('name');
        localStorage.removeItem('role_id');
        localStorage.removeItem('token');
        router.push({ name: 'login' });
    })
    .catch(function (error) {
        console.log(error);
        console.log('ini error');
    });
};

</script>

<template>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <!-- logo -->
            <a class="navbar-brand" to="/">Navbar</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- menu -->
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <router-link to="/" class="nav-link active" aria-current="page">Home</router-link>
                    </li>
                    <li v-if="roleId == 4 || roleId == 1" class="nav-item">
                        <router-link v-if="userName" to="/order" class="nav-link active" aria-current="page">Order</router-link>
                    </li>
                    <li class="nav-item">
                        <router-link v-if="!userName" to="/register" class="nav-link">Register</router-link>
                    </li>
                    <li class="nav-item">
                        <router-link v-if="!userName" to="/login" class="nav-link" aria-current="page">Login</router-link>
                    </li>
                </ul>

                <!-- User Info -->
                <div v-if="userName">
                    <span class="navbar-text me-3">Hi, {{ userName }}</span>
                    <!-- <span class="navbar-text me-3">you are, {{ roleId }}</span> -->
                </div>

                <!-- Avatar -->
                <div class="dropdown" v-if="userName">
                    <a class="dropdown-toggle d-flex align-items-center hidden-arrow" href="#"
                        id="navbarDropdownMenuAvatar" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="https://mdbcdn.b-cdn.net/img/new/avatars/2.webp" class="rounded-circle" height="25"
                            alt="Black and White Portrait of a Man" loading="lazy" />
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuAvatar">
                        <li>
                            <a class="dropdown-item" href="#">My profile</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#">Settings</a>
                        </li>
                        <li>
                            <a class="dropdown-item" @click="logout">Logout</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
</template>
