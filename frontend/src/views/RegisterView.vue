<template>
  <div class="card mx-auto" style="max-width:420px;">
    <div class="card-body">
      <h3 class="card-title mb-3">Cadastro de Usuário</h3>
      <form @submit.prevent="handleSubmit">
        <div class="mb-3">
          <label for="name" class="form-label">Nome</label>
          <input v-model="form.name" class="form-control" type="text" id="name" required />
        </div>

        <div class="mb-3">
          <label for="email" class="form-label">Email</label>
          <input v-model="form.email" class="form-control" type="email" id="email" required />
        </div>

        <div class="mb-3">
          <label for="password" class="form-label">Senha</label>
          <input v-model="form.password" class="form-control" type="password" id="password" required />
        </div>

        <button class="btn btn-primary w-100" :disabled="loading">
          {{ loading ? 'Cadastrando...' : 'Cadastrar' }}
        </button>

        <p v-if="errorMessage" class="text-danger mt-2">{{ errorMessage }}</p>
        <p v-if="successMessage" class="text-success mt-2">{{ successMessage }}</p>
      </form>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, reactive, ref } from 'vue';
import { createUser, type UserInput } from '../api/user';

export default defineComponent({
  name: 'RegisterView',
  setup() {
    const form = reactive<UserInput>({
      name: '',
      email: '',
      password: '',
    });

    const loading = ref(false);
    const errorMessage = ref('');
    const successMessage = ref('');

    const handleSubmit = async () => {
      loading.value = true;
      errorMessage.value = '';
      successMessage.value = '';

      try {
        const user = await createUser(form);
        successMessage.value = `Usuário ${user.name} cadastrado com sucesso!`;
        form.name = '';
        form.email = '';
        form.password = '';
      } catch (err: any) {
        if (err.response?.data?.message) {
          errorMessage.value = err.response.data.message;
        } else {
          errorMessage.value = 'Erro ao cadastrar usuário.';
        }
      } finally {
        loading.value = false;
      }
    };

    return {
      form,
      loading,
      errorMessage,
      successMessage,
      handleSubmit,
    };
  },
});
</script>

<style scoped>
.register-page {
  max-width: 400px;
  margin: 0 auto;
  padding: 2rem;
}

input {
  display: block;
  width: 100%;
  margin-bottom: 1rem;
  padding: 0.5rem;
}

button {
  padding: 0.5rem 1rem;
}

.error {
  color: red;
  margin-top: 1rem;
}

.success {
  color: green;
  margin-top: 1rem;
}
</style>
