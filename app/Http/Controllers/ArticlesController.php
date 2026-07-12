<?php

namespace App\Http\Controllers;

use App\Models\articles;
use App\Models\articlesCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Facades\JWTAuth;

interface ArticlesInterface {
public function validator(Request $request);
}
class ArticlesController extends Controller implements ArticlesInterface{
 public function validator(Request $request)
    {
        try {
            $tokenHeader = $request->header("Authorization");
            $actualToken = substr($tokenHeader, 7);
            if (!$tokenHeader || !$actualToken) {
                throw new \Exception("Unauthorized", 401);
            }
            $payload = JWTAuth::setToken($actualToken)->getPayload();
            return $payload["sub"];
        } catch (\Exception $err) {
            throw new \Exception($err->getMessage(), 500);
        }
    }



    public function CreateArticlesCategory(Request $request){

     try{
            $validatedRequest = $request->validate([
    "title"=>"required|string|unique:articlesCategories,title",
    "description"=>"required"
    ]);      
         $admin_id = $this->validator($request);
      $validatedRequest["admins_id"] = $admin_id;
     articlesCategory::create($validatedRequest);
return response()->json([
"success"=>true,
"message"=>"Article Category Saved"
]);
     }catch(ValidationException $err){
     Log::error($err->getMessage());
     return response()->json([
      "success"=>false,
      "message"=>collect($err->errors())->flatten()->first()
     ],200);
     }catch(\Exception $err){
     Log::error($err->getMessage());
     return response()->json([
      "success"=>false,
      "message"=>"Something Went Wrong"
     ],500);
     }
    }


    //insert articles
    public function CreateArticle(Request $request){

     try{
             $validatedRequest = $request->validate([
    "title"=>"required|string|unique:articles,title",
    "articleImage"=>"required|file|image|max:2048",
    "articleBody"=>"required|string",
    "articleOverview"=>"required|string",
    "articlesCategories_id"=>"required|exists:articlesCategories,id",
     ]);
    
     $slug = Str::slug($validatedRequest["title"]);
     $validatedRequest["slug"] = $slug;
     $admins_id = $this->validator($request);
     $image_path = null;
      if(!$request->hasFile("articleImage")){
       return response()->json([
       "message"=>"Article Image Should be provided"
       ]);
      }
    $image_path = $request->file("articleImage")->store("articles","public");
     $validatedRequest["articleImage"] = $image_path;
     $validatedRequest["admins_id"] = $admins_id;
     articles::create($validatedRequest);
    return response()->json([
    "success"=>true,
    "message"=>"Article Saved Successfully"
    ]);
     }catch(ValidationException $errValidation){
    Log::error($errValidation->getMessage());
    return response()->json([
    "success"=>false,
    "message"=>collect($errValidation->errors())->flatten()->first()
    ],200);
     }catch(\Exception $err){
  Log::error($err->getMessage());
    return response()->json([
    "success"=>false,
    "message"=>"Something Went Wrong"
    ],500);}}




    public function GetArticleCategories(Request $request){
     $article_categories = articlesCategory::all()->map(function($category){
       return [
        "label"=>$category->title,
        "value"=>$category->id
       ];
     });
     return response()->json([
     "success"=>true,
     "data"=>$article_categories
     ]);
    }
}
