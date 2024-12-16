<template>
    <div class="flex items-center justify-center min-h-screen bg-gray-100">
      <div class="w-full max-w-sm p-6 bg-white rounded-lg shadow-md">
        <h2 class="text-2xl font-bold text-center text-gray-700">Login</h2>
        <form @submit.prevent="handleLogin" class="mt-4">
          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-600" for="email">Email</label>
            <input
              id="email"
              type="email"
              v-model="email"
              class="w-full px-4 py-2 mt-2 text-gray-700 bg-gray-200 border rounded-lg focus:outline-none focus:ring focus:ring-blue-300"
              placeholder="Email"
              required
            />
          </div>
          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-600" for="password">Password</label>
            <input
              id="password"
              type="password"
              v-model="password"
              class="w-full px-4 py-2 mt-2 text-gray-700 bg-gray-200 border rounded-lg focus:outline-none focus:ring focus:ring-blue-300"
              placeholder="Password"
              required
            />
          </div>
          <button
            type="submit"
            class="w-full px-4 py-2 mt-4 text-white bg-blue-500 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring focus:ring-blue-300"
          >
            Login
          </button>
        </form>
        <p class="mt-4 text-sm text-center text-gray-600">
          Don't have an account?
          <router-link to="/register" class="text-blue-500 hover:underline">Register</router-link>
        </p>
      </div>
    </div>
  </template>

  <script>
  import axios from '../plugins/axios.js';

  export default {
    data() {
      return {
        email: '',
        password: '',
      };
    },
    methods: {
      async handleLogin() {
        try {
          const response = await axios.post('/login', {
            email: this.email,
            password: this.password,
          });
          localStorage.setItem('token', response.data.token); // Simpan token JWT ke localStorage
          this.$router.push('/'); // Redirect ke halaman dashboard
        } catch (error) {
          console.error('Login failed:', error);
          alert('Invalid credentials');
        }
      },
    },
  };
  </script>

  <style scoped>
  </style>
