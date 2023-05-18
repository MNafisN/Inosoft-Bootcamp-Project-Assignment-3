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
     * Show TODO list
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

    /**
     * Add a new TODO
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function addTodo(Request $request) : JsonResponse
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

    /**
     * Update an existing TODO
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateTodo(Request $request) : JsonResponse
    {
        $data = $request->all();

        try {
            $todo = $this->todoService->update($data);
            return response()->json([
                'status' => 201,
                'message' => 'Todo updated successfully',
                'todo' => $todo
            ], 201);
        } catch (Exception $err) {
            return response()->json([
                'status' => 422,
                'error' => $err->getMessage()
            ], 422);
        }
    }

    /**
     * Delete an existing TODO
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteTodo(Request $request) : JsonResponse
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
