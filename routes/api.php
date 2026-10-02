<?php

use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Route;

Route::get('/site', [ApiController::class, 'site']);
Route::get('/pages', [ApiController::class, 'pages']);
Route::get('/pages/{slug}', [ApiController::class, 'page']);
Route::get('/blog', [ApiController::class, 'blog']);
Route::get('/blog/{slug}', [ApiController::class, 'blogDetail']);
Route::get('/categories', [ApiController::class, 'categories']);
Route::get('/categories/{slug}/blog', [ApiController::class, 'categoryBlog']);
Route::post('/register', [ApiController::class, 'register']);
Route::post('/login', [ApiController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [ApiController::class, 'user']);

    Route::middleware('role:admin,editor')->group(function () {
        Route::get('/posts', [ApiController::class, 'posts']);
        Route::post('/posts', [ApiController::class, 'createPost']);
        Route::get('/pages-admin', [ApiController::class, 'pagesAdmin']);
        Route::post('/pages-admin', [ApiController::class, 'createPage']);
        Route::put('/pages-admin/{page}', [ApiController::class, 'updatePage']);
        Route::put('/posts/{post}', [ApiController::class, 'updatePost']);
    });

    Route::middleware('role:admin')->group(function () {
        Route::delete('/pages-admin/{page}', [ApiController::class, 'deletePage']);
        Route::delete('/posts/{post}', [ApiController::class, 'deletePost']);
    });
});
