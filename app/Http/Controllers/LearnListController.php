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
        }
      
        return response()->json(['learnList' => $learnLists],201);
    }
}
