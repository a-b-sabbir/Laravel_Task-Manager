<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'phone' => 'required|string',
            'address' => 'required|string'
        ]);

        $updatedProfile = $request->user()->profile()->create($validatedData);

        return response()->json([
            'message' => 'Profile updated successfully',
            'data' => $updatedProfile
        ]);
    }

    public function index(Request $request)
    {
        $query = $request->user()->profile();

        $allowedFilters = [
            'phone',
            'address'
        ];

        foreach ($request->all() as $key => $value) {
            if (!in_array($key, $allowedFilters)) {
                return response()->json([
                    'message' => 'Not valid filter',
                    'data' => null
                ]);
            }
        }

        if ($request->has('phone')) {
            $query->where('phone', $request->phone);
        }
        if ($request->has('address')) {
            $query->where('address', $request->address);
        }

        $profile = $query->paginate(5);
        $user = $request->user();

        if ($profile->isEmpty()) {
            return response()->json([
                'message' => 'No Information',
                'data' => null
            ]);
        }
        return response()->json([
            'message' => "Data fetched",
            'data' => [
                'profile' => $profile,
                'user' => [
                    'name' => $user->name,
                    'email' => $user->email
                ]
            ]
        ]);
    }

    public function update(Request $request, $id)
    {
        $validatedData = Validator::make($request->all(), [
            'phone' => 'sometimes',
            'address' => 'sometimes'
        ]);

        if ($validatedData->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'data' => $validatedData->errors()->all()
            ]);
        }

        $updatedProfile = $request->user()->profile()->find($id);

        if (!$updatedProfile) {
            return response()->json([
                'status' => false,
                'message' => 'The profile not found',

            ]);
        }

        $user = $request->user();
        $updatedProfile->phone = $request->phone;
        $updatedProfile->address = $request->address;

        return response()->json([
            'status' => true,
            'message' => "Profile Updated",
            'data' => [
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $updatedProfile->phone,
                'address' => $updatedProfile->address
            ]
        ]);
    }

    public function delete(Request $request, $id)
    {
        $profile = $request->user()->profile()->find($id);

        if (!$profile) {
            return response()->json([
                'status' => false,
                'message' => 'Profile not found'
            ]);
        }

        $profile->delete();

        return response()->json([
            'status' => true,
            'message' => 'Data Deleted'
        ]);
    }
}
