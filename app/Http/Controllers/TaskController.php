<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Cek role pengguna
        if ($user->role === 'admin') {
            $tasks = Task::query();
        } else {
            $tasks = Task::where('user_id', $user->id);
        }

        // Filter berdasarkan status
        if ($request->has('status')) {
            $tasks->where('status', $request->status);
        }

        // Urutkan tugas berdasarkan tanggal pembuatan terbaru
        $tasks->orderBy('created_at', 'desc');

        // Pagination
        return response()->json($tasks->paginate(6), 200);
    }

    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:completed,incomplete',
        ]);

        // Buat tugas baru
        $task = Task::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'status' => $validated['status'],
            'user_id' => Auth::id(),
        ]);

        return response()->json(['message' => 'Task created successfully', 'task' => $task], 201);
    }

    public function show(Task $task)
    {
        $user = Auth::user();

        // Cek apakah user memiliki akses ke tugas
        if ($user->role !== 'admin' && $task->user_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json($task, 200);
    }

    public function update(Request $request, Task $task)
    {
        $user = Auth::user();

        // Cek apakah user memiliki akses ke tugas
        if ($user->role !== 'admin' && $task->user_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Validasi input
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'status' => 'sometimes|in:completed,incomplete',
        ]);

        // Update tugas
        $task->update($validated);

        return response()->json(['message' => 'Task updated successfully', 'task' => $task], 200);
    }

    public function destroy(Task $task)
    {
        $user = Auth::user();

        // Cek apakah user memiliki akses ke tugas
        if ($user->role !== 'admin' && $task->user_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Hapus tugas
        $task->delete();

        return response()->json(['message' => 'Task deleted successfully'], 200);
    }

}
