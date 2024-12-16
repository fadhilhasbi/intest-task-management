<template>
    <div class="mb-4 flex justify-end">
        <select
            :value="selectedLanguage"
            @change="changeLanguage($event)"
            class="p-2 border rounded">
            <option value="en">English</option>
            <option value="id">Bahasa Indonesia</option>
        </select>
    </div>
</template>

<script>
export default {
    props: ['selectedLanguage'],
    methods: {
        changeLanguage(event) {
            // Emit the update:selectedLanguage event with the new value
            this.$emit('update:selectedLanguage', event.target.value);
        }
    }
};
</script>

<style scoped>
/* Tambahkan styling khusus untuk komponen ini */
</style>
