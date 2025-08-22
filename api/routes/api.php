<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\ProjectController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function() {
    Route::post('/create-task', [TaskController::class, 'createTask'])->name("task.create");
    Route::put('/task-update/{id}', [TaskController::class, 'updateTask'])->name("task.update");
    Route::put('/update-status/{id}', [TaskController::class, 'updateStatus'])->name("task.update.status");
    Route::delete('/removeTask/{id}', [TaskController::class, 'removeTask'])->name("task.delete");
    Route::post('/tasks-restore/{id}', [TaskController::class, 'restoreTask'])->name("task.restore");
    Route::patch('/task-complete/{id}', [TaskController::class, 'completeTask'])->name("task.complete");
    Route::get('/finished-tasks', [TaskController::class, 'showFinishedTasks'])->name("task.show.finished");
    Route::get('/unfinished-tasks', [TaskController::class, 'showUnfinishedTasks'])->name("task.show.unfinished");
    Route::post('/tasks/export', [TaskController::class, 'export'])->name("task.export");
    Route::get('/check-export/{id}', [TaskController::class, 'checkExport'])->name("download.json");

    Route::get('/listProjects', [ProjectController::class, 'listProjects'])->name('projects.list');
    Route::get('/listArchivedProjects', [ProjectController::class, 'listArchivedProjects'])->name('projects.list.archived');
    Route::get('/showProject/{id}', [ProjectController::class, 'showProject'])->name('project.show');
    Route::post('/createProject', [ProjectController::class, 'createProject'])->name('project.create');
    Route::put('/updateProject/{id}', [ProjectController::class, 'updateProject'])->name('project.update');
    Route::delete('/archiveProject/{id}', [ProjectController::class, 'archiveProject'])->name('project.archive');
    Route::post('/unarchiveProject/{id}', [ProjectController::class, 'unarchiveProject'])->name('project.unarchive');

    Route::post('/logOut', [AuthenticationController::class, 'logOut']);
});

Route::post('/logIn', [AuthenticationController::class, 'logIn']);
