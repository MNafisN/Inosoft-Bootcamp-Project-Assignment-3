<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;

use Jenssegers\Mongodb\Eloquent\Model;

class Todo extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'todo';

    /**
     * The attributes that are mass assignable.
     * 
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'assigned',
        'todo_subtasks',
        'created_at',
        'updated_at'
    ];
}
