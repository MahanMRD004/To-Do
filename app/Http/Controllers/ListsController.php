<?php

namespace App\Http\Controllers;

use App\Models\TodoList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ListsController extends Controller
{
    public function addList(request $request)
    {
        $this->validate($request, [
            'title' => 'required'
        ]);
        $title = $request->title;
        $newList = new TodoList();
        $newList -> title = $title;
        $newList -> user_id = Auth::user()->id;
        $newList -> save();

        return redirect()->back();
    }


    public function removeList(request $request)
    {
        $id = $request ->id;
        TodoList::findOrFail($id)->delete();
    }

    public function setTheme(request $request)
    {
        $this->validate($request, [
            'selected' => 'required',
            'id' => 'required'
        ]);

        $id = $request->id;
        $selectedList = TodoList::findOrFail($id);
        $selectedList -> theme_id = $request->selected;
        $selectedList->save();

        return redirect()->back();

    }

}
