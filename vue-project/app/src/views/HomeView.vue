<template>
  <div class="home-page">
    <h1>Bem-vindo, {{ user?.name }}</h1>
    <p>Email: {{ user?.email }}</p>
  </div>
</template>

<script lang="ts" setup>
import { ref, onMounted } from 'vue';
import { getUser, type UserOutput } from '@/api/user';
import { getUserSession } from '@/services/session';
import { useRouter } from 'vue-router';

const router = useRouter();
const user = ref<UserOutput | null>(null);

onMounted(async () => {
  const session = getUserSession();
  console.log('Session:', session);
  if (!session) {
    router.push('/login');
    return;
  }

  try {
    const user = await getUser(session.id);
  } catch (err) {
    console.error(err);
    router.push('/login');
  }
});
</script>
