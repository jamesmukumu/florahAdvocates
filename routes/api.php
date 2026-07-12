<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ArticlesController;
use App\Http\Controllers\PatnersController;
use App\Http\Controllers\ServicesController;
use Illuminate\Support\Facades\Route;

Route::post('/create/new/admin',[AdminController::class,'registerAdmin']);
Route::post('/login/admin',[AdminController::class,'handleLogin']);



// patners
Route::post('/add/new/partner',[PatnersController::class,'CreatePatners']);


// Practice Areas
Route::post("add/new/practice",[ServicesController::class,"AddService"]);



//articles category
Route::post("/add/new/article/category",[ArticlesController::class,"CreateArticlesCategory"]);
Route::get("/get/article/categories",[ArticlesController::class,"GetArticleCategories"]);
Route::post("/add/new/article",[ArticlesController::class,"CreateArticle"]);