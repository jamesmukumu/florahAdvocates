<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ArticlesController;
use App\Http\Controllers\PatnersController;
use App\Http\Controllers\ServicesController;
use Illuminate\Support\Facades\Route;

Route::post('/create/new/admin', [AdminController::class, 'registerAdmin']);
Route::post('/login/admin', [AdminController::class, 'handleLogin']);



// patners
Route::post('/add/new/partner', [PatnersController::class, 'CreatePatners']);
Route::get('/get/patners', [PatnersController::class, 'GetAllPatners']);
Route::get('/patner/{slug}', [PatnersController::class, 'GetPatner']);
Route::post('/update/patner', [PatnersController::class, 'UpdatePatner']);
Route::delete('/delete/patner', [PatnersController::class, 'DeletePatner']);
Route::patch('/patch/patner/{method}', [PatnersController::class, 'PublishPatner']);


// Practice Areas
Route::post("add/new/practice", [ServicesController::class, "AddService"]);



//articles category
Route::get("/article/{slug}", [ArticlesController::class, "GetArticle"]);
Route::post("/add/new/article/category", [ArticlesController::class, "CreateArticlesCategory"]);
Route::get("/get/article/categories", [ArticlesController::class, "GetArticleCategories"]);
Route::post("/add/new/article", [ArticlesController::class, "CreateArticle"]);
Route::post("/update/article", [ArticlesController::class, "UpdateArticle"]);
Route::get("/get/articles", [ArticlesController::class, "GetAllArticles"]);
Route::patch("/patch/article/{method}", [ArticlesController::class, "PublishArticle"]);

