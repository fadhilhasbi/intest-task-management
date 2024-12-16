import routes from './routes';
import { createRouter, createWebHistory } from 'vue-router';

const router = createRouter({
  history: createWebHistory(),
  routes,
});

// Middleware untuk memeriksa apakah user sudah login
router.beforeEach((to, from, next) => {
  const token = localStorage.getItem('token');
  if (to.meta.requiresAuth && !token) {
    next('/login');  // Arahkan ke halaman login jika belum login
  } else {
    next();
  }
});

export default router;
