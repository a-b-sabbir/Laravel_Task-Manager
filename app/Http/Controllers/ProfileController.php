<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;

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
}
