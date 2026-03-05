<script setup lang="ts">
import { ref } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";
import { AuthService } from "../../../core/services/auth.service";
import Toast from "../../../components/Toast.vue";

const router = useRouter();

const email = ref("");
const password = ref("");
const loading = ref(false);
const error = ref("");

const toastMessage = ref("");
const toastType = ref<'success' | 'error'>('success');
const showToast = ref(false);

const authService = new AuthService()

const login = async () => {
  try {
    loading.value = true;

    await authService.login({email: email.value, password: password.value });

    showToastMessage("Login realizado com sucesso", 'success');
    setTimeout(() => {
      router.push({ name: "tasks" });
    }, 1500);

  } catch (err: any) {
    error.value = "Credenciais inválidas";
  } finally {
    loading.value = false;
  }
};

const showToastMessage = (message: string, type: 'success' | 'error') => {
  toastMessage.value = message;
  toastType.value = type;
  showToast.value = true;
  setTimeout(() => showToast.value = false, 3000);
};
</script>

<template>
<div class="container vh-100 d-flex align-items-center justify-content-center">

  <div class="card shadow p-4" style="width:400px">

    <h3 class="text-center mb-4">
      Login
    </h3>

    <div v-if="error" class="alert alert-danger">
      {{ error }}
    </div>

    <form @submit.prevent="login">

      <div class="mb-3">
        <label class="form-label">
          Email
        </label>
        <input
          v-model="email"
          type="email"
          class="form-control"
          required
        />
      </div>

      <div class="mb-3">
        <label class="form-label">
          Senha
        </label>
        <input
          v-model="password"
          type="password"
          class="form-control"
          required
        />
      </div>

      <button
        class="btn btn-primary w-100"
        :disabled="loading"
      >
        {{ loading ? "Entrando..." : "Entrar" }}
      </button>

    </form>

  </div>

  <Toast
    :message="toastMessage"
    :type="toastType"
    :visible="showToast"
    @close="showToast = false"
  />

</div>
</template>