<?php

use App\Http\Controllers\Article\ArticleCommentController;
use App\Http\Controllers\Article\ArticleController;
use App\Http\Controllers\Article\ArticleUpvoteController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['prefix' => 'auth'], function () {
    Route::controller(AuthController::class)->group(function () {
        Route::get('user', 'user')->name('auth.user');
        Route::post('login', 'login')->name('auth.login');
    });

    Route::post('register', RegisterController::class)->name('auth.register');
});

Route::group(['prefix' => 'articles'], function () {
    Route::group(['prefix' => 'upvotes'], function () {
        Route::controller(ArticleUpvoteController::class)->group(function () {
            Route::get('', 'index')->name('article.upvote.index');
            Route::post('/{article}', 'toggleArticleUpvote')->name('article.upvote.toggleArticleUpvote');
        });
    });

    Route::controller(ArticleCommentController::class)->group(function () {
        Route::get('/comments', 'index')->name('article.comment.index');
        Route::get('/{article}/comments/{comment}', 'show')->name('article.comment.show');
        Route::post('/{article}/comments', 'store')->name('article.comment.store');
        Route::match(['put', 'patch'], '/{article}/comments/{comment}', 'update')->name('article.comment.update');
        Route::delete('/{article}/comments/{comment}', 'destroy')->name('article.comment.destroy');
    });

    Route::controller(ArticleController::class)->group(function () {
        Route::get('', 'index')->name('article.index');
        Route::get('/{article}', 'show')->name('article.show');
        Route::post('', 'store')->name('article.store');
        Route::match(['put', 'patch'], '/{article}', 'update')->name('article.update');
        Route::delete('/{article}', 'destroy')->name('article.destroy');
    });
});
