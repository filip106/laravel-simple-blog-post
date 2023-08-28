<?php

namespace App\Services;

use App\Models\Post;

class PostService
{

    /**
     * @param array $postData
     * @return Post
     */
    public function store(array $postData): Post
    {
        return Post::create($postData);
    }
}
