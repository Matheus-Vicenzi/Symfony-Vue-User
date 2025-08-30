import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import Register from '@/views/Register.vue'
import LoginView from '@/views/Login.vue'
import { getUser } from '@/api/user';
import { getUserSession } from '@/services/session';

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView,
    },
    {
      path: '/register',
      name: 'register',
      component: Register,
    },
    {
      path: '/login',
      name: 'login',
      component: LoginView,
    },
  ],
})

router.beforeEach(async (to, from, next) => {
  const protectedRoutes = ['home'];

  const session = getUserSession();

  if (protectedRoutes.includes(to.name as string)) {
    if (!session) {
      next('/login');
      return;
    }
    try {
      await getUser(session.id);
      next();
    } catch {
      next('/login');
    }
  } else {
    next();
  }
});

export default router
