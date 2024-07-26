<script setup>
import { RouterLink, RouterView } from 'vue-router'
import { ref, onMounted } from 'vue';

// Inisialisasi nilai ref untuk menyimpan nama pengguna
const userName = ref('');

// Ambil nilai dari localStorage saat komponen dimuat
onMounted(() => {
  userName.value = localStorage.getItem('name') || '';
});

// Mengelola state untuk hamburger menu
const isMenuOpen = ref(false);

const toggleMenu = () => {
  isMenuOpen.value = !isMenuOpen.value;
};
</script>

<template>
  <header v-if="$route.name !== 'login'">
    <nav class="bg-gray-100 p-4">
      <div class="container mx-auto flex flex-wrap items-center justify-between">
        <div class="flex justify-between w-full lg:w-auto">
          <RouterLink to="/" class="text-xl font-semibold">Navbar</RouterLink>
          <button class="lg:hidden p-2 border border-gray-500 rounded" @click="toggleMenu">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M4 6h16M4 12h16m-7 6h7"></path>
            </svg>
          </button>
        </div>
        <div :class="{'hidden': !isMenuOpen, 'flex': isMenuOpen, 'lg:flex': true, 'lg:items-center': true, 'lg:w-auto': true, 'w-full': true}" id="navbarSupportedContent">
          <div class="flex flex-col lg:flex-row lg:items-center lg:space-x-4 w-full lg:w-auto">
            <RouterLink to="/" class="font-medi px-3 py-2 text-slate-700 rounded-lg hover:bg-slate-100 hover:text-slate-900">Home</RouterLink>
            <RouterLink to="/about" class="font-medi px-3 py-2 text-slate-700 rounded-lg hover:bg-slate-100 hover:text-slate-900">About</RouterLink>
            <RouterLink to="/login" class="font-medi px-3 py-2 text-slate-700 rounded-lg hover:bg-slate-100 hover:text-slate-900">Login</RouterLink>
          </div>
          <div class="me-6 mt-3 lg:mt-0 lg:ml-auto text-center lg:text-left">
            <span class="text-lg font-medium">Hi, {{ userName }}</span>
          </div>
        </div>
      </div>
    </nav>
  </header>

  <RouterView />
</template>

<style scoped>
/* Anda bisa menambahkan gaya khusus di sini */
</style>
