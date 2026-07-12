<?php

namespace App\Http\Controllers;

use App\Models\admins;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AdminController extends Controller{

public function registerAdmin(Request $request){
try{
$validatedRequest = $request->validate([
"name" => "required|string|max:255|unique:admins",
"email" => "required|string|email|max:255|unique:admins",
"password" => "required|string",
"phoneNumber"=>"required|string"
]);
$hashedPassword = Hash::make($validatedRequest["password"]);
$admin = new admins();
$validatedRequest['password'] = $hashedPassword;
$admin->name = $validatedRequest["name"];
$admin->email = $validatedRequest["email"];
$admin->password = $validatedRequest["password"];
$admin->phoneNumber = $validatedRequest["phoneNumber"];
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
"password"=>"required|min:6"
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




}
