<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class TaskController extends Controller
{
    public function store(TaskRequest $request, int $projectId): JsonResponse
    {
        if (!Project::find($projectId)) {
            return response()->json(['error' => 'Project not found'], Response::HTTP_NOT_FOUND);
        }

        $request->merge(['project_id' => $projectId]);

        try {
            $task = Task::create($request->validated());

            if (!$task) {
                return response()->json(['error' => 'Failed to create task'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return response()->json($task, Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error creating the task'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(TaskRequest $request, int $projectId, int $taskId): JsonResponse
    {
        $task = Task::where('id', $taskId)
            ->where('project_id', $projectId)
            ->first();

        if (!$task) {
            return response()->json(['error' => 'Task not found'], Response::HTTP_NOT_FOUND);
        }

        try {
            $updatedTask = $task->update($request->validated());

            if (!$updatedTask) {
                return response()->json(['error' => 'Task not updated'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return response()->json($task, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error updating the task'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(int $projectId, int $taskId): JsonResponse
    {
        $task = Task::where('id', $taskId)
            ->where('project_id', $projectId)
            ->first();

        if (!$task) {
            return response()->json(['error' => 'Task not found'], Response::HTTP_NOT_FOUND);
        }

        if ($task->delete()) {
            return response()->json(['success' => true], Response::HTTP_OK);
        } else {
            return response()->json(['error' => 'Error deleting the task'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
