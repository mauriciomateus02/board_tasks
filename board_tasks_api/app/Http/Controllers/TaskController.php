<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TaskController extends Controller
{

    use AuthorizesRequests;

    public function index(Request $request)
    {
        $tasks = Task::where('user_id', $request->user()->id)
            ->orderBy('due_date')
            ->paginate(10);

        return response()->json($tasks);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
        ]);

        $data['user_id'] = $request->user()->id;
        $data['is_completed'] = false;
        $task = Task::create($data);

        return response()->json($task);
    }

    public function show(Task $task)
    {
        $this->authorize('view', $task);

        return response()->json($task);
    }

    public function update(Request $request, Task $task)
    {
        $this->authorize('update', $task);

        $data = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'sometimes|date',
        ]);

        $task->update($data);

        return response()->json($task);
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);

        $task->delete();

        return response()->json([
            'message' => 'Tarefa excluida.'
        ]);
    }

    public function complete($id)
    {
        $task = Task::find($id);
        if (!$task) {
            return response()->json(['error' => 'Tarefa não encontrada.'], 404);
        }
        $task->is_completed = true;
        $task->save();
        return response()->json($task);
    }
}
