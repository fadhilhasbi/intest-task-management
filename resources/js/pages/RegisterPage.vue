<template>
    <div class="flex items-center justify-center min-h-screen bg-gray-100">
      <div class="w-full max-w-sm p-6 bg-white rounded-lg shadow-md">
        <h2 class="text-2xl font-bold text-center text-gray-700">Register</h2>
        <form @submit.prevent="handleRegister" class="mt-4">
          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-600" for="name">Full Name</label>
            <input
              id="name"
              type="text"
              v-model="name"
              class="w-full px-4 py-2 mt-2 text-gray-700 bg-gray-200 border rounded-lg focus:outline-none focus:ring focus:ring-blue-300"
              placeholder="Full Name"
              required
            />
            <p v-if="errors.name" class="text-red-500 text-xs mt-1">{{ errors.name }}</p>
          </div>
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
            <p v-if="errors.email" class="text-red-500 text-xs mt-1">{{ errors.email }}</p>
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
            <p v-if="errors.password" class="text-red-500 text-xs mt-1">{{ errors.password }}</p>
          </div>
          <button
            type="submit"
            class="w-full px-4 py-2 mt-4 text-white bg-blue-500 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring focus:ring-blue-300"
          >
            Register
          </button>
        </form>
        <p class="mt-4 text-sm text-center text-gray-600">
          Already have an account?
          <router-link to="/login" class="text-blue-500 hover:underline">Login</router-link>
        </p>
      </div>
    </div>
  </template>

  <script>
  import axios from '../plugins/axios.js';
  import Swal from 'sweetalert2'; // Import SweetAlert2

  export default {
    data() {
      return {
        name: '',
        email: '',
        password: '',
        errors: {},
      };
    },
    methods: {
      validateForm() {
        this.errors = {};

        if (!this.name) {
          this.errors.name = 'Full name is required';
        }

        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!this.email || !emailRegex.test(this.email)) {
          this.errors.email = 'A valid email is required';
        }

        if (this.password.length < 8) {
          this.errors.password = 'Password must be at least 8 characters long';
        }

        return Object.keys(this.errors).length === 0;
      },

      async handleRegister() {
        if (!this.validateForm()) {
          return;
        }

        try {
          const response = await axios.post('/register', {
            name: this.name,
            email: this.email,
            password: this.password,
          });

          // Save JWT token to localStorage
          localStorage.setItem('token', response.data.token);

          // Redirect to login page after successful registration
          this.$router.push('/login');
        } catch (error) {
          console.error('Registration failed:', error);

          // Display SweetAlert2 error notification
          Swal.fire({
            icon: 'error',
            title: 'Registration Failed',
            text: 'There was an issue with the registration. Please try again.',
          });
        }
      },
    },
  };
  </script>

  <style scoped>
  /* You can add custom styles here if needed */
  </style>
