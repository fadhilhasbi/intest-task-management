<template>
    <header class="flex justify-between items-center pb-6 border-b border-gray-300">
        <h1 class="text-2xl font-bold text-gray-700">{{ currentLanguage.dashboard }}</h1>

        <!-- Dropdown for username and logout -->
        <div class="relative inline-block text-left">
            <button @click="toggleDropdown"
                class="inline-flex justify-center items-center w-full px-4 py-2 text-sm font-medium leading-5 text-gray-700 transition duration-150 ease-in-out bg-white border border-gray-300 rounded-md hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-50 active:text-gray-800"
                type="button" aria-haspopup="true" aria-expanded="true" aria-controls="dropdown-menu">
                <span>{{ username }}</span>
                <svg class="w-5 h-5 ml-2 -mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                        clip-rule="evenodd"></path>
                </svg>
            </button>

            <div v-if="dropdownOpen"
                class="absolute right-0 mt-2 w-48 bg-white border border-gray-300 rounded-md shadow-lg z-10">
                <div class="px-4 py-3">
                    <p class="text-sm leading-5">{{ currentLanguage.greet }}</p>
                    <p class="text-sm font-medium leading-5 text-gray-900 truncate">{{ username }}</p>
                </div>
                <div class="py-1">
                    <a href="javascript:void(0)" tabindex="0"
                        class="text-gray-700 flex justify-between w-full px-4 py-2 text-sm leading-5 text-left"
                        role="menuitem" @click="logout">
                        {{ currentLanguage.logout }}
                    </a>
                </div>
            </div>
        </div>
    </header>
</template>

<script>
import axios from "../plugins/axios";
import en from "../locales/en.json";
import id from "../locales/id.json";

export default {
    data() {
        return {
            dropdownOpen: false, // Status dropdown untuk user menu
            username: '', // Nama pengguna yang sedang login
            selectedLanguage: 'id', // Bahasa yang dipilih
            languageData: {
                en,
                id
            }
        };
    },
    computed: {
        currentLanguage() {
            return this.languageData[this.selectedLanguage];
        }
    },
    methods: {
        async fetchUsername() {
            try {
                const response = await axios.get("/user/name");
                this.username = response.data.name;
            } catch (error) {
                console.error("Failed to fetch username:", error);
            }
        },
        toggleDropdown() {
            this.dropdownOpen = !this.dropdownOpen;
        },
        logout() {
            localStorage.removeItem("token");
            this.$router.push("/login");
        }
    },
    mounted() {
        this.fetchUsername();
    }
};
</script>

<style scoped>
/* Styling khusus untuk header jika diperlukan */
</style>
