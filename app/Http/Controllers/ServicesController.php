<?php

namespace App\Http\Controllers;

use App\Models\practiceAreas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Facades\JWTAuth;

interface ServicesInterface {
public function validator(Request $request);
}
class ServicesController extends Controller{
    
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


public function AddService(Request $request){
try{
$validatedRequest = $request->validate([
'overview'=>'required|string',
'title'=>'required|string|unique:practiceAreas,title',
'description'=>'required|string',
'practiceAreaImage'=>"required|file"
]);

$slug = Str::slug($validatedRequest['title']);
$validatedRequest['slug'] = $slug;
if(!$request->hasFile('practiceAreaImage')){
return response()->json([
'message'=>"Practice Image Must be given",
]);
}
$path = $request->file("practiceAreaImage")->store("practices","public");
$validatedRequest["practiceAreaImage"] = $path;
$user_id = $this->validator($request);
$validatedRequest["admins_id"] = $user_id;
PracticeAreas::create($validatedRequest);
return response()->json([
'success'=>true,
'message'=>'Practice Added'
]);
}catch(ValidationException $errValidation){
Log::error($errValidation->getMessage());
return response()->json([
'success'=>false,
'message'=>collect($errValidation->errors())->flatten()->first()
],200);
}catch(\Exception $err){
Log::error($err->getMessage());
return response()->json([
'success'=>false,
'message'=>'Something Went Wrong'
],500);
}
}
}