<?php

use App\Http\Controllers\FeedController;
use App\Http\Controllers\PornstarController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [FeedController::class, 'index'])->name('feeds.index');

Route::get('/{feed_id}/pornstars', [PornstarController::class, 'index'])->name('pornstars.index');
Route::get('/{feed_id}/pornstars/{pornstar_id}', [PornstarController::class, 'show'])->name('pornstars.show');
