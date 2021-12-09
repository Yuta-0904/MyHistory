<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskListController;
use App\Http\Controllers\TaskCardController;
use App\Http\Controllers\LearnCardController;
use App\Http\Controllers\LearnListController;

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

// タスクカード(リスト所属)取得
Route::get('/task-card', [TaskCardController::class,'get'])->name('taskCard');

// タスクカード全件取得
Route::get('/task-card/all', [TaskCardController::class,'getAll'])->name('taskCardAll');

// タスクカード登録
Route::post('/task-card', [TaskCardController::class,'create'])->name('taskCardCreate');

// タスクカード詳細取得
Route::get('/task-card/{taskCard}', [TaskCardController::class,'show'])->name('taskCardShow');

// タスクカード削除
Route::delete('/task-card/{taskCard}', [TaskCardController::class,'delete'])->name('taskCardDelete');

// タスクカード更新
Route::patch('/task-card/{taskCard}', [TaskCardController::class,'update'])->name('taskCardUpdate');

// 学習リスト取得
Route::get('/learn-list', [LearnListController::class,'get'])->name('learnList');

// タスクカード(リスト所属)取得
Route::get('/learn-card', [LearnCardController::class,'get'])->name('learnCard');

// タスクカード全件取得
Route::get('/learn-card/all', [LearnCardController::class,'getAll'])->name('learnCardAll');


//学習リスト登録
Route::post('/learn-list', [LearnListController::class,'create'])->name('learnListCreate');

// 学習リスト削除
Route::delete('/learn-list/{learnList}', [LearnListController::class,'delete'])->name('learnListDelete');

// 学習カード登録
Route::post('/learn-card', [LearnCardController::class,'create'])->name('learnCardCreate');

// 学習カード削除
Route::delete('/learn-card/{learnCard}', [LearnCardController::class,'delete'])->name('learnCardDelete');

// 学習カード詳細取得
Route::get('/learn-card/{learnCard}', [LearnCardController::class,'show'])->name('learnCardShow');

// 学習カード更新
Route::patch('/learn-card/{learnCard}', [LearnCardController::class,'update'])->name('learnCardUpdate');