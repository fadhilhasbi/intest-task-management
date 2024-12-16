<template>
    <div class="dashboard bg-gray-100 min-h-screen p-8">
        <DashboardHeader />

        <section class="mt-6">
            <!-- Language Toggle -->
            <LanguageToggle v-model:selectedLanguage="selectedLanguage" />

            <!-- Filter Dropdown -->
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-gray-600">{{ currentLanguage.tasks }}</h2>
                <select v-model="selectedStatus" @change="fetchTasks(1)" class="p-2 border rounded">
                    <option value="">{{ currentLanguage.default_status }}</option>
                    <option value="incomplete">{{ currentLanguage.status_incomplete }}</option>
                    <option value="completed">{{ currentLanguage.status_completed }}</option>
                </select>
                <button @click="openTaskModal()"
                    class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600 transition">
                    {{ currentLanguage.add_task }}
                </button>
            </div>

            <div v-if="!tasks || !tasks.data || tasks.data.length === 0" class="text-center text-gray-500 mt-8">
                <p>{{ currentLanguage.no_tasks }}</p>
            </div>
            <ul v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <li v-for="task in tasks.data" :key="task.id"
                    class="bg-white shadow-md p-4 rounded border-l-4 flex flex-col justify-between min-h-[200px]"
                    :class="{
                        'border-blue-500': task.status === 'incomplete',
                        'border-green-500': task.status === 'completed',
                    }">
                    <!-- Task Title -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700 mb-2 break-words">{{ task.title }}</h3>
                        <p class="text-gray-500 text-sm break-words">{{ task.description }}</p>
                    </div>

                    <!-- Task Status and Actions -->
                    <div class="mt-4 flex justify-between items-center">
                        <!-- Status -->
                        <span class="text-xs font-medium uppercase" :class="{
                            'text-blue-500': task.status === 'incomplete',
                            'text-green-500': task.status === 'completed',
                        }">
                            {{ task.status === 'incomplete' ? currentLanguage.status_incomplete :
                                currentLanguage.status_completed }}
                        </span>
                        <!-- Buttons -->
                        <div class="flex gap-2">
                            <button @click="editTask(task)"
                                class="text-blue-500 hover:underline flex items-center gap-1 text-sm">
                                <i class="fas fa-pen"></i>
                                {{ currentLanguage.edit }}
                            </button>
                            <button @click="deleteTask(task.id)"
                                class="text-red-500 hover:underline flex items-center gap-1 text-sm">
                                <i class="fas fa-trash"></i>
                                {{ currentLanguage.delete }}
                            </button>
                        </div>
                    </div>
                </li>
            </ul>

            <!-- Pagination controls -->
            <Pagination :tasks="tasks" :currentLanguage="currentLanguage" @change-page="fetchTasks"/>
        </section>

        <!-- Modal for adding/editing tasks -->
        <div v-if="isTaskModalOpen" class="fixed inset-0 bg-black bg-opacity-30 flex justify-center items-center">
            <div class="bg-white p-6 rounded-lg shadow-md w-11/12 max-w-md">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">
                    {{ modalTask.id ? currentLanguage.edit_task : currentLanguage.add_task_modal }}
                </h3>
                <form @submit.prevent="saveTask">
                    <div class="mb-4">
                        <label for="title" class="block text-gray-700">{{ currentLanguage.title }}</label>
                        <input v-model="modalTask.title" id="title" type="text"
                            class="w-full p-2 border rounded focus:outline-none focus:ring focus:ring-blue-300"
                            required />
                    </div>
                    <div class="mb-4">
                        <label for="description" class="block text-gray-700">{{ currentLanguage.description }}</label>
                        <textarea v-model="modalTask.description" id="description"
                            class="w-full p-2 border rounded focus:outline-none focus:ring focus:ring-blue-300"
                            required></textarea>
                    </div>
                    <div class="mb-4">
                        <label for="status" class="block text-gray-700">{{ currentLanguage.status }}</label>
                        <select v-model="modalTask.status" id="status"
                            class="w-full p-2 border rounded focus:outline-none focus:ring focus:ring-blue-300">
                            <option value="incomplete">{{ currentLanguage.status_incomplete }}</option>
                            <option value="completed">{{ currentLanguage.status_completed }}</option>
                        </select>
                    </div>
                    <div class="flex justify-end gap-4">
                        <button @click="closeTaskModal()" type="button"
                            class="py-2 px-4 bg-gray-200 rounded hover:bg-gray-300">
                            {{ currentLanguage.cancel }}
                        </button>
                        <button type="submit" class="py-2 px-4 bg-blue-500 text-white rounded hover:bg-blue-600">
                            {{ currentLanguage.save }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "../plugins/axios";
import Swal from "../plugins/sweetalert";
import en from "../locales/en.json";
import id from "../locales/id.json";
import DashboardHeader from "../components/DashboardHeader.vue";
import LanguageToggle from "../components/LanguageToggle.vue";
import Pagination from "../components/Pagination.vue";

export default {
    components: {
        DashboardHeader,
        LanguageToggle,
        Pagination,
    },
    data() {
        return {
            tasks: {}, // Data tugas yang diambil dari server
            selectedLanguage: 'id', // Bahasa yang dipilih
            selectedStatus: '', // Status tugas yang dipilih untuk filter
            isTaskModalOpen: false, // Status modal tugas (buka/tutup)
            isEditMode: false, // Menandai apakah sedang dalam mode edit
            modalTask: {
                id: null,
                title: "",
                description: "",
                status: "incomplete",
            },
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
        async fetchTasks(page = 1) {
            try {
                const statusParam = this.selectedStatus ? `&status=${this.selectedStatus}` : '';
                const response = await axios.get(`/tasks?page=${page}${statusParam}`);
                this.tasks = response.data;
            } catch (error) {
                console.error("Failed to fetch tasks:", error);
                this.tasks = [];
            }
        },
        changePage(page) {
            if (page >= 1 && page <= this.tasks.last_page) {
                this.fetchTasks(page);
            }
        },
        changeLanguage() {
            this.fetchTasks(); // Reload tasks dengan bahasa yang dipilih
        },
        openTaskModal(task = null) {
            this.isTaskModalOpen = true;
            this.isEditMode = !!task; // Mode edit jika ada `task`, mode tambah jika tidak
            if (task) {
                this.modalTask = { ...task }; // Salin data tugas ke modalTask
            } else {
                this.resetModalTask();
            }
        },
        closeTaskModal() {
            this.isTaskModalOpen = false;
            this.resetModalTask(); // Reset data modal
        },
        resetModalTask() {
            this.modalTask = {
                id: null,
                title: "",
                description: "",
                status: "incomplete",
            };
            this.isEditMode = false;
        },
        editTask(task) {
            this.openTaskModal(task); // Buka modal dan masukkan data tugas untuk diedit
        },
        async saveTask() {
            try {
                if (this.isEditMode) {
                    // Update tugas yang ada
                    await axios.put(`/tasks/${this.modalTask.id}`, this.modalTask);
                    Swal.fire({
                        icon: 'success',
                        title: this.currentLanguage.task_updated,
                        text: this.currentLanguage.task_updated_message,
                        timer: 1500,
                        showConfirmButton: false,
                    });
                } else {
                    // Tambahkan tugas baru
                    await axios.post("/tasks", this.modalTask);
                    Swal.fire({
                        icon: 'success',
                        title: this.currentLanguage.task_created,
                        text: this.currentLanguage.task_created_message,
                        timer: 1500,
                        showConfirmButton: false,
                    });
                }
                this.closeTaskModal();
                this.fetchTasks(); // Perbarui daftar tugas setelah simpan
            } catch (error) {
                console.error("Failed to save task:", error);
                Swal.fire({
                    icon: 'error',
                    title: this.currentLanguage.error_title,
                    text: this.currentLanguage.error_message,
                });
            }
        },
        async deleteTask(id) {
            const confirmation = await Swal.fire({
                title: this.currentLanguage.confirm_delete_title,
                text: this.currentLanguage.confirm_delete_message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: this.currentLanguage.confirm_delete,
                cancelButtonText: this.currentLanguage.cancel,
            });

            if (confirmation.isConfirmed) {
                try {
                    await axios.delete(`/tasks/${id}`);
                    Swal.fire({
                        icon: 'success',
                        title: this.currentLanguage.task_deleted,
                        text: this.currentLanguage.task_deleted_message,
                        timer: 1500,
                        showConfirmButton: false,
                    });
                    this.fetchTasks();
                } catch (error) {
                    console.error("Failed to delete task:", error);
                    Swal.fire({
                        icon: 'error',
                        title: this.currentLanguage.error_title,
                        text: this.currentLanguage.error_message,
                    });
                }
            }
        },
        logout() {
            localStorage.removeItem("token");
            this.$router.push("/login");
        }
    },
    mounted() {
        this.fetchTasks();
    }
};
</script>
