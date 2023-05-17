<?php

namespace App\Services;

use App\Repositories\AuthRepository;
use Illuminate\Contracts\Auth\Authenticatable;
use MongoDB\Exception\InvalidArgumentException;
use Illuminate\Support\Facades\Validator;

class AuthService 
{
    protected $authRepository;

    public function __construct(AuthRepository $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    /**
     * Untuk mengambil semua list user
     */
    public function getAll() : ?Object
    {
        $todos = $this->authRepository->getAll();
        $todo = $todos->isEmpty() ? null : $todos;
        return $todo;
    }

    /**
     * Untuk menambahkan user
     */
    public function store(array $data) : Object
    {
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:5'
        ]);

        if ($validator->fails()) {
            throw new InvalidArgumentException($validator->errors()->first());
        }

        $result = $this->authRepository->store($data);
        return $result;
    }

    /**
     * Untuk melakukan login user dengan data yang diperlukan
     */
    public function login(array $credentials) : string|bool
    {        
        $validator = Validator::make($credentials, [
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        if ($validator->fails()) {
            throw new InvalidArgumentException($validator->errors()->first());
        }        

        $token = auth()->attempt($credentials, true);

        return $token;
    }

    /**
     * Untuk melihat detail user yang telah dalam keadaan logged in
     */
    public function data() : Authenticatable
    {
        return auth()->user();
    }

    /**
     * Untuk melakukan logout pada user
     */
    public function logout() : string
    {
        $username = auth()->user()['name'];
        auth()->logout();
        return $username;
    }
}