<?php

namespace App\Http\Controllers;

use App\Mail\enquirymail;
use App\Models\admins;
use App\Models\enquiries;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Redis;
use Illuminate\Validation\ValidationException;
use RyanChandler\LaravelCloudflareTurnstile\Rules\Turnstile;
use Tymon\JWTAuth\Facades\JWTAuth;

class AdminController extends Controller{

public function registerAdmin(Request $request){
try{
$validatedRequest = $request->validate([
"name" => "required|string|max:255|unique:admins",
"email" => "required|string|email|max:255|unique:admins",
"password" => "required|string",
"phoneNumber"=>"required|string",
"profilePhoto"=>"file|nullable",
"cf-turnstile-response"=>["required",new Turnstile]
]);
$hashedPassword = Hash::make($validatedRequest["password"]);
$admin = new admins();
$profilePhoto= '';
if($request->hasFile("profilePhoto")){
$profilePhoto = $request->file("profilePhoto")->store("profiles","public");
}

$validatedRequest['password'] = $hashedPassword;
$admin->name = $validatedRequest["name"];
$admin->email = $validatedRequest["email"];
$admin->password = $validatedRequest["password"];
$admin->phoneNumber = $validatedRequest["phoneNumber"];
$admin->profilePhoto = $profilePhoto;
$admin->save();
return response()->json([
'message'=>"Admin Added Successfully",
"success"=>true
]);
}catch(ValidationException $err){
Log::error($err->getMessage());
return response()->json([
"success"=>false,
"message"=>collect($err->errors())->flatten()->first()
],200);
}catch(\Exception $errs){
Log::error($errs->getMessage());
return response()->json([
"success"=>false,
"message"=>"Something Went Wrong"
],500);
}}




public function handleLogin(Request $request){
try{
$validatedRequest = $request->validate([
"credential"=>"required",
"password"=>"required|min:6",
 'cf-turnstile-response' => ['required', new Turnstile]
]);
$User = new admins();
$User->name = $validatedRequest["credential"];
$User -> email = $validatedRequest["credential"];
if($User->where("name",$User["name"])->exists() || $User->where("email",$User["email"])->exists()){
$matchingUser =  $User->where("email", $User["email"])->first();
$matchingPassword = Hash::check($validatedRequest["password"],$matchingUser["password"]);
if(!$matchingPassword){
return response()->json([
"message"=> "Credentials mismatch"
]);
}else{
$token = JWTAuth::fromUser($matchingUser);
$key = "user:".$matchingUser->email;
Redis::setex($key,3600,$token);
return response()->json([
"message"=>"Successful Login",
])->header("Authorization",$token)->header("Access-Control-Expose-Headers","Authorization");
}
}else{
return response()->json([
"message"=>"User does not have an account"
]);
}
}catch(\Exception $err){
Log::error($err->getMessage());
return response()->json([
"message"=>"Something has gone wrong"
],200);
}}


//save enquiry and validate the token with turnstile
public function SaveEnquiry(Request $request){
//rate limiting the enquiries comming through
$key = 'enquiry'.$request->ip();
if(RateLimiter::tooManyAttempts($key,5)){
return response()->json([
'success'=>false,
"message"=>"Too Many Attempts.Kindly Try Later"
]);
}
RateLimiter::hit($key,300);
try{
$validatedRequest =$request->validate([
"email"=>"required|string",
"firstName"=>"required|string",
"lastName"=>"required|string",
"phoneNumber"=>"required|string|max:12|min:10",
"message"=>"required|string",
'cf-turnstile-response'=>['required',new Turnstile]
]);

$mailer = new enquirymail($validatedRequest);
Mail::to($validatedRequest["email"])->cc("litigation@floracadocates.co.ke")->send($mailer);
enquiries::create($validatedRequest);
return response()->json([
"success"=>true,
"message"=>"Enquiry Received Successfully"
]);
}catch(ValidationException $err){
Log::error($err->getMessage());
return response()->json([
'success'=>false,
'message'=>collect($err->errors())->flatten()->first()
],200);
}catch(\Exception $err){
Log::error($err->getMessage());
return response()->json([
'success'=>false,
],500);
}}



}
