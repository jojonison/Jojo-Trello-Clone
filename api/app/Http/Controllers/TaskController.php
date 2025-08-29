<?php

namespace App\Http\Controllers;

//use App\Jobs\ExportTasks;
use App\Jobs\TaskExportJob;
//use Exception;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Http\Requests\CreateTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Requests\CompleteTaskRequest;
use App\Http\Resources\TaskResource;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class TaskController extends Controller
{
    public function showUnfinishedTasks(Request $request) {
        $result = Task::where(column: "completed_at", operator: "=", value: null)
            ->where("user_id", $request->user()->id)
            ->where("project_id", "=", Project::hashToId($request->input("project_id")))
            ->orderByRaw("id ASC")->get();
        return response()->json(TaskResource::collection($result));
    }

    public function showFinishedTasks(Request $request) {
        $result = Task::where(column: "completed_at", operator: "!=", value: null)
            ->where("user_id", $request->user()->id)
            ->where("project_id", "=", Project::hashToId($request->input("project_id")))
            ->orderByRaw("id ASC")->get();
        return response()->json(TaskResource::collection($result));
    }

    public function createTask(CreateTaskRequest $request) {
        $user = $request->user();
        $projectID = $request->input("project_id");
        $task = Task::create([
            "title" => $request->input("title"),
            "description" => $request->input("description"),
            "project_id" => Project::hashToId($projectID),
            "user_id" => $user->id,
            "status" => "not started"
        ]);
        return response()->json(TaskResource::make($task));
    }

    public function updateTask(UpdateTaskRequest $request, $id) {
        $task = Task::findOrFail(Task::hashToId($id));
        $task->update([
            "title" => $request->input("title"),
            "description" => $request->input("description")
        ]);
        if ($request->hasFile('sticker')) {
            try {
                $task->addMediaFromRequest('sticker')->toMediaCollection('sticker');
            } catch (FileDoesNotExist|FileIsTooBig) {}
        }
        return response()->json(TaskResource::make($task));
    }

    public function updateStatus(Request $request, $id) {
        $task = Task::findOrFail(Task::hashToId($id));
        $task->update([
            "status" => $request->input("status")
        ]);
        return response()->json(TaskResource::make($task));
    }

    public function completeTask(CompleteTaskRequest $request, $id) {
        $task = Task::byHashOrFail($id);
        $task->update(["completed_at" => Carbon::now('Asia/Manila')->toDateTimeString()]);
        return response()->json(TaskResource::make($task));
    }

    public function removeTask(Request $request, $id) {
        $task = Task::byHashOrFail($id);
        $task->delete();
        return response()->json(['message' => 'Task soft deleted']);
    }

    public function restoreTask(Request $request, $id) {
        $task = Task::withTrashed()->find(Task::hashToId($id));
        if (!$task || !$task->trashed()) {
            return response()->json(['error' => 'Task not found or not deleted'], 404);
        }
        $task->restore();
        return response()->json(['message' => 'Task restored', 'task' => $task]);
    }

    // TODO
    public function export(Request $request) {
        $exportId = uniqid();
        dispatch(new TaskExportJob($request->user()->id, $exportId));
        return response()->json(['export_id' => $exportId]);
    }

    // TODO
    public function checkExport($id) {
        $filepath = "public/task_export/$id.json";
        if (Storage::exists($filepath)) {
            return response()->json([
                'ready' => true,
                'download_url' => Storage::disk('public')->url("task_export/$id.json")
            ]);
        }
        return response()->json(['ready' => false]);
    }
}
