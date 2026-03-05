<script setup lang="ts">
import { ref, onMounted } from "vue";

import ConfirmModal from "@/components/ConfirmModal.vue";
import TaskModal from "@/components/TaskModal.vue";
import NotificationModal from "@/components/NotificationModal.vue";
import Toast from "@/components/Toast.vue";
import type { Task } from "@/types/task";
import type { NotificationSetting } from "@/types/notification-setting";
import { TaskService } from "@/core/services/task.service";
import { NotificationService } from "@/core/services/notification.service";
import router from "@/router";

const tasks = ref<Task[]>([]);
const page = ref(1);
const totalPages = ref(1);

const showTaskModal = ref(false);
const editingTask = ref<Task | null>(null);

const showNotificationModal = ref(false);
const notificationSetting = ref<NotificationSetting | null>(null);

const showModal = ref(false);
const modalMessage = ref("");
const action = ref<() => void>(() => {});

const toastMessage = ref("");
const toastType = ref<'success' | 'error'>('success');
const _showToast = ref(false);

const taskService = new TaskService();
const notificationService = new NotificationService();

async function loadTasks() {
  try {
    const data = await taskService.getTasks(page.value);
    tasks.value = data.data;
    console.log(tasks.value)
    totalPages.value = data.last_page;
  } catch (error: any) {
    if (error.response?.status === 401) {
      localStorage.removeItem('token');
      router.push({ name: 'login' });
      showToast("Sessão expirada, faça login novamente", false);
    } else {
      showToast("Erro ao carregar tarefas", false);
    }
  }
}

async function loadNotificationSetting() {
  try {
    notificationSetting.value = await notificationService.getSetting();
  } catch (error: any) {
    if (error.response?.status === 404) {
      notificationSetting.value = null; // Não existe configuração
    } else {
      showToast("Erro ao carregar configuração de notificação", false);
    }
  }
}

function confirmDelete(task: Task) {
  modalMessage.value = `Deseja excluir a tarefa "${task.title}"?`;
  action.value = async () => {
    try {
      await taskService.deleteTask(task.id);
      showModal.value = false;
      loadTasks();
      showToast("Tarefa excluída com sucesso", true);
    } catch (error) {
      showToast("Erro ao excluir tarefa", false);
    }
  };
  showModal.value = true;
}

function confirmComplete(task: Task) {
  modalMessage.value = `Deseja marcar "${task.title}" como completa?`;
  action.value = async () => {
    try {
      await taskService.completeTask(task.id);
      showModal.value = false;
      loadTasks();
      showToast("Tarefa marcada como completa", true);
    } catch (error) {
      showToast("Erro ao completar tarefa", false);
    }
  };
  showModal.value = true;
}

function changePage(p: number) {
  page.value = p;
  loadTasks();
}

function openCreate() {
  editingTask.value = null;
  showTaskModal.value = true;
}

function openEdit(task: Task) {
  editingTask.value = task;
  showTaskModal.value = true;
}

function openNotificationSettings() {
  showNotificationModal.value = true;
}

async function saveTask(data: any) {
  try {
    if (editingTask.value) {
      await taskService.updateTask(editingTask.value.id, data);
      showToast("Tarefa atualizada com sucesso", true);
    } else {
      await taskService.createTask(data);
      showToast("Tarefa criada com sucesso", true);
    }
    showTaskModal.value = false;
    loadTasks();
  } catch (error) {
    showToast("Erro ao salvar tarefa", false);
  }
}

async function saveNotificationSetting(setting: NotificationSetting) {
  notificationSetting.value = setting;
  showNotificationModal.value = false;
  showToast("Configuração de notificação salva", true);
}

function showToast(message: string, isSuccess: boolean) {
  toastMessage.value = message;
  toastType.value = isSuccess ? 'success' : 'error';
  _showToast.value = true;
  setTimeout(() => _showToast.value = false, 3000);
}


onMounted(() => {
  loadTasks();
  loadNotificationSetting();
});
</script>

<template>
  <div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h1>Tasks</h1>
      <div>
        <button @click="openNotificationSettings()" class="btn btn-info me-2">
          Configurações de Notificação
        </button>
        <button @click="openCreate()" class="btn btn-primary">
          Criar Tarefa
        </button>
      </div>
    </div>

    <div v-if="tasks.length === 0" class="alert alert-info">
      Nenhuma tarefa encontrada.
    </div>

    <div v-else class="table-responsive">
      <table class="table table-striped table-hover">
        <thead class="table-dark">
          <tr>
            <th>Título</th>
            <th>Descrição</th>
            <th>Data de Vencimento</th>
            <th>Status</th>
            <th style="min-width: 200px;">Ações</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="task in tasks" :key="task.id">
            <td>{{ task.title }}</td>
            <td>{{ task.description }}</td>
            <td>{{ new Date(task.due_date).toLocaleDateString() }}</td>
            <td>
              <span v-if="task.is_completed" class="badge bg-success">Completa</span>
              <span v-else class="badge bg-warning">Pendente</span>
            </td>
            <td>
              <button v-if="!task.is_completed" @click="confirmComplete(task)" class="btn btn-success btn-sm me-2">
                Concluir
              </button>
              <button @click="openEdit(task)" class="btn btn-warning btn-sm me-2">
                Editar
              </button>
              <button @click="confirmDelete(task)" class="btn btn-danger btn-sm">
                Excluir
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <nav v-if="totalPages > 1" aria-label="Paginação">
      <ul class="pagination justify-content-center">
        <li class="page-item" :class="{ disabled: page === 1 }">
          <button class="page-link" @click="changePage(page - 1)" :disabled="page === 1">Anterior</button>
        </li>
        <li v-for="p in totalPages" :key="p" class="page-item" :class="{ active: p === page }">
          <button class="page-link" @click="changePage(p)">{{ p }}</button>
        </li>
        <li class="page-item" :class="{ disabled: page === totalPages }">
          <button class="page-link" @click="changePage(page + 1)" :disabled="page === totalPages">Próximo</button>
        </li>
      </ul>
    </nav>

    <ConfirmModal
      :visible="showModal"
      title="Confirmação"
      :message="modalMessage"
      @cancel="showModal = false"
      @confirm="action()"
    />

    <TaskModal
      :visible="showTaskModal"
      :task="editingTask"
      @save="saveTask"
      @close="showTaskModal = false"
    />
    <NotificationModal
      :visible="showNotificationModal"
      :setting="notificationSetting"
      @save="saveNotificationSetting"
      @close="showNotificationModal = false"
    />
    <Toast
      :message="toastMessage"
      :type="toastType"
      :visible="_showToast"
      @close="_showToast = false"
    />
  </div>
</template>