<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use App\Models\Project;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ProjectController extends Controller
{
    public function show(int $id): JsonResponse
    {
        $project = Project::find($id);

        if (!$project) {
            return response()->json(['message' => 'Project not found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($project);
    }

    public function getSimpleTasksList(Request $request, int $id): JsonResponse
    {
        $project = Project::find($id);

        if (!$project) {
            return response()->json(['message' => 'Project not found'], Response::HTTP_NOT_FOUND);
        }

        $tasks = $project->tasks()->select('id', 'name', 'user_id', 'status');

        if ($request->has('user_id')) {
            $tasks->where('user_id', $request->user_id);
        }

        return response()->json($tasks->get());
    }

    public function store(ProjectRequest $request): JsonResponse
    {
        try {
            $project = Project::create($request->validated());

            if (!$project) {
                return response()->json(['error' => 'Failed to create project'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return response()->json($project, Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create project', 'message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function update(ProjectRequest $request, int $id): JsonResponse
    {
        $project = Project::find($id);

        if (!$project) {
            return response()->json(['message' => 'Project not found'], Response::HTTP_NOT_FOUND);
        }

        try {
            $updatedProject = $project->update($request->validated());

            if (!$updatedProject) {
                return response()->json(['error' => 'Project not updated'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return response()->json($project, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error updating the project'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(int $id) {
        $project = Project::find($id);

        if (!$project) {
            return response()->json(['error' => 'Project not found'], Response::HTTP_NOT_FOUND);
        }

        if ($project->delete()) {
            return response()->json(['success' => true], Response::HTTP_OK);
        } else {
            return response()->json(['error' => 'Error deleting the project'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
