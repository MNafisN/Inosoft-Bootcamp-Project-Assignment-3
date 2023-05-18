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
     * Untuk mengambil data todo berdasarkan id
     */
    public function getById(string $id) : ?Object
    {
        $todo = $this->todo->where('_id', $id)->first();
        return $todo;
    }

    /**
     * Untuk menyimpan data todo baru
     */
    public function store(array $data) : Object
    {
        $dataBaru = new $this->todo;

        $dataBaru->title = $data['title'];
        $dataBaru->description = $data['description'];
        $dataBaru->author = auth()->user()['name'];
        $dataBaru->assigned = null;
        $dataBaru->created_at = time();
        $dataBaru->updated_at = null;

        $dataBaru->save();
        return $dataBaru->fresh();
    }

    /**
     * Untuk menghapus data todo berdasarkan id
     */
    public function delete(string $id) : void
    {
        $this->todo->destroy($id);
    }
}