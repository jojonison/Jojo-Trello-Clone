<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function createProject(CreateProjectRequest $request) {
        $user = $request->user();
        $project = Project::create([
            'project_name' => $request->input('project_name'),
            'owned_by' => $user->id
        ]);
        return response()->json(ProjectResource::make($project));
    }

    public function updateProject(UpdateProjectRequest $request, $id) {
        $project = Project::find(Project::hashToId($id));
        $project->update([
            'project_name' => $request->input('project_name'),
        ]);
        return response()->json(ProjectResource::make($project));
    }

    public function archiveProject($id) {
        $project = Project::byHashOrFail($id);
        $project->delete();
        return response()->json(['message' => 'Project has been archived']);
    }

    public function unarchiveProject($id) {
        $project = Project::withTrashed()->find(Project::hashToId($id));
        $project->restore();
        return response()->json(['message' => 'Project has been unarchived']);
    }

    public function listProjects(Request $request) {
        $project = Project::where('owned_by', $request->user()->id)->orderByRaw("id ASC")->get();
        return response()->json(ProjectResource::collection($project));
    }

    public function showProject(Request $request, $id) {
        $project = Project::byHashOrFail($id);
        return response()->json(ProjectResource::make($project));
    }

    public function listArchivedProjects(Request $request) {
        $project = Project::onlyTrashed()
            ->where('owned_by', $request->user()->id)->orderByRaw("id ASC")
            ->get();
        return response()->json(ProjectResource::collection($project));
    }
}
