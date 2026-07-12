<?php

namespace App\Http\Controllers;

use App\Models\patners;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Facades\JWTAuth;

interface PatnersInterface {
public function validator(Request $request);
}
class PatnersController extends Controller implements PatnersInterface{
    
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




    public function CreatePatners(Request $request){
 
    try{
           $validatedRequest = $request->validate([
    'name'=>'required|string|unique:patners,name',
    'title'=>'required|string',
    'overview'=>'required|string',
    'educationBackground'=>'required|string',
    'workBackground'=>"required|string",
    'phoneNumber'=>'required|string',
    'secondaryPhoneNumber'=>'nullable|string',
    'email'=>'required|unique:patners,email',
    'patnersImage'=>'required|file',
    'socialsAccounts'=>'nullable'
    ]);

    $admins_id = $this->validator($request);
    $slug = Str::slug($validatedRequest["name"]);
    $validatedRequest["admins_id"] = $admins_id;
    $validatedRequest["slug"] = $slug;

    if(!$request->hasFile('patnersImage')){
    return response()->json([
    'message'=>'Patners Image Should be Given',
    ]);
    }
    $path = null;
    $path = $request->file("patnersImage")->store('patners','public');
    $validatedRequest['patnersImage'] = $path;
     patners::create($validatedRequest);
     return response()->json([
    "success"=>true,
    "message"=>"Patner Added Successfully"
     ]);
    }catch(ValidationException $errValidation){
     Log::error($errValidation->getMessage());
     $errors = $errValidation->errors();
     return response()->json([
    "success"=>false,
    "message"=>collect($errors)->flatten()->first()
     ],200);
    }catch(\Exception $err){
    Log::error($err->getMessage());
       return response()->json([
    "success"=>false,
    "message"=>"Something Went Wrong"
     ],500);
    }

    }

}
