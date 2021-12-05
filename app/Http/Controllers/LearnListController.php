<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LearnListRequest;
use App\LearnList;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class LearnListController extends Controller
{
    public function get()
    {
        //認証ユーザの値のみ取得
        $learnLists = LearnList::all()->where('user_id',Auth::id());

     
        foreach ($learnLists as $learnList) {
            ///リストに紐づいているタスクカードも取得
            $learnList->cards->sortByDesc('created_at');

            foreach($learnList->cards as $learnCard) {
                // $carbon = new Carbon($learnCard->created_at);
                $carbon = new Carbon($learnCard->created_at, 'Asia/Tokyo');
                $learnCard->date = $carbon->format('Y年m月d日');
                     
            }
        }

       
      
        return response()->json(['learnList' => $learnLists],201);
    }

    public function create(LearnListRequest $request, LearnList $learnList)
    {
        
        $learnList->fill($request->all());
        $learnList->user_id = $request->user()->id;
        $learnList->save();

        return response()->json(['taskList' => $learnList],201);
    }

    public function delete(LearnList $learnList)
    {        
        $learnList->cards()->each(function ($cards) {
            $cards->delete();
        });
        
        $learnList->delete();
        return response()->json(['message' => '削除が完了しました'],201);
    }
}
