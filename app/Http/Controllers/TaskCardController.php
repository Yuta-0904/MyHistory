<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskCardRequest;
use App\TaskCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TaskCardController extends Controller
{
    public function __construct()
    {
        //このコントローラー内のアクションは全て認証が必要になる
        $this->middleware('auth');
    }

    public function get(){
        $taskCards = TaskCard::where('user_id',Auth::id())->get()->sortByDesc('created_at');
        //log::info($taskCards);
        
        
        return response()->json(['taskCards' => $taskCards],201);
    }

    public function create(TaskListRequest $request, TaskCard $taskCard)
    {
        $taskCard->fill($request->all());
        $taskCard->user_id = $request->user()->id;
        $taskCard->save();
        return response()->json(['taskCard' => $taskCard],201);
    }
}
