<template>
  <div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="bg-white shadow-lg rounded-2xl p-8 w-full max-w-md">
      <h1 class="text-2xl font-bold mb-6 text-center">Login</h1>
      
      <form @submit.prevent="handleLogin" class="space-y-4">
        <div>
          <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
          <input
            v-model="form.email"
            type="email"
            id="email"
            required
            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
          />
        </div>

        <div>
          <label for="password" class="block text-sm font-medium text-gray-700">Senha</label>
          <input
            v-model="form.password"
            type="password"
            id="password"
            required
            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
          />
        </div>

        <button
          type="submit"
          :disabled="loading"
          class="w-full flex justify-center py-2 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50"
        >
          {{ loading ? 'Entrando...' : 'Entrar' }}
        </button>

        <p v-if="error" class="text-red-500 text-sm text-center mt-2">
          {{ error }}
        </p>
      </form>

      <div class="mt-6">
        <button
          @click="fetchUser"
          class="w-full flex justify-center py-2 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700"
        >
          Buscar Usuário
        </button>
      </div>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { reactive, ref } from "vue";
import { doLogin } from "@/api/auth";
import { useRouter } from "vue-router";
import { getUserSession, setUserSession } from "@/services/session";
import { httpAuth } from "@/api/http";
import { getUser } from "@/api/user";

const router = useRouter();
const form = reactive({ email: "", password: "" });
const loading = ref(false);
const error = ref<string | null>(null);

const handleLogin = async () => {
  loading.value = true;
  error.value = null;

  try {
    const session = await doLogin(form);
    const response = await httpAuth.get('/test');
    console.log(await response.data);  // deve logar { ok: true }
    console.log(session);
    setUserSession(session);
  } catch (err: any) {
    error.value = "Email ou senha inválidos.";
  } finally {
    loading.value = false;
  }
};

const fetchUser = async () => {
  const session = getUserSession();
  if (!session) {
    alert("Você precisa fazer login primeiro.");
    return;
  }

  try {
    const userData = await getUser(session.id);
    console.log("Usuário retornado:", userData);
    alert(`Usuário: ${userData.name} (${userData.email})`);
  } catch (err: any) {
    console.error(err);
    alert("Erro ao buscar usuário.");
  }
};
</script>
