<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PostTest extends TestCase
{

    /** @var User */
    protected User $loggedInUser;

    /** @var Post */
    protected Post $loggedInUsersPost;

    /** @var Post */
    protected Post $postFromOtherUser;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->loggedInUser = User::factory()->create([
            'name' => 'user10',
            'email' => 'user10@email',
            'password' => Hash::make('123456'),
        ]);
        $accessToken = $this->loggedInUser->createToken('Laravel Password Grant Client')->accessToken;
        $this->withToken($accessToken);

        $post = Post::query()->create([
            'title' => 'default_post1',
            'content' => 'post content',
            'slug' => 'slug_default_post1',
            'author_id' => $this->loggedInUser->id,
        ]);
        $postFromOtherUser = Post::query()->where('author_id', '!=', $this->loggedInUser->id)->first();
        if ($post instanceof Post && $postFromOtherUser instanceof Post) {
            $this->loggedInUsersPost = $post;
            $this->postFromOtherUser = $postFromOtherUser;
        }
    }

    /**
     * Test if all post routes are protected with auth middleware
     * @return void
     */
    public function testAllPostsAuthError(): void
    {
        $this->flushHeaders();
        $response = $this->json('GET', '/api/posts');
        $response->assertStatus(401);
        $response = $this->json('GET', '/api/posts/1');
        $response->assertStatus(401);
        $response = $this->json('POST', '/api/posts');
        $response->assertStatus(401);
        $response = $this->json('PUT', '/api/posts/1');
        $response->assertStatus(401);
        $response = $this->json('DELETE', '/api/posts/1');
        $response->assertStatus(401);
    }

    /**
     * Test if user can see route for all posts
     * @return void
     */
    public function testListPosts(): void
    {
        $response = $this->json('GET', '/api/posts');
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'content',
                        'slug',
                        'author_id',
                        'created_at',
                        'updated_at',
                    ]
                ],
                'current_page',
                'per_page',
                'total',
            ]);
    }

    /**
     * Test if logged-in user can see posts created by other users
     * @return void
     */
    public function testShowPostFromOtherUser(): void
    {
        $response = $this->json('GET', '/api/posts/' . $this->postFromOtherUser->id);
        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'title',
                'content',
                'slug',
                'author_id',
                'created_at',
                'updated_at',
            ]);
    }

    /**
     * Test if saving post with slug param
     * @return void
     */
    public function testSavePostWithSlug(): void
    {
        $params = [
            'title' => 'post1',
            'content' => 'post content',
            'slug' => 'slug_post1',
        ];
        $response = $this->json('POST', '/api/posts', $params);
        $response->assertStatus(201)
            ->assertJsonStructure([
                'id',
                'title',
                'content',
                'slug',
                'author_id',
                'created_at',
                'updated_at',
            ]);
    }

    /**
     * Test if saving post without slug param
     * @return void
     */
    public function testSavePostWithoutSlug(): void
    {
        $params = [
            'title' => 'post1',
            'content' => 'post content',
        ];
        $response = $this->json('POST', '/api/posts', $params);
        $response->assertStatus(201)
            ->assertJsonStructure([
                'id',
                'title',
                'content',
                'slug',
                'author_id',
                'created_at',
                'updated_at',
            ]);
    }

    /**
     * Test if user can edit his post
     * @return void
     */
    public function testEditPost(): void
    {
        $params = [
            'edited content',
        ];
        $response = $this->json('PUT', '/api/posts/' . $this->loggedInUsersPost->id, $params);
        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'title',
                'content',
                'slug',
                'author_id',
                'created_at',
                'updated_at',
            ]);
    }

    /**
     * Test if user can edit posts from other users
     * @return void
     */
    public function testEditPostFromOtherUser(): void
    {
        $params = [
            'edited content',
        ];
        $response = $this->json('PUT', '/api/posts/' . $this->postFromOtherUser->id, $params);
        $response->assertStatus(403);
    }

    /**
     * Test if user can edit his post
     * @return void
     */
    public function testDeletePost(): void
    {
        $response = $this->json('DELETE', '/api/posts/' . $this->loggedInUsersPost->id);
        $response->assertStatus(200);
    }

    /**
     * Test if user can edit posts from other users
     * @return void
     */
    public function testDeletePostFromOtherUser(): void
    {
        $response = $this->json('DELETE', '/api/posts/' . $this->postFromOtherUser->id);
        $response->assertStatus(403);
    }
}
