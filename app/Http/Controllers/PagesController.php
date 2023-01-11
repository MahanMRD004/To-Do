<?php

namespace App\Http\Controllers;
use App\Models\Theme;
use Carbon\Carbon;
use App\Models\Task;
use App\Models\TodoList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Expr\List_;

class PagesController extends Controller
{
    public function index()
    {
       return redirect('login');
    }

    public function myDay()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $listTitle = "My Day";
            $todo_list_id = null;
            $lists = TodoList::where('user_id',$user->id)->with('tasks')->get();
            $tasks = [];
            foreach ($lists as $list) {
                $listTasks = $list->tasks->where('date',Carbon::today()->Format('Y-m-d'));
                foreach ($listTasks as $task) {
                    array_push($tasks, $task);
                }
            }
            $date = Carbon::today()->isoFormat('dddd, MMMM D');
            $themes = Theme::all();
            $currentTheme = Theme::where('name', 'myday')->first();
            $greeting = ['img' => 'myday.png', 'text' => 'Get thing done with MyDay, a list that refreshes every day'];
            return view('index', compact('tasks','listTitle', 'lists','date','todo_list_id','user','themes','currentTheme', 'greeting'));
        }
        return redirect(route('login'));
    }

    public function all()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $listTitle = "All";
            $todo_list_id = null;
            $lists = TodoList::where('user_id',$user->id)->with('tasks')->get();
            $tasks = [];
            foreach ($lists as $list) {
                $listTasks = $list->tasks;
                foreach ($listTasks as $task) {
                    array_push($tasks, $task);
                }
            }
            $date = Carbon::today()->isoFormat('dddd, MMMM D');
            $themes = Theme::all();
            $currentTheme = Theme::where('name', 'all')->first();
            $greeting = ['img' => 'all.png', 'text' => 'You have nothing left to do, you\'ll see all your tasks here' ];
            return view('index', compact('tasks','listTitle', 'lists','date','todo_list_id','user','themes','currentTheme', 'greeting'));
        }
        return redirect(route('login'));
    }

    public function complete()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $listTitle = "Complete";
            $todo_list_id = null;
            $lists = TodoList::where('user_id',$user->id)->get();
            $tasks = [];
            foreach ($lists as $list) {
                $listTasks = $list->tasks->where('status', 1);
                foreach ($listTasks as $task) {
                    array_push($tasks, $task);
                }
            }
            $date = Carbon::today()->isoFormat('dddd, MMMM D');
            $themes = Theme::all();
            $currentTheme = Theme::where('name', 'complete')->first();
            $greeting = ['img' => 'complete.png', 'text' => 'There is no completed task, Stop being lazy :/' ];
            return view('index', compact('tasks','listTitle', 'lists','date','todo_list_id','user','themes','currentTheme', 'greeting'));
        }
        return redirect(route('login'));
    }

    public function list($id)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $todo_list_id = $id;
            $selectedList = TodoList::with('tasks','theme')->findOrFail($id);
            $listTitle = $selectedList->title;
            $lists = TodoList::where('user_id',$user->id)->get();
            $tasks = $selectedList->tasks;
            $date = Carbon::today()->isoFormat('dddd, MMMM D');
            $themes = Theme::all();
            $currentTheme = Theme::where('name', $selectedList->theme->name)->first();
            $greeting = ['img' => 'list.png', 'text' => 'There is no task, Wanna add some?' ];
            if ($selectedList->user_id != Auth::user()->id) {
                return redirect(route('myDay'));
            }
            return view('index', compact('tasks','listTitle', 'lists','date','todo_list_id','user','themes','currentTheme', 'greeting'));
        }
        return redirect(route('login'));
    }

    public function test()
    {
        return Auth::user()->id;
    }
}
