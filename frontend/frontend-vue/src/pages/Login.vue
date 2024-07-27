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

const submitLogin = async () => {
    await axios.post('/api/login', {
        email: email.value,
        password: password.value
    });

    router.push('/');

}
</script>
