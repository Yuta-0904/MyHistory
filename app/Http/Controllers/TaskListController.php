<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Http\Requests\TaskListRequest;
use App\TaskList;
use App\TaskCard;
use App\Meeting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class TaskListController extends Controller
{
    public function __construct()
    {
        //このコントローラー内のアクションは全て認証が必要になる
        $this->middleware('auth');
    }

    public function get(Request $request)
    {

        $user = auth()->user();
        $noZoomCode = $user->zoom_code == null;
        $zoomOuthLink = 'https://zoom.us/oauth/authorize?'.http_build_query([
            'response_type'=>'code',
            'redirect_uri'=>env('APP_URL').'/api/zoomauth/check',
            'client_id'=>env('ZOOM_CLIENT_ID'),
        ]);

        $user = $this->checkRefresh();
        // $zoom_meeting_id = '83009445126';
        
        //$url = 'https://api.zoom.us/v2/past_meetings/'.$zoom_meeting_id.'';//開催後のミーディング情報取得できる。参加人数など。参加者名は取得できず。
        //$url = 'https://api.zoom.us/v2/users/'.$zoom_user->id.'/meetings'; //作成したミーディング一覧情報取得できる
        // $client = Http::withHeaders([
        //     'Authorization' => 'Bearer '.$user->access_token,
        // ])->get($url);

     

        //ミーティング時間編集api
        //$zoom_meeting_id = '82142939589'; ///編集対象のミーティングid
        //$url = 'https://api.zoom.us/v2/meetings/'.$zoom_meeting_id.'';
        
        // $client = Http::withHeaders([
        //     'Authorization' => 'Bearer '.$user->access_token,
        //     'Content-type' => 'application/json',
        // ])->patch($url,[
        //     'topic'=>'UpdateTest4',
        //     'type' => 2,
        //     'time_zone' => 'Asia/Tokyo',
        //     'start_time' => '2022-03-30T20:00:00Z',
        // ]);

        //ミーティング削除api
        // $zoom_meeting_id = '83922709504'; ///編集対象のミーティングid
        // $url = 'https://api.zoom.us/v2/meetings/'.$zoom_meeting_id.'';
        // $client = Http::withHeaders([
        //     'Authorization' => 'Bearer '.$user->access_token,
        //     'Content-type' => 'application/json',
        // ])->delete($url);


        ///リアルタイムのミーティング状況情報取得できるかテスト
        $zoom_meeting_id = '84209979734'; 
        $url = 'https://api.zoom.us/v2/metrics/meetings/' . $zoom_meeting_id .'/participants/?type=live&next_page_token=';
        
        $client = Http::withHeaders([
            'Authorization' => 'Bearer '.$user->access_token,
        ])->get($url);

        $result = json_decode($client->body());
        \Log::debug(print_r($result, true));
        
        

        $oauthSuccess=false;
        $meetings = Meeting::all();

        $taskLists = TaskList::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();
        return response()->json(['taskList' => $taskLists,'noZoomCode' => $noZoomCode,
        'zoomOuthLink' => $zoomOuthLink,'oauthSuccess' => $oauthSuccess,'meetings' => $meetings], 201);
    }

    public function create(TaskListRequest $request, TaskList $taskList)
    {
        $taskList->fill($request->all());
        $taskList->user_id = $request->user()->id;
        $taskList->save();

        return response()->json(['taskList' => $taskList], 201);
    }

    public function delete(TaskList $taskList)
    {
        $taskList->cards()->each(function ($cards) {
            $cards->delete();
        });

        $taskList->delete();
        return response()->json(['message' => '削除が完了しました'], 201);
    }

    public function zoomOauth(Request $request) {
        $user = auth()->user();

        if($user->zoom_code==null){
            $code = $request['code'];
            $user->zoom_code = $code;
            // $user->save();            

            $basic = base64_encode(env('ZOOM_CLIENT_ID').':'.env('ZOOM_CLIENT_SECRET'));
            $client = Http::withHeaders([
                'Authorization' => 'Basic '.$basic,
            ])->post('https://zoom.us/oauth/token?grant_type=authorization_code&code=' . $code . '&redirect_uri=http://localhost:8000/api/zoomauth/check');

            $result = json_decode($client->body());
            \Log::debug(print_r($result, true));
            $user->access_token= $result->access_token;
            $user->refresh_token= $result->refresh_token;
            $unixTime = time();
            $user->zoom_expires_in= date("Y-m-d H:i:s",$unixTime+$result->expires_in);
            $user->save();
          
            return app('redirect')->to('http://localhost:8000/');
        }

    }
    
    protected function me(){
        $user = auth()->user();
        
        $client = Http::withHeaders([
            'Authorization' => 'Bearer '.$user->access_token,
        ])->get('https://api.zoom.us/v2/users/me');
        $result = json_decode($client->body());

        return $result;
    }
    
    protected function checkRefresh(){
        $user = auth()->user();
        $token_expires =  new \DateTime($user->zoom_expires_in);
        $now = new \DateTime();
        
        if($now >= $token_expires){
            $basic = base64_encode(env('ZOOM_CLIENT_ID').':'.env('ZOOM_CLIENT_SECRET'));
            $client = Http::withHeaders([
                'Authorization' => 'Basic '.$basic,
            ])->post('https://zoom.us/oauth/token?grant_type=refresh_token&refresh_token=' . $user->refresh_token . '');
            $result = json_decode($client->body());

            $user->access_token= $result->access_token;
            $user->refresh_token= $result->refresh_token;
            $unixTime = time();
            $user->zoom_expires_in= date("Y-m-d H:i:s",$unixTime+$result->expires_in);
            $user->save();
            return $user;
        }
        return $user;
    }
    
    public function createMeeting(Request $request){
        
        $user = $this->checkRefresh();
        $user = auth()->user();       
        $zoom_user = $this->me();
    
        $url = 'https://api.zoom.us/v2/users/'.$zoom_user->id.'/meetings';

        $topic = $request->CompanyName.' '.$request->YourName.'様 ご相談';
        $meeting_password = substr(base_convert(bin2hex(openssl_random_pseudo_bytes(9)),16,36),0,9);

        $client = Http::withHeaders([
            'Authorization' => 'Bearer '.$user->access_token,
            'Content-Type'=>'application/json'
        ])->post($url,[
            'topic'=>$topic,
            'type' => 2,
            'time_zone' => 'Asia/Tokyo',
            'start_time' => '2022-03-30T20:00:00Z',
            'password'=>$meeting_password
        ]);
        
        $result = json_decode($client->body());
        \Log::debug(print_r($result, true));

        $meeting = new Meeting();
        $meeting->name=$request->YourName;
        $meeting->company_name=$request->CompanyName;
        $meeting->email=$request->Email;
        $meeting->content=$request->Content;
    
        $start = new \DateTime($result->start_time);
        $meeting->start_at=$start;
        $meeting->hash=substr(base_convert(bin2hex(openssl_random_pseudo_bytes(64)),16,36),0,64);
        $meeting->is_canceled=false;
    
        $meeting->zoom_meeting_id=$result->id;
        $meeting->zoom_join_url=$result->join_url;
        $meeting->zoom_start_url=$result->start_url;
        $meeting->zoom_password=$result->password;
        $meeting->save();
    
        $format = $start->format('Y年m月d日 H時i分');
        // $meeting->start_at = $format;
        // $mail = new ContactMail($meeting);
        // Mail::to($request->email)->send($mail);

        return app('redirect')->to('http://localhost:8000/');
    }
}
