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
            'status' => 'required|in:Pending,In-progress,Completed'
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

    public function index(Request $request)
    {
        $tasks = Task::paginate(5);

        return response()->json([
            'status' => true,
            'message' => 'Data shown',
            'data' => $tasks
        ]);
    }

    public function show(Request $request, $id)
    {
        $task = Task::find($id);

        return response()->json([
            'status' => true,
            'message' => 'Requested info',
            'data' => $task
        ]);
    }

    public function update(Request $request, $id)
    {
        $validatedData = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'status' => 'required|in:Pending, In-Progress, Completed'
        ]);
        if ($validatedData->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Put the valid data',
                'data' => $validatedData->errors()->all()
            ]);
        }

        $task = Task::find($id);
        $task->title = $request->title;
        $task->description = $request->description;
        $task->status = $request->status;
        return response()->json([
            'status' => true,
            'message' => 'Task updated',
            'data' => $task
        ]);
    }

    public function destroy($id)
    {
        $task = Task::find($id);
        if (!$task) {
            return response()->json([
                'status' => false,
                'message' => 'Task not found'
            ]);
        }
        $task->delete();
        return response()->json([
            'status' => true,
            'message' => 'Task Deleted'
        ]);
    }
}
