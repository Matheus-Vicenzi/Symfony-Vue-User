import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import Register from '@/views/RegisterView.vue'
import LoginView from '@/views/LoginView.vue'
import { getUserSession } from '@/services/session';

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'Home',
      component: HomeView,
    },
    {
      path: '/register',
      name: 'Cadastro',
      component: Register,
    },
    {
      path: '/login',
      name: 'Login',
      component: LoginView,
    },
  ],
})

router.beforeEach(async (to, from, next) => {
  const protectedRoutes = ['Home'];

  const session = getUserSession();

  if (protectedRoutes.includes(to.name as string) && !session) {
    next({ path: '/login', query: { msg: 'login-required' } });
    return;
  }
  next();
});

export default router
