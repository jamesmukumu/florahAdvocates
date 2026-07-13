<?php

namespace App\Http\Controllers;

use App\Models\patners;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Facades\JWTAuth;

interface PatnersInterface
{
    public function validator(Request $request);
}
class PatnersController extends Controller implements PatnersInterface
{

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




    public function CreatePatners(Request $request)
    {

        try {
            if ($request->has('socialsAccounts') && !$request->has('socialAccounts')) {
                $request->merge(['socialAccounts' => $request->input('socialsAccounts')]);
            }

            $validatedRequest = $request->validate([
                'name' => 'required|string|unique:patners,name',
                'title' => 'required|string',
                'overview' => 'required|string',
                'educationBackground' => 'required|string',
                'workBackground' => 'required|string',
                'phoneNumber' => 'required|string|unique:patners,phoneNumber',
                'secondaryPhoneNumber' => 'nullable|string',
                'email' => 'required|email|unique:patners,email',
                'patnersImage' => 'required|file|image|max:2048',
                'socialAccounts' => 'nullable'
            ]);

            $admins_id = $this->validator($request);
            $slug = Str::slug($validatedRequest["name"]);
            $validatedRequest["admins_id"] = $admins_id;
            $validatedRequest["slug"] = $slug;

            if (!$request->hasFile('patnersImage')) {
                return response()->json([
                    'message' => 'Patners Image Should be Given',
                ]);
            }

            $path = $request->file("patnersImage")->store('patners', 'public');
            $validatedRequest['patnersImage'] = $path;
            patners::create($validatedRequest);
            return response()->json([
                "success" => true,
                "message" => "Patner Added Successfully"
            ]);
        } catch (ValidationException $errValidation) {
            Log::error($errValidation->getMessage());
            $errors = $errValidation->errors();
            return response()->json([
                "success" => false,
                "message" => collect($errors)->flatten()->first()
            ], 200);
        } catch (\Exception $err) {
            Log::error($err->getMessage());
            return response()->json([
                "success" => false,
                "message" => "Something Went Wrong"
            ], 500);
        }

    }


    public function GetAllPatners(Request $request)
    {
        try {
            $patners = patners::all();
            return response()->json([
                "success" => true,
                "data" => $patners
            ]);
        } catch (\Exception $err) {
            Log::error($err->getMessage());
            return response()->json([
                "success" => false,
                "message" => "Something Went Wrong"
            ], 500);
        }
    }


    public function GetPatner(Request $request)
    {
        try {
            $slug = $request->route("slug");
            $patner = patners::where("slug", $slug)->first();
            if (!$patner) {
                return response()->json([
                    "success" => false,
                    "message" => "Patner not found"
                ], 404);
            }

            return response()->json([
                "success" => true,
                "data" => $patner
            ]);
        } catch (\Exception $err) {
            Log::error($err->getMessage());
            return response()->json([
                "success" => false,
                "message" => "Something Went Wrong"
            ], 500);
        }
    }


    public function UpdatePatner(Request $request)
    {
        try {
            $id = $request->query("id");
            $patner = patners::find($id);
            if (!$patner) {
                return response()->json([
                    "success" => false,
                    "message" => "Patner not found"
                ], 404);
            }

            $admins_id = $this->validator($request);

            if ($request->has('socialsAccounts') && !$request->has('socialAccounts')) {
                $request->merge(['socialAccounts' => $request->input('socialsAccounts')]);
            }

            $validatedRequest = $request->validate([
                'name' => 'nullable|string|unique:patners,name,' . $id,
                'title' => 'nullable|string',
                'overview' => 'nullable|string',
                'educationBackground' => 'nullable|string',
                'workBackground' => 'nullable|string',
                'phoneNumber' => 'nullable|string|unique:patners,phoneNumber,' . $id,
                'secondaryPhoneNumber' => 'nullable|string',
                'email' => 'nullable|email|unique:patners,email,' . $id,
                'patnersImage' => 'nullable|file|image|max:2048',
                'socialAccounts' => 'nullable',
            ]);

            if ($request->hasFile('patnersImage')) {
                $path = $request->file('patnersImage')->store('patners', 'public');
                $validatedRequest['patnersImage'] = $path;
            }

            if (isset($validatedRequest['name'])) {
                $validatedRequest['slug'] = Str::slug($validatedRequest['name']);
            }

            $patner->update($validatedRequest);
            return response()->json([
                "success" => true,
                "message" => "Patner Updated Successfully"
            ]);
        } catch (ValidationException $errValidation) {
            Log::error($errValidation->getMessage());
            return response()->json([
                "success" => false,
                "message" => collect($errValidation->errors())->flatten()->first()
            ], 200);
        } catch (\Exception $err) {
            Log::error($err->getMessage());
            return response()->json([
                "success" => false,
                "message" => "Something Went Wrong"
            ], 500);
        }
    }


    public function DeletePatner(Request $request)
    {
        try {
            $id = $request->query("id");
            $patner = patners::find($id);
            if (!$patner) {
                return response()->json([
                    "success" => false,
                    "message" => "Patner not found"
                ], 404);
            }

            $admins_id = $this->validator($request);
            $patner->delete();
            return response()->json([
                "success" => true,
                "message" => "Patner Deleted Successfully"
            ]);
        } catch (\Exception $err) {
            Log::error($err->getMessage());
            return response()->json([
                "success" => false,
                "message" => "Something Went Wrong"
            ], 500);
        }
    }


    public function PublishPatner(Request $request)
    {
        try {
            $id = $request->query("id");
            $method = $request->route("method");
            $patner = patners::find($id);
            if (!$patner) {
                return response()->json([
                    "success" => false,
                    "message" => "Patner not found"
                ], 404);
            }

            $admins_id = $this->validator($request);
            if ($method == 'publish') {
                $patner->update(["published" => true]);
            } else {
                $patner->update(["published" => false]);
            }

            return response()->json([
                "success" => true,
                "message" => "Patner " . $method . " Successfully"
            ]);
        } catch (ValidationException $errValidation) {
            Log::error($errValidation->getMessage());
            return response()->json([
                "success" => false,
                "message" => collect($errValidation->errors())->flatten()->first()
            ], 200);
        } catch (\Exception $err) {
            Log::error($err->getMessage());
            return response()->json([
                "success" => false,
                "message" => "Something Went Wrong"
            ], 500);
        }
    }






}
