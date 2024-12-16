<template>
    <div v-if="tasks.total > tasks.per_page" class="mt-6 flex justify-center space-x-2">
      <button @click="changePage(tasks.current_page - 1)" :disabled="tasks.current_page <= 1"
              class="w-32 bg-blue-500 text-white py-2 rounded hover:bg-blue-600 transition disabled:opacity-50">
        {{ currentLanguage.previous }}
      </button>
      <span class="py-2 px-4 text-gray-600">
        {{ currentLanguage.page }} {{ tasks.current_page }} {{ currentLanguage.of }} {{ tasks.last_page }}
      </span>
      <button @click="changePage(tasks.current_page + 1)" :disabled="tasks.current_page >= tasks.last_page"
              class="w-32 bg-blue-500 text-white py-2 rounded hover:bg-blue-600 transition disabled:opacity-50">
        {{ currentLanguage.next }}
      </button>
    </div>
  </template>

  <script>
  export default {
    props: {
      tasks: Object,
      currentLanguage: Object
    },
    methods: {
      changePage(page) {
        if (page >= 1 && page <= this.tasks.last_page) {
          this.$emit('change-page', page); // Emit event untuk mengubah halaman
        }
      }
    }
  };
  </script>

  <style scoped>
  /* Styling untuk pagination jika perlu */
  </style>
