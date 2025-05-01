<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index(Request $request, $projectId)
    {
        $query = Task::with('users')->where('project_id', $projectId);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        return response()->json($query->get());
    }

    public function store(Request $request, $projectId)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'status' => 'nullable|in:Pendiente,En progreso,Completada',  // Validar el estado
        ]);
    
        // Usar el estado proporcionado o asignar 'Pendiente' por defecto si no se pasa un valor
        $status = $request->status ?? 'Pendiente';
    
        $task = Task::create([
            'name' => $request->name,
            'description' => $request->description,
            'status' => $status,  // Asignar el estado recibido o 'Pendiente' por defecto
            'project_id' => $projectId,
        ]);
    
        $task->users()->sync($request->user_ids); 
    
        return response()->json($task, 201);
    }

    public function show($id)
    {
        $task = Task::with('users')->findOrFail($id);
        return response()->json($task);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|nullable|string',
            'status' => 'sometimes|in:Pendiente,En progreso,Completada',
            'user_ids' => 'sometimes|array', 
            'user_ids.*' => 'exists:users,id', 
        ]);

        $task = Task::findOrFail($id);
        $task->update($request->all());

        if ($request->has('user_ids')) {
            $task->users()->sync($request->user_ids); 
        }

        return response()->json($task);
    }

    public function destroy($id)
    {
        Task::destroy($id);
        return response()->json(['message' => 'Task deleted successfully']);
    }

    public function addUsersToTask(Request $request, $taskId)
{
    $request->validate([
        'user_ids' => 'required|array',
        'user_ids.*' => 'exists:users,id',
    ]);

    $task = Task::findOrFail($taskId);
    $task->users()->sync($request->user_ids); // Reemplaza todos los usuarios asignados

    return response()->json(['message' => 'Usuarios asignados correctamente', 'task' => $task->load('users')]);
}

}
