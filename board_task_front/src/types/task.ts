export interface Task {
  id: number
  title: string
  description?: string
  due_date: string
  is_completed: boolean
  user_id: number
  created_at: string
  updated_at: string
}