<?php

namespace App\Http\Controllers;


use App\Models\Post;

class PostController extends Controller
{
    public function index()
    {
        return view('post.index', ['posts' => Post::query()->paginate()]);
    }

    public function create()
    {
        return view('post.create');
    }

    public function edit()
    {
        return view('post.edit');
    }
}
