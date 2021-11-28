<?php

namespace App\Http\Controllers;

use App\Http\Requests\LearnCardRequest;
use App\LearnCard;
use App\LearnList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class LearnCardController extends Controller
{
    public function __construct()
    {
        //このコントローラー内のアクションは全て認証が必要になる
        $this->middleware('auth');
    }
    
    public function create(LearnCardRequest $request, LearnCard $learnCard)
    {
        $user_id = $request->user()->id;
        $status = $request->status;
        switch ($status){
            case '未着手':
                $status = 0;
                break;
            case '学習中':
                $status = 1;
                break;
            case '保留':
                $status = 2;
                break;
            default:
                $status = 3;
        }

        $list_name = $request->list_name;
        $list = LearnList::where('name',$list_name)->first();
        $list_id = $list->id;
        
        $learnCard->fill([
            'user_id' => $user_id,
            'list_id' => $list_id,
            'name' => $request->name,
            'content' => $request->content,
            'status' => $status,
        ]);

        $learnCard->save();
        return response()->json(['learnCard' => $learnCard],201);
    }

    public function show(LearnCard $learnCard)
    {    
        return response()->json(['learnCard' => $learnCard],200);
    }

    public function delete(LearnCard $learnCard)
    {         
        $learnCard->delete();
        return response()->json(['message' => '削除が完了しました'],201);
    }

    public function update(LearnCardRequest $request,LearnCard $learnCard)
    {         
        log::info($request);
        $status = $request->status;
        switch ($status){
            case '未着手':
                $status = 0;
                break;
            case '学習中':
                $status = 1;
                break;
            case '保留':
                $status = 2;
                break;
            default:
                $status = 3;
        }
        

        $learnCard->update([
            'name' => $request->name,
            'content' => $request->content,
            'status' => $status,
        ]);
        return response()->json(['learnCard' => $learnCard],201);
    }
}
