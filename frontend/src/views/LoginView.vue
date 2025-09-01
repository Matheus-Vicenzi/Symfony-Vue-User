<template>
  <div class="card mx-auto" style="max-width:420px;">
    <div class="card-body">
      <h3 class="card-title mb-3">Login</h3>
      <form @submit.prevent="handleLogin">
        <div class="mb-3">
          <label class="form-label">Email</label>
          <input v-model="form.email" class="form-control" type="email" />
        </div>
        <div class="mb-3">
          <label class="form-label">Senha</label>
          <input v-model="form.password" class="form-control" type="password" />
        </div>
        <button class="btn btn-primary w-100" :disabled="loading">{{ loading ? 'Entrando...' : 'Entrar' }}</button>
      </form>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { reactive, ref, watch } from "vue";
import { doLogin } from "@/api/auth";
import { useRoute, useRouter } from "vue-router";
import { setUserSession } from "@/services/session";
import { showToast } from "@/services/toast";

const router = useRouter();
const route = useRoute();
const form = reactive({ email: "", password: "" });
const loading = ref(false);
const error = ref<string | null>(null);

watch(
  () => route.query.msg,
  (newVal) => {
    if (newVal === 'login-required') {
      showToast('Você precisa estar logado para acessar essa página.', 'warning');
    }
  },
  { immediate: true }
)

const handleLogin = async () => {
  loading.value = true;
  error.value = null;

  try {
    const session = await doLogin(form);

    setUserSession(session);
    router.push("/");
  } catch (err) {
    console.error(err)
    error.value = "Email ou senha inválidos.";
    showToast(`Erro ao fazer login: ${error.value}`, 'danger');
  } finally {
    loading.value = false;
  }
};
</script>
