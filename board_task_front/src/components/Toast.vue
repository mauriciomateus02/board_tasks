<script setup lang="ts">
interface Props {
  message: string;
  type: 'success' | 'error';
  visible: boolean;
}

const props = defineProps<Props>();

const emit = defineEmits<{
  close: [];
}>();

const closeToast = () => {
  emit('close');
};
</script>

<template>
  <div class="toast-container position-fixed top-0 end-0 p-3">
    <div v-show="visible" class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="toast-header">
        <strong class="me-auto" :class="{ 'text-danger': type === 'error' }">
          {{ type === 'success' ? 'Sucesso' : 'Erro' }}
        </strong>
        <button type="button" class="btn-close" @click="closeToast" aria-label="Close"></button>
      </div>
      <div class="toast-body">
        {{ message }}
      </div>
    </div>
  </div>
</template>