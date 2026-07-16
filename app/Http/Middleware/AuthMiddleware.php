<?php

namespace App\Http\Middleware;

use App\Models\admins;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $tokenHeader = $request->header("Authorization");
            if (!$tokenHeader || !str_starts_with($tokenHeader, 'Bearer ')) {
               
                return response()->json([
                    "status" => false,
                    "message" => "Permission not granted"
                ], 401);
            }

            // Extract token
            $token = substr($tokenHeader, 7);

            // Validate token and authenticate
            $admin = JWTAuth::setToken($token)->authenticate();
          
           
            if (!$admin || !isset($admin->id)) {

                return response()->json([
                    "status" => false,
                    "message" => "Permission not granted"
                ], 401);
            }
            $admin_found = admins::find($admin->id);
            if (!$admin_found) {
                Log::warning('Admin not found in database', ['admin_id' => $admin->id]);
                return response()->json([
                    "status" => false,
                    "message" => "Permission not granted"
                ], 401);
            }

            // Verify token matches Redis session
            $key = 'user:' . $admin->email;
            $token_string = Redis::get($key);
            if (empty($token_string) || $token_string !== $token) {
             
                return response()->json([
                    "status" => false,
                    "message" => "Permission not granted"
                ], 401);
            }



            return $next($request);

        } catch (\Exception $err) {
            Log::error('Middleware authentication error', [
                'message' => $err->getMessage(),
                'code' => $err->getCode(),
                'file' => $err->getFile(),
                'line' => $err->getLine()
            ]);

            return response()->json([
                "status" => false,
                "message" => "Permission not granted"
            ], 401);
        }
    }
}