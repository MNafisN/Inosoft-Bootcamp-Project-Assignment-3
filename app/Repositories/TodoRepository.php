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
     * Untuk memperbarui data todo maupun menyimpan data todo baru
     */
    public function save(array $data) : Object
    {         
        if (array_key_exists('todo_id', $data)) {
            $todo = $this->getById($data['todo_id']);

            if (array_key_exists('title', $data)) { $todo->title = $data['title']; }
            if (array_key_exists('description', $data)) { $todo->description = $data['description']; }            
            if (array_key_exists('author', $data)) { $todo->author = $data['author']; }
            if (array_key_exists('assigned', $data)) { $todo->assigned = $data['assigned']; }
            $todo->updated_at = time();    
        } else {
            $todo = new $this->todo;
            
            $todo->title = $data['title'];
            $todo->description = $data['description'];
            $todo->author = auth()->user()['name'];
            $todo->assigned = null;
            $todo->created_at = time();
            $todo->updated_at = null;
        }
        $todo->save();
        return $todo->fresh();
    }

    /**
     * Untuk menghapus data todo berdasarkan id
     */
    public function delete(string $id) : void
    {
        $this->todo->destroy($id);
    }
}