<?php

namespace App\Http\Controllers;

use App\Models\practiceAreas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Facades\JWTAuth;

interface ServicesInterface
{
    public function validator(Request $request);
}

class ServicesController extends Controller implements ServicesInterface
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

    // Create Service
    public function AddService(Request $request)
    {
        try {
            $validatedRequest = $request->validate([
                'overview' => 'required|string',
                'title' => 'required|string|unique:practiceAreas,title',
                'description' => 'required|string',
                'practiceAreaImage' => 'required|file|image|max:2048'
            ]);

            $slug = Str::slug($validatedRequest['title']);
            $validatedRequest['slug'] = $slug;

            if (!$request->hasFile('practiceAreaImage')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Practice Image Must be given',
                ], 200);
            }

            $path = $request->file("practiceAreaImage")->store("practices", "r2");
            $validatedRequest["practiceAreaImage"] = $path;
            $user_id = $this->validator($request);
            $validatedRequest["admins_id"] = $user_id;

            practiceAreas::create($validatedRequest);
            return response()->json([
                'success' => true,
                'message' => 'Practice Added Successfully'
            ]);
        } catch (ValidationException $errValidation) {
            Log::error($errValidation->getMessage());
            return response()->json([
                'success' => false,
                'message' => collect($errValidation->errors())->flatten()->first()
            ], 200);
        } catch (\Exception $err) {
            Log::error($err->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something Went Wrong'
            ], 500);
        }
    }

    // Get All Services
    public function GetAllServices(Request $request)
    {
        try {
            $services = practiceAreas::all();
            return response()->json([
                'success' => true,
                'data' => $services
            ]);
        } catch (\Exception $err) {
            Log::error($err->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something Went Wrong'
            ], 500);
        }
    }

    // Get Single Service by Slug
    public function GetService(Request $request)
    {
        try {
            $slug = $request->route("slug");
            $service = practiceAreas::where("slug", $slug)->first();
            if (!$service) {
                return response()->json([
                    'success' => false,
                    'message' => 'Service not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $service
            ]);
        } catch (\Exception $err) {
            Log::error($err->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something Went Wrong'
            ], 500);
        }
    }

    // Update Service
    public function UpdateService(Request $request)
    {
        try {
            $id = $request->query("id");
            $service = practiceAreas::find($id);
            if (!$service) {
                return response()->json([
                    'success' => false,
                    'message' => 'Service not found'
                ], 404);
            }

            $admins_id = $this->validator($request);

            $validatedRequest = $request->validate([
                'overview' => 'nullable|string',
                'title' => 'nullable|string|unique:practiceAreas,title,' . $id,
                'description' => 'nullable|string',
                'practiceAreaImage' => 'nullable|file|image|max:2048'
            ]);

            if ($request->hasFile('practiceAreaImage')) {
                $path = $request->file('practiceAreaImage')->store('practices', 'r2');
                $validatedRequest['practiceAreaImage'] = $path;
            }

            if (isset($validatedRequest['title'])) {
                $validatedRequest['slug'] = Str::slug($validatedRequest['title']);
            }

            $service->update($validatedRequest);
            return response()->json([
                'success' => true,
                'message' => 'Service Updated Successfully'
            ]);
        } catch (ValidationException $errValidation) {
            Log::error($errValidation->getMessage());
            return response()->json([
                'success' => false,
                'message' => collect($errValidation->errors())->flatten()->first()
            ], 200);
        } catch (\Exception $err) {
            Log::error($err->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something Went Wrong'
            ], 500);
        }
    }

    // Delete Service
    public function DeleteService(Request $request)
    {
        try {
            $id = $request->query("id");
            $service = practiceAreas::find($id);
            if (!$service) {
                return response()->json([
                    'success' => false,
                    'message' => 'Service not found'
                ], 404);
            }

            $admins_id = $this->validator($request);
            $service->delete();
            return response()->json([
                'success' => true,
                'message' => 'Service Deleted Successfully'
            ]);
        } catch (\Exception $err) {
            Log::error($err->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something Went Wrong'
            ], 500);
        }
    }

    // Publish/Unpublish Service
    public function PublishService(Request $request)
    {
        try {
            $id = $request->query("id");
            $method = $request->route("method");
            $service = practiceAreas::find($id);
            if (!$service) {
                return response()->json([
                    'success' => false,
                    'message' => 'Service not found'
                ], 404);
            }

            $admins_id = $this->validator($request);
            if ($method == 'publish') {
                $service->update(["published" => true]);
            } else {
                $service->update(["published" => false]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Service ' . $method . ' Successfully'
            ]);
        } catch (ValidationException $errValidation) {
            Log::error($errValidation->getMessage());
            return response()->json([
                'success' => false,
                'message' => collect($errValidation->errors())->flatten()->first()
            ], 200);
        } catch (\Exception $err) {
            Log::error($err->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something Went Wrong'
            ], 500);
        }
    }
}
