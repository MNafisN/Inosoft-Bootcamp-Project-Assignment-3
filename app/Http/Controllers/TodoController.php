<?php

namespace App\Http\Controllers;

use App\Services\TodoService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    protected $todoService;

    public function __construct(TodoService $todoService)
    {
        $this->middleware('auth:api');
        $this->todoService = $todoService;
    }

    /**
     * Show todo list
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTodoList() : JsonResponse
    {
        try {
            $result = [
                'status' => 200,
                'data' => $this->todoService->getAll()
            ];
        } catch (Exception $err) {
            $result = [
                'status' => 404,
                'error' => $err->getMessage()
            ];
        }

        return response()->json($result, $result['status']);
    }

    public function addTodo(Request $request)
    {
        $data = $request->all();

        try {
            $todo = $this->todoService->store($data);
            return response()->json([
                'status' => 201,
                'message' => 'Todo added successfully',
                'new_todo' => $todo
            ], 201);
        } catch (Exception $err) {
            return response()->json([
                'status' => 422,
                'error' => $err->getMessage()
            ], 422);
        }
    }

    public function deleteTodo(Request $request)
    {
        $data = $request->all();

        try {
            $todo = $this->todoService->delete($data['todo_id']);
            return response()->json([
                'status' => 200,
                'message' => $todo." Deleted successfully"
            ]);
        } catch (Exception $err) {
            return response()->json([
                'status' => 422,
                'error' => $err->getMessage()
            ], 422);
        }
    }
}
