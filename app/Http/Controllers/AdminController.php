<?php

namespace App\Http\Controllers;

use App\Mail\enquirymail;
use App\Mail\resetpassword;
use App\Models\admins;
use App\Models\enquiries;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use RyanChandler\LaravelCloudflareTurnstile\Rules\Turnstile;
use Symfony\Component\Uid\Uuid;
use Tymon\JWTAuth\Facades\JWTAuth;


interface AdminsInterface
{
    public function validator(Request $request);
}
class AdminController extends Controller implements AdminsInterface {
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
"message"=>"Something has gone wrong",
"error"=>$err->getMessage()
],500);
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






//reset password 
public function requestResetPassword(Request $request){
try{
$validatedRequest = $request->validate([
"email"=>"required|exists:admins,email"
]);
$admin = admins::where("email",$validatedRequest["email"])->first();
$uuid_string =(string) Str::uuid();
Redis::hMset("resets:".$uuid_string,[
"email"=>$admin->email,
"id"=>$admin->id
]);
Redis::expire("resets:".$uuid_string,10*60);

$mailer = new resetpassword("https://portal.floracadvocates.co.ke/complete/reset-password/".$uuid_string);
Mail::to($admin->email)->send($mailer);
return response()->json([
"success"=>true,
"message"=>'Reset Link has been sent to your email'
]);
}catch(ValidationException $err){
Log::error($err->getMessage());
return response()->json([
"success"=>false,
"message"=>collect($err->errors())->flatten()->first()
]);
}catch(\Exception $err){
Log::error($err->getMessage());
return response()->json([
"success"=>false,
"message"=>"something went wrong"
],500);
}}



public function CompleteReset(Request $request){
try{
$validatedRequest = $request->validate([
"password"=>"string|min:6"
]);

$uuid = $request->route('id');
$key = 'resets:'.$uuid;
$infos = Redis::hGetAll($key);
$admin = admins::find($infos["id"]);
$match = Hash::check($validatedRequest["password"],$admin->password);
if($match){
return response()->json([
'success'=>false,
'message'=>'New password cannot be the same as the old one'
]);
}
$new_password = Hash::make($validatedRequest["password"]);
$admin->password = $new_password;
$admin->save();
return response()->json([
'success'=>true,
'message'=>'Password Updated'
]);
}catch(ValidationException $err){
Log::error($err->getMessage());
return response()->json([
"success"=>false,
"message"=>collect($err->errors())->flatten()->first()
]);
}catch(\Exception $err){
Log::error($err->getMessage());
return response()->json([
'success'=>false,
'message'=>'Something went Wrong'
],500);
}}


public function Getuserprofile(Request $request){
try{
$user_id = $this->validator($request);
$admins = admins::select(["name","email","profilePhoto"])->find($user_id);
return response()->json([
'data'=>$admins,
"success"=>true
]);
}catch(\Exception $err){
Log::error($err->getMessage());
return response()->json([
'message'=>'Something went wrong'
],500);
}}



public function GetEnquiries(Request $request){
try{
$enquiries = enquiries::all();
return response()->json([
'success'=>true,
'data'=>$enquiries
]);
}catch(\Exception $err){
Log::error($err->getMessage());
return response()->json([
'success'=>false,
'message'=>'Something Went Wrong'
]);
}
}

}
