<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\TaskListRequest;
use App\TaskList;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class TaskListController extends Controller
{
    public function __construct()
    {
        //このコントローラー内のアクションは全て認証が必要になる
        $this->middleware('auth');
    }

    public function get()
    {
        //認証ユーザの値のみ取得
        $taskLists = TaskList::all()->where('user_id',Auth::id());
        foreach ($taskLists as $taskList) {
            ///リストに紐づいているタスクカードも取得
            $taskList->cards->sortByDesc('created_at');
        }
      
        return response()->json(['taskList' => $taskLists],201);
    }

    public function create(TaskListRequest $request, TaskList $taskList)
    {
        
        $taskList->fill($request->all());
        $taskList->user_id = $request->user()->id;
        $taskList->save();

        return response()->json(['taskList' => $taskList],201);
    }

    public function delete(TaskList $taskList)
    {        
        $taskList->cards()->each(function ($cards) {
            $cards->delete();
        });
        
        $taskList->delete();
        return response()->json(['message' => '削除が完了しました'],201);
    }
}
