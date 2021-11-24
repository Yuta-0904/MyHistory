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

    // public function get(){
    //     $taskCards = TaskCard::where('user_id',Auth::id())->get()->sortByDesc('created_at');
    //     //log::info($taskCards);
        
        
    //     return response()->json(['taskCards' => $taskCards],201);
    // }

    public function create(TaskCardRequest $request, TaskCard $taskCard)
    {
        $user_id = $request->user()->id;
        $status = $request->status;
        
        switch ($status){
            case '未着手':
                $status = 0;
                break;
            case '対応中':
                $status = 1;
                break;
            case '保留':
                $status = 2;
                break;
            default:
                $status = 3;
        }
        
        $taskCard->fill([
            'user_id' => $user_id,
            'list_id' => $request->list_id,
            'name' => $request->name,
            'content' => $request->content,
            'status' => $status,
            'limit' => $request->limit,
        ]);

        $taskCard->save();
        return response()->json(['taskCard' => $taskCard],201);
    }
}
