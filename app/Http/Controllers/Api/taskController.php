<?php

namespace App\Http\Controllers\Api;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{

    public function index()
    {
        $tasks = Task::with('incidence')->get();


        if ($tasks->isEmpty()) {
            return response()->json(['message' => 'No existen tareas'], 404);
        }

        $tasks = $tasks->map(function ($task) {
            return [
                'id' => $task->id,
                'incidence_id' => $task->incidence->id,
                'title' => $task->title,
                'description' => $task->description,
                'status' => $task->status,
                'notes' => $task->notes,
                'price' => $task->price,
                'assumed_by' => $task->assumed_by,
            ];
        });

        return response()->json($tasks, 200);
    }

    public function store(Request $request)
    {
        $request->merge(['assumed_by' => trim(strtolower($request->assumed_by))]);

        $validated = Validator::make($request->all(), [
            'incidence_id' => 'required|exists:incidences,id',
            'title' => 'required|string',
            'description' => 'string',
            'price' => 'numeric',
            'assumed_by' => 'required|in:client,owner,homeselect',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'message' => 'Validación fallida',
                'errors' => $validated->errors(),
                'status' => 400,
            ], 400);
        }

        $task = Task::create([
            'incidence_id' => $request->incidence_id,
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'assumed_by' => $request->assumed_by,
        ]);

        if (!$task) {
            $data = [
                'message' => 'Tarea no se puedo crear',
                'status' => 500
            ];
            return response()->json($data, 500);
        }

        $data = [
            'message' => 'Tarea creada',
            'status' => 201
        ];

        return response()->json($data, 201);
    }

    public function show($id)
    {
        return Task::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        $request->merge(['assumed_by' => trim(strtolower($request->assumed_by))]);

        $validated = Validator::make($request->all(), [
            'incidence_id' => 'exists:incidences,id',
            'title' => 'string',
            'description' => 'string',
            'status' => 'in:pending,assigned,solved,closed',
            'price' => 'numeric',
            'assumed_by' => 'in:client,owner,homeselect',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'message' => 'Validación fallida',
                'errors' => $validated->errors(),
                'status' => 400,
            ], 400);
        }

        $task->update([
            'incidence_id' => $request->incidence_id,
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
            'price' => $request->price,
            'assumed' => $request->assumed_by,
        ]);

        return response()->json(['message' => 'Tarea actualizada'], 200);

    }

    public function updateFinish(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        $validated = Validator::make($request->all(), [
            'status' => 'required|in:solved,closed',
            'notes' => 'required|string',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'message' => 'Validación fallida',
                'errors' => $validated->errors(),
                'status' => 400,
            ], 400);
        }

        $task->update([
            'status' => $request->status,
            'notes' => $request->notes,
        ]);

        return response()->json(['message' => 'Tarea finalizada'], 200);
    }

    public function destroy($id)
    {
        Task::destroy($id);
        return response()->json(['message' => 'Tarea eliminada'], 200);
    }
}
