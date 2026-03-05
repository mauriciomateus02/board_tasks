import type { Task } from "@/types/task";
import http from "../interceptors/auth.interceptors";
import type { PaginateResponse } from "../dto/paginate-response.dto";

export class TaskService {

    async getTasks(page: number) {
        const response = await http.get<PaginateResponse<Task>>(`tasks?page=${page}`);
        return response.data
    }

    createTask(task: Partial<Task>) {
        return http.post<Task>("/tasks", task);
    }

    updateTask(id: number, task: Partial<Task>) {
        return http.put<Task>(`/tasks/${id}`, task);
    }

    deleteTask = (id: number) => {
        return http.delete(`/tasks/${id}`);
    }

    async completeTask(id: number) {
        const response = await http.patch(`/tasks/${id}/complete`)
        console.log(response)
        return response.data
    }
}