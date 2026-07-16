<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ArticlesController;
use App\Http\Controllers\PatnersController;
use App\Http\Controllers\ServicesController;
use App\Http\Middleware\AuthMiddleware;
use Illuminate\Support\Facades\Route;


Route::post('/login/admin', [AdminController::class, 'handleLogin']);


//protected routes 
Route::middleware(AuthMiddleware::class)->group(function(){

// patners
Route::post('/add/new/partner', [PatnersController::class, 'CreatePatners']);
Route::get('/get/patners', [PatnersController::class, 'GetAllPatners']);
Route::get('/patner/{slug}', [PatnersController::class, 'GetPatner']);
Route::post('/update/patner', [PatnersController::class, 'UpdatePatner']);
Route::delete('/delete/patner', [PatnersController::class, 'DeletePatner']);
Route::patch('/patch/patner/{method}', [PatnersController::class, 'PublishPatner']);


// Practice Areas / Services
Route::post("/add/new/practice", [ServicesController::class, "AddService"]);
Route::get("/get/practices", [ServicesController::class, "GetAllServices"]);
Route::get("/practice/{slug}", [ServicesController::class, "GetService"]);
Route::post("/update/practice", [ServicesController::class, "UpdateService"]);
Route::delete("/delete/practice", [ServicesController::class, "DeleteService"]);
Route::patch("/patch/practice/{method}", [ServicesController::class, "PublishService"]);


//articles category
Route::get("/article/{slug}", [ArticlesController::class, "GetArticle"]);
Route::post("/add/new/article/category", [ArticlesController::class, "CreateArticlesCategory"]);
Route::get("/get/article/categories", [ArticlesController::class, "GetArticleCategories"]);
Route::post("/add/new/article", [ArticlesController::class, "CreateArticle"]);
Route::post("/update/article", [ArticlesController::class, "UpdateArticle"]);
Route::get("/get/articles", [ArticlesController::class, "GetAllArticles"]);
Route::patch("/patch/article/{method}", [ArticlesController::class, "PublishArticle"]);


});

Route::post('/create/new/admin', [AdminController::class, 'registerAdmin']);
Route::post("/add/new/enquiry",[AdminController::class,"SaveEnquiry"]);