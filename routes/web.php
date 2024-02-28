<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Models\Task;
use App\Http\Requests\TaskRequest;
use App\Http\Controllers\TaskController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get("/tasks", function () {
    return view('tasks.index', ['tasks' => Task::latest()->paginate(10)]);
})->name('tasks.index');

Route::get("/", function () {
    return redirect()->route('tasks.index');
});

Route::view('/tasks/create', 'tasks.create')->name('tasks.create');

Route::get('/tasks/{task}/edit', function (Task $task) {
    return view('tasks.edit', ['task' => $task]);
})->name('tasks.edit');

Route::get('/tasks/{task}', function (Task $task) {
    return view('tasks.show', ['task' => $task]);
})->name('tasks.show');

Route::post('/tasks', function (TaskRequest $taskRequest) {
    $task = Task::create($taskRequest->validated());

    return redirect()->route('tasks.show', ['task' => $task->id])->with('success', 'Task created successfully');
})->name('tasks.store');

Route::put('/tasks/{task}', function (Task $task, TaskRequest $taskRequest) {
    $task->update($taskRequest->validated());

    return redirect()->route('tasks.show', ['task' => $task->id])->with('success', 'Task updated successfully');
})->name('tasks.update');

Route::delete('/tasks/{task}', function (Task $task) {
    $task->delete();

    return redirect()->route('tasks.index')->with('success', 'Task deleted successfully');
})->name('tasks.delete');

Route::put('tasks/{task}/toggle-complete', function (Task $task) {
    $task->toggleComplete();

    return redirect()->back()->with('success', 'Task updated successfully');
})->name('tasks.toggle-complete');

Route::fallback(function () {
    return abort(404);
});
