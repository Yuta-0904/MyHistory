<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\TaskListRequest;
use App\TaskList;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TaskListController extends Controller
{
    public function __construct()
    {
        //このコントローラー内のアクションは全て認証が必要になる
        $this->middleware('auth');
    }

    public function get()
    {
        $taskList = TaskList::where('user_id',Auth::id())->get()->sortByDesc('created_at');
        return response()->json(['taskList' => $taskList],201);
    }

    public function create(TaskListRequest $request, TaskList $taskList)
    {
        
        $taskList->fill($request->all());
        $taskList->user_id = $request->user()->id;
        $taskList->save();

        return response()->json(['taskList' => $taskList],201);
    }
}
