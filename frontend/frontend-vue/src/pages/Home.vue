<template>
    <h1>home page</h1>
   </template>

   <script setup>
    import {ref, onMounted} from 'vue';
    import axios from 'axios'

    const user = ref();

    onMounted(async () => {
        const data = await axios.get('/api/me');
        console.log(data);
        user.data = data.data;
    })
   </script>
