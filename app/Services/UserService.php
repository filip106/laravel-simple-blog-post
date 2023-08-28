<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserService
{

    /**
     * @param array $userData
     * @return User
     */
    public function store(array $userData): User
    {
        $userData['password'] = Hash::make($userData['password']);
        $userData['remember_token'] = Str::random(10);

        return User::create($userData);
    }

    /**
     * @param string $email
     * @return User|null
     */
    public function get(string $email): ?User
    {
        return User::where('email', $email)->first();
    }
}
