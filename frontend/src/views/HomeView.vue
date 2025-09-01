<template>
  <div class="container mt-5">
    <div class="card mx-auto" style="max-width: 600px;">
      <div class="card-body text-center">
        <h2 class="card-title mb-3">Bem-vindo(a), {{ user?.name }}!</h2>
        <p class="card-text mb-2">Seu email registrado: <strong>{{ user?.email }}</strong></p>
      </div>
    </div>
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
  if (!session) {
    router.push('/login');
    return;
  }

  try {
    const userData = await getUser(session.id);
    user.value = userData;
  } catch (err) {
    alert(err);
    router.push('/login');
  }
});
</script>
