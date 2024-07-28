<template>
    <div class="container mt-3">
        <div class="col-md-6 m-auto">
            <h1>login page</h1>

            <form @submit.prevent="submitLogin">

            <!-- Email input -->
            <div data-mdb-input-init class="form-outline mb-4">
                <input type="email" id="email" class="form-control" v-model="email" />
                <label class="form-label" for="form2Example1">Email address</label>
            </div>

            <!-- Password input -->
            <div data-mdb-input-init class="form-outline mb-4">
                <input type="password" id="password" class="form-control" v-model="password" />
                <label class="form-label" for="form2Example2">Password</label>
            </div>

            <!-- Submit button -->
            <button type="submit" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-block mb-4">Sign
                in</button>

            </form>

        </div>
    </div>
</template>

<script setup>

import { ref } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';

const email = ref('');
const password = ref('');
const router = useRouter();

// Dapatkan CSRF Token
const getToken = async() => {
    await axios.get('/sanctum/csrf-cookie');
};

// Fungsi untuk login dan mendapatkan Bearer token
const submitLogin = async () => {
    try {
        // Dapatkan CSRF token
        await getToken();

        // Login dan dapatkan Bearer token
        const response = await axios.post('/api/login', {
            email: email.value,
            password: password.value
        });

        console.log(response);

        // Simpan data pengguna dan token di localStorage
        localStorage.setItem('email', response.data.data.email);
        localStorage.setItem('name', response.data.data.name);
        localStorage.setItem('role_id', response.data.data.level);
        localStorage.setItem('token', response.data.token);

        // Set default header untuk semua permintaan Axios berikutnya
        axios.defaults.headers.common['Authorization'] = `Bearer ${response.data.data.token}`;

        // Alihkan ke halaman utama
        router.push('/');
    } catch (error) {
        console.log(error);
        console.log('Ini error');
    }
};

</script>
