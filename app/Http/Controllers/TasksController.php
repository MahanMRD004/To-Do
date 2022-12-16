<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TodoList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TasksController extends Controller
{
    public function addTask(request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:25',
            'date' => 'date'
        ]);
        $title = $request->title;
        $date = $request->date;
        if ($request->id == null) {
            $newList = new TodoList();
            $newList -> title = 'Tasks';
            $newList -> user_id = Auth::user()->id;
            $newList -> save();
            $request->id = TodoList::where('user_id', Auth::user()->id)->first()->id;
        }
        $todo_list_id = $request->id;
        $newTask = new Task();
        $newTask -> title = $title;
        $newTask -> date = $date;
        $newTask -> todo_list_id = $todo_list_id;
        $newTask -> save();

        return redirect()->back();
    }

    public function removeTask(request $request)
    {
        $id = $request ->id;
        Task::findOrFail($id)->delete();
    }

    public function check(request $request)
    {
        $id = $request -> id;
        $value = $request -> value;
        $task = Task::findOrFail($id);
        $task->status = $value;
        $task->save();
    }


    public function setPriority(request $request)
    {
        $id = $request -> id;
        $value = $request -> value;
        $task = Task::findOrFail($id);
        $task->priority = $value;
        $task->save();
    }
}
