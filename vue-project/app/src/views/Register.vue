<template>
  <div class="register-page">
    <h1>Cadastro de Usuário</h1>
    <form @submit.prevent="handleSubmit">
      <div>
        <label for="name">Nome</label>
        <input type="text" id="name" v-model="form.name" required />
      </div>

      <div>
        <label for="email">Email</label>
        <input type="email" id="email" v-model="form.email" required />
      </div>

      <div>
        <label for="password">Senha</label>
        <input type="password" id="password" v-model="form.password" required />
      </div>

      <button type="submit" :disabled="loading">
        {{ loading ? 'Cadastrando...' : 'Cadastrar' }}
      </button>
    </form>

    <p v-if="errorMessage" class="error">{{ errorMessage }}</p>
    <p v-if="successMessage" class="success">{{ successMessage }}</p>
  </div>
</template>

<script lang="ts">
import { defineComponent, reactive, ref } from 'vue';
import { createUser, type UserInput } from '../api/user';

export default defineComponent({
  name: 'Register',
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
