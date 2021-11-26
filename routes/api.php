<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskListController;
use App\Http\Controllers\TaskCardController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// 会員登録
Route::post('/register', 'Auth\RegisterController@register')->name('register');

// ログイン
Route::post('/login', 'Auth\LoginController@login')->name('login');

// ログアウト
Route::post('/logout', 'Auth\LoginController@logout')->name('logout');

// ログインユーザー取得
Route::get('/user', fn() => Auth::user())->name('user');

// タスクリスト取得
Route::get('/task-list', [TaskListController::class,'get'])->name('taskList');

// タスクリスト登録
Route::post('/task-list', [TaskListController::class,'create'])->name('taskListCreate');

// タスクリスト削除
Route::delete('/task-list/{taskList}', [TaskListController::class,'delete'])->name('taskListDelete');

// タスクカード取得
Route::get('/task-card', [TaskCardController::class,'get'])->name('taskCard');

// タスクカード登録
Route::post('/task-card', [TaskCardController::class,'create'])->name('taskCardCreate');

// タスクカード詳細取得
Route::get('/task-card/{taskCard}', [TaskCardController::class,'show'])->name('taskCardShow');

// タスクカード削除
Route::delete('/task-card/{taskCard}', [TaskCardController::class,'delete'])->name('taskCardDelete');

// タスクカード更新
Route::patch('/task-card/{taskCard}', [TaskCardController::class,'update'])->name('taskCardUpdate');