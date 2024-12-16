import { createApp } from 'vue'
import router from './router'
import App from './App.vue';
import { createI18n } from 'vue-i18n'
import '@fortawesome/fontawesome-free/css/all.css';

// Import language files
import en from './locales/en.json'
import id from './locales/id.json'

const i18n = createI18n({
  locale: localStorage.getItem('lang') || 'en', // Default to English or saved language
  messages: {
    en,
    id
  }
});


const app = createApp(App);
app.use(router)
app.use(i18n)
app.mount('#app')
