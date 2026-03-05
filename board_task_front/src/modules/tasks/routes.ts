import TasksView from "./views/TasksView.vue"
import { authGuard } from "../../core/guards/auth.guard"

export default [
  {
    path: "/tasks",
    component: TasksView,
    beforeEnter: authGuard
  }
]