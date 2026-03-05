<template>
  <div v-if="visible" class="modal-overlay">
    <div class="confirm-modal">
      <h3>{{ title }}</h3>
      <p>{{ message }}</p>

      <div class="actions">
        <button class="btn btn-secondary" @click="$emit('cancel')">
          Cancelar
        </button>

        <button class="btn btn-danger" @click="$emit('confirm')">
          Confirmar
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
defineProps({
  visible: Boolean,
  title: String,
  message: String
});
</script>

<style>
.modal-overlay{
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,.4);
  display:flex;
  align-items:center;
  justify-content:center;
  z-index: 2000;
}

.confirm-modal{
  background:white;
  padding:24px;
  border-radius:8px;
  width:400px;
  box-shadow:0 10px 25px rgba(0,0,0,.2);
}

.actions{
  margin-top:20px;
  display:flex;
  justify-content:flex-end;
  gap:10px;
}
</style>