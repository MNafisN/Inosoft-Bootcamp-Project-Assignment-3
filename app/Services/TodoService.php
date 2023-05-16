<?php

namespace App\Services;

use App\Repositories\TodoRepository;
use MongoDB\Exception\InvalidArgumentException;
use Illuminate\Support\Facades\Validator;

class TodoService {
    protected $todoRepository;

    public function __construct(TodoRepository $todoRepository)
    {
        $this->todoRepository = $todoRepository;
    }

    /**
     * Untuk mengambil semua list todo di collection todo
     */
    public function getAll()
    {
        $todo = $this->todoRepository->getAll();
        return $todo;
    }

    /**
     * Untuk menambahkan todo
     */
    public function store($data) : Object
    {
        $validator = Validator::make($data, [
            'title' => 'required|max:32',
            'description' => 'max:256'
        ]);

        if ($validator->fails()) {
            throw new InvalidArgumentException($validator->errors()->first());
        }

        $result = $this->todoRepository->store($data);
        return $result;
    }
}