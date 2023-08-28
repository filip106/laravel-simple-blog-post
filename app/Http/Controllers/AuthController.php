<?php

namespace App\Http\Controllers;

/**
 *
 */
class AuthController extends Controller
{

    /**
     * @return mixed
     */
    public function login()
    {
        return view('auth.login');
    }

    public function register()
    {
        return view('auth.register');
    }
}
