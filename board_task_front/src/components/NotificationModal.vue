<template>
  <div v-if="visible" class="modal fade show" style="display: block;" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Configuração de Notificação</h5>
          <button type="button" class="btn-close" @click="$emit('close')"></button>
        </div>
        <div class="modal-body">
          <form @submit.prevent="submit">
            <div class="mb-3">
              <label for="daysBefore" class="form-label">Dias antes da vencimento para notificar</label>
              <input
                id="daysBefore"
                v-model.number="form.days_before"
                type="number"
                class="form-control"
                min="1"
                required
              />
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" @click="$emit('close')">
            Cancelar
          </button>
          <button type="submit" class="btn btn-primary" @click="submit">
            Salvar
          </button>
        </div>
      </div>
    </div>
  </div>
  <div v-if="visible" class="modal-backdrop fade show"></div>
</template>

<script setup lang="ts">
import { ref, reactive, watch } from "vue";
import type { NotificationSetting } from "@/types/notification-setting";
import { NotificationService } from "@/core/services/notification.service";

const props = defineProps<{
  visible: boolean;
  setting: NotificationSetting | null;
}>();

const emit = defineEmits<{
  save: [setting: NotificationSetting];
  close: [];
}>();

const form = reactive({
  days_before: 1,
});

const notificationService = new NotificationService();

watch(
  () => props.setting,
  (setting) => {
    form.days_before = setting?.days_before ?? 1;
  },
  { immediate: true }
);

async function submit() {
  try {
    const result = await notificationService.saveSetting({ days_before: form.days_before });
    emit("save", result);
  } catch (error) {
    console.error("Erro ao salvar configuração", error);
  }
}
</script>