<?php

namespace App\Http\Controllers;

use App\Services\TodoService;
use Exception;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    protected $todoService;

    public function __construct(TodoService $todoService)
    {
        $this->middleware('auth:api');
        $this->todoService = $todoService;
    }

    public function getTodoList()
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
        $data = $request->only([
            'title',
            'description'
        ]);

        try {
            $result = [
                'status' => 201,
                'data' => $this->todoService->store($data)
            ];
        } catch (Exception $err) {
            $result = [
                'status' => 422,
                'error' => $err->getMessage()
            ];
        }

        return response()->json($result, $result['status']);
    }
}
