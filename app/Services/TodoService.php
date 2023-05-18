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
    public function getAll() : ?Object
    {
        $todos = $this->todoRepository->getAll();
        if ($todos->isEmpty()) {
            throw new InvalidArgumentException('Data tidak ditemukan');
        }
        return $todos;
    }

    /**
     * Untuk menambahkan todo
     */
    public function store(array $data) : Object
    {
        $validator = Validator::make($data, [
            'title' => 'required|string|max:32|min:3',
            'description' => 'string|max:255|nullable'
        ]);

        if ($validator->fails()) {
            throw new InvalidArgumentException($validator->errors()->first());
        }

        $dataBaru = $this->todoRepository->save($validator->validated());
        return $dataBaru;
    }

    /**
     * Untuk memperbarui data todo
     */
    public function update(array $formData) : Object
    {
        $todo = $this->todoRepository->getById($formData['todo_id']);
        if (!$todo) {
            throw new InvalidArgumentException('Data tidak ditemukan');
        }

        $validator = Validator::make($formData, [
            'todo_id' => 'required|string',
            'title' => 'sometimes|required|string|max:32|min:3',
            'description' => 'sometimes|required|string|max:255|nullable',
            'author' => 'sometimes|required|string',
            'assigned' => 'sometimes|required|string'
        ]);

        if ($validator->fails()) {
            throw new InvalidArgumentException($validator->errors()->first());
        }

        $result = $this->todoRepository->save($validator->validated());
        return $result;
    }

    /**
     * Untuk menghapus data todo
     */
    public function delete(string $id) : string
    {
        $todo = $this->todoRepository->getById($id);
        if (!$todo) {
            throw new InvalidArgumentException('Data tidak ditemukan');
        }
        $todoTitle = $todo['title'];
        $this->todoRepository->delete($id);
        return $todoTitle;
    }
}