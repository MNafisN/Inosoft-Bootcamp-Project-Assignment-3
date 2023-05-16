<?php

namespace App\Repositories;

use App\Models\Todo;

class TodoRepository
{
    protected $todo;

    public function __construct(Todo $todo)
    {
        $this->todo = $todo;
    }

    /**
     * Untuk mengambil semua list todo
     */
    public function getAll() : Object
    {
        $todo = $this->todo->get();
        return $todo;
    }

    /**
     * Untuk menyimpan data todo baru
     */
    public function store($data) : Object
    {
        $dataBaru = new $this->todo;

        $dataBaru->title = $data['title'];
        $dataBaru->description = $data['description'];
        $dataBaru->assigned = null;
        $dataBaru->todo_subtasks = [];
        $dataBaru->created_at = time();
        $dataBaru->updated_at = null;

        $dataBaru->save();
        return $dataBaru->fresh();
    }
}