<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AuthenticationTest extends TestCase
{
    // use DatabaseMigrations;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    //ログイン画面を表示
    public function testLoginView()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
         // 認証されていないことを確認
         $this->assertGuest();
    }

    //ログイン処理実行
    public function testLogin(){
        // 認証されていないことを確認
        $this->assertGuest();
        // ダミーログイン
        $response = $this->dummyLogin();
        // 認証を確認
        $this->assertAuthenticated();
        
    }

    public function testLogout(){
        //認証確認
        $this->dummyLogin();
        $this->assertAuthenticated();
        //ログアウト処理
        $response = $this->post('logout');
        $response->assertStatus(200);
        // 認証されていないことを確認
        $this->assertGuest();
        
    }
    

    private function dummyLogin() {
       $user = factory(User::class)->create();
       return $this->actingAs($user)
       ->withSession(['foo' => 'bar'])
       ->get('/');

    }
}
