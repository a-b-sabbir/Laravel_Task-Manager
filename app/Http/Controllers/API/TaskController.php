<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [new Middleware('auth:api')];
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in-pending,in-progress,completed'
        ]);

        // Created a tasks method in User model to make it authenticated
        $tasks = $request->user()->tasks()->create($validatedData);
        $response = [];
        $response['title'] = $tasks->title;
        $response['description'] = $tasks->description;
        $response['status'] = $tasks->status;

        return response()->json([
            'status' => true,
            'message' => 'done',
            'data' => $response
        ]);
    }
}
