<template>
  <div v-if="visible" class="modal fade show" style="display: block;" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">{{ isEdit ? 'Editar Tarefa' : 'Criar Tarefa' }}</h5>
          <button type="button" class="btn-close" @click="$emit('close')"></button>
        </div>
        <div class="modal-body">
          <form @submit.prevent="submit">
            <div class="mb-3">
              <label for="taskTitle" class="form-label">Título</label>
              <input
                id="taskTitle"
                v-model="form.title"
                type="text"
                class="form-control"
                required
              />
            </div>
            <div class="mb-3">
              <label for="taskDescription" class="form-label">Descrição</label>
              <textarea
                id="taskDescription"
                v-model="form.description"
                class="form-control"
                rows="3"
              ></textarea>
            </div>
            <div class="mb-3">
              <label for="taskDueDate" class="form-label">Data de Vencimento</label>
              <input
                id="taskDueDate"
                v-model="form.due_date"
                type="date"
                class="form-control"
              />
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" @click="$emit('close')">
                Cancelar
              </button>
              <button type="submit" class="btn btn-primary">
                {{ isEdit ? 'Atualizar' : 'Criar' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <div v-if="visible" class="modal-backdrop fade show"></div>
</template>

<script setup lang="ts">
import type { Task } from "@/types/task";
import { computed, reactive, watch } from "vue";

const props = defineProps({
  visible: Boolean,
  task: Object as () => Task | null
});

const emit = defineEmits(["save", "close"]);

const form = reactive({
  title: "",
  description: "",
  due_date: ""
});

const isEdit = computed(() => !!props.task);

watch(
  () => props.task,
  (task) => {
    form.title = task?.title ?? "";
    form.description = task?.description ?? "";
    
    const dueDate = task?.due_date;
    form.due_date = dueDate ? new Date(dueDate).toISOString().split('T')[0]! : "";
  },
  { immediate: true }
);

function submit() {
  emit("save", { ...form });
}
</script>