<?php

use App\Http\Requests\TaskRequest;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Task;

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



Route::get('/', function () {
  return redirect()->route('tasks.index');
});

Route::get('/tasks', function () {
  return view('index', ['tasks' => Task::latest()->paginate(10)]);
  //->where('completed', true)->get()
})->name('tasks.index');

Route::view('/tasks/create', 'create')->name('tasks.create');

Route::get('/tasks/{task}/edit', function (Task $task) {
  return view('edit', ['task' => $task]);
})->name('tasks.edit');

Route::get('/tasks/{task}', function (Task $task) {
  return view('show', ['task' => $task]);
})->name('tasks.show');

Route::post('/tasks', function (TaskRequest $request) {

  $data = $request->validated();
  $task = Task::create($request->validated());

  return redirect()->route('tasks.show', ['task' => $task->id])
    ->with('success', 'Task Created Successfully!');
})->name('tasks.store');


Route::put('/tasks/{task}', function (Task $task, TaskRequest $request) {
  $data = $request->validated();


  $task->update($request->validated());

  return redirect()->route('tasks.show', ['task' => $task->id])
    ->with('success', 'Task Updated Successfully!');
})->name('tasks.update');


Route::delete('/tasks/{task}', function (Task $task) {
  $task->delete();

  return redirect()->route('tasks.index')
    ->with('success', 'Task Deleted Successfully!');
})->name('tasks.destroy');


Route::put('tasks/{task}/toggle-complete', function (Task $task) {
  $task->toggleComplete();

  return redirect()->back()->with('success', 'Task Updated Successfully!');
})->name('tasks.toggle-complete');

// Route::get('/hello', function () {
//     return 'Hello Page';
// })->name('hello');

// Route::get('/hallo', function () {
//     return redirect()->route('hello');
// });

// Route::get('/greet/{name}', function ($name) {
//     return 'Hello ' . $name . '!';
// });

Route::fallback(function () {
  return 'Still got somewhere';
});
