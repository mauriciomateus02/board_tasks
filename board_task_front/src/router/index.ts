import { createRouter, createWebHistory, type RouteRecordRaw } from "vue-router";
import { authGuard } from "../core/guards/auth.guard";

import LoginView from "../modules/users/views/LoginView.vue";
import TaskListView from "../modules/tasks/views/TaskListView.vue";

const routes: RouteRecordRaw[] = [
{
  path: "/login",
  name: "login",
  component: LoginView
},
{
  path: "/tasks",
  name: "tasks",
  component: TaskListView,
  beforeEnter: authGuard
}
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

export default router;