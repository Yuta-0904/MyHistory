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
    // use DatabaseMigrations;
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

    //タスクリスト・カード作成後、作成したタスクが作成ユーザのみに表示されているか確認
    public function testTaskconnectionUser(){
        $users = $this->makeUser();
        // $userでログイン
        $this->actingAs($users['user1']);

        //認証しているユーザーがタスクリストを送信し保存
        $dataList = ['name' => 'testPost'];
        $this->post(route('taskListCreate'),$dataList);
        $taskList = TaskList::findOrFail(1);

        //認証しているユーザーがタスクカードを送信し保存
        $dataCard = ['list_name' => 'testCard','content' => '認証ユーザのタスクカード','status' => 1,'limit' => now()];
        $this->post(route('taskCardCreate'),$dataCard);

        

        //認証されてないユーザがタスクリスト・カードを登録
        $notAuthList = factory(TaskList::class)->create([
            'user_id' => $users['user2']->id,
            'name' => 'dummyData'
        ]);
        $notAuthCard = factory(TaskCard::class)->create([
            'user_id' => $users['user2']->id,
            'list_id' => $notAuthList->id,
            'name' => '未認証のタスクカード'
        ]);


        // $user1が作成したタスクリスト・カードが保存されているか確認
        $this->assertDatabaseHas('task_lists', [
            'user_id' => $users['user1']->id
        ]);
        // $this->assertDatabaseHas('task_cards', [
        //     'user_id' => $users['user1']->id
        // ]);
        
        $response = $this->get(route('taskList'));
        //認証ユーザが投稿したタスクリストが取得しているかつ、未認証ユーザが投稿したデータが取得できていないことの確認
        $response->assertSee('testPost');
        $response->assertDontSee($notAuthList->name);


        //$response = $this->get(route('taskCard'));
        //認証ユーザが投稿したタスクカードが取得しているかつ、未認証ユーザが投稿したデータが取得できていないことの確認
        //$response->assertSee('認証ユーザのタスクカード');
        //$response->assertDontSee($notAuthCard->name);


        //デバックも可能
        //\Log::debug(print_r($response, true));
        //log::info($notAuthList->name);
    } 

    private function makeUser(){
        factory(User::class, 2)->create();
        // ユーザーのうち片方を認証させる
        $user = User::findOrFail(1);
        $user2 = User::findOrFail(2);
        
        return ['user1' => $user,'user2' => $user2];
    }

    

    
}
