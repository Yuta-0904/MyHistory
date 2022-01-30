<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;
use App\TaskCard;
use App\TaskList;
use Illuminate\Support\Facades\Log;

class TaskTest extends TestCase
{
    use DatabaseMigrations;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    // ログイン後、タスクリスト取得可能かテスト
    public function testTaskView(){
        $user = factory(User::class)->create();
        $this->actingAs($user);
        $response = $this->get('/');
        $response->assertStatus(200);
        
        //タスクリスト取得
        $response = $this->get(route('taskList'));
        $response->assertStatus(201);
    }

     // 末ログイン時、タスクリスト取得可能かテスト
    public function testCuestTaskView(){
        $response = $this->get(route('taskList'));
        $response->assertStatus(302);
    } 

    //タスクリスト作成後、作成したタスクが表示されているかテスト
    public function testTaskCreate(){
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $firstPost = factory(TaskList::class)->create([
            'user_id' => $user->id,
            'name' => 'firstPost'
        ]);

        $secondPost = factory(TaskList::class)->create([
            'user_id' => $user->id,
            'name' => 'secondPost'
        ]);

        $thirdPost = factory(TaskList::class)->create([
            'user_id' => $user->id,
            'name' => 'thirdPost'
        ]);

        $response = $this->get(route('taskList'));
        $response->assertStatus(201);
        $response->assertSee($firstPost->name, $thirdPost->name, $secondPost->name);

    }

    //タスクリスト作成後、作成したタスクが作成ユーザのみに表示されているか確認
    public function testTaskconnectionUser(){
        factory(User::class, 2)->create();
        // ユーザーのうち片方を認証させる
        $user = User::findOrFail(1);
        $user2 = User::findOrFail(2);
        // $userでログイン
        $this->actingAs($user);

        //認証しているユーザーが投稿を送信し保存
        $data = ['name' => 'testPost'];
        $userData = $this->post(route('taskListCreate'),$data);

        //認証されてないユーザがデータを登録
        $firstPost = factory(TaskList::class)->create([
            'user_id' => $user2->id,
            'name' => 'dummyData'
        ]);
        
　　　　　//$userが作成した$userDataがDBに保存されているか確認
        $this->assertDatabaseHas('task_lists', [
            'user_id' => $user->id
        ]);


        $response = $this->get(route('taskList'));

        //ログ取得でデバックも可能
        //\Log::debug(print_r($response, true));
        //log::info($firstPost->name);
        
        //$userが投稿したデータがレスポンスで取得しているかつ、$use2が投稿したデータが取得できていないことの確認
        $response->assertSee('testPost');
        $response->assertSee($firstPost->name);

    } 

    

    
}
