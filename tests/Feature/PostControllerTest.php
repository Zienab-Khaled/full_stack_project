<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PostControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_user_can_get_posts_with_pagination()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        // Create 15 posts
        for ($i = 1; $i <= 15; $i++) {
            $user->posts()->create([
                'title' => "Post {$i}",
                'content' => "Content for post {$i}",
                'status' => 'published',
            ]);
        }

        $response = $this->getJson('/api/posts?per_page=10');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'posts',
                'pagination' => [
                    'current_page',
                    'last_page',
                    'per_page',
                    'total',
                ]
            ]);

        $this->assertEquals(10, count($response->json('posts')));
        $this->assertEquals(15, $response->json('pagination.total'));
        $this->assertEquals(2, $response->json('pagination.last_page'));
    }

    public function test_user_can_search_posts_by_title()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $user->posts()->create([
            'title' => 'Laravel Tutorial',
            'content' => 'Learn Laravel framework',
            'status' => 'published',
        ]);

        $user->posts()->create([
            'title' => 'Vue.js Guide',
            'content' => 'Learn Vue.js framework',
            'status' => 'published',
        ]);

        $response = $this->getJson('/api/posts?search=Laravel');

        $response->assertStatus(200);
        $this->assertEquals(1, count($response->json('posts')));
        $this->assertEquals('Laravel Tutorial', $response->json('posts.0.title'));
    }

    public function test_user_can_search_posts_by_content()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $user->posts()->create([
            'title' => 'First Post',
            'content' => 'This post contains Laravel content',
            'status' => 'published',
        ]);

        $user->posts()->create([
            'title' => 'Second Post',
            'content' => 'This post contains Vue.js content',
            'status' => 'published',
        ]);

        $response = $this->getJson('/api/posts?search=Vue.js');

        $response->assertStatus(200);
        $this->assertEquals(1, count($response->json('posts')));
        $this->assertEquals('Second Post', $response->json('posts.0.title'));
    }

    public function test_user_can_filter_posts_by_status()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $user->posts()->create([
            'title' => 'Published Post',
            'content' => 'This is published',
            'status' => 'published',
        ]);

        $user->posts()->create([
            'title' => 'Draft Post',
            'content' => 'This is draft',
            'status' => 'draft',
        ]);

        $response = $this->getJson('/api/posts?status=published');

        $response->assertStatus(200);
        $this->assertEquals(1, count($response->json('posts')));
        $this->assertEquals('Published Post', $response->json('posts.0.title'));
    }

    public function test_user_can_create_post()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $postData = [
            'title' => 'New Post',
            'content' => 'This is a new post content',
            'status' => 'published',
        ];

        $response = $this->postJson('/api/posts', $postData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'post' => [
                    'id',
                    'title',
                    'content',
                    'status',
                    'author',
                ],
                'message'
            ]);

        $this->assertDatabaseHas('posts', [
            'title' => 'New Post',
            'content' => 'This is a new post content',
            'status' => 'published',
            'author_type' => User::class,
            'author_id' => $user->id,
        ]);
    }

    public function test_user_can_view_own_post()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $post = $user->posts()->create([
            'title' => 'My Post',
            'content' => 'My post content',
            'status' => 'published',
        ]);

        $response = $this->getJson("/api/posts/{$post->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'post' => [
                    'id',
                    'title',
                    'content',
                    'status',
                    'author',
                ]
            ]);

        $this->assertEquals('My Post', $response->json('post.title'));
    }

    public function test_user_cannot_view_other_users_post()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        Sanctum::actingAs($user1);

        $post = $user2->posts()->create([
            'title' => 'Other User Post',
            'content' => 'Other user content',
            'status' => 'published',
        ]);

        $response = $this->getJson("/api/posts/{$post->id}");

        $response->assertStatus(403);
    }

    public function test_user_can_update_own_post()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $post = $user->posts()->create([
            'title' => 'Original Title',
            'content' => 'Original content',
            'status' => 'draft',
        ]);

        $updateData = [
            'title' => 'Updated Title',
            'content' => 'Updated content',
            'status' => 'published',
        ];

        $response = $this->putJson("/api/posts/{$post->id}", $updateData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'post' => [
                    'id',
                    'title',
                    'content',
                    'status',
                    'author',
                ],
                'message'
            ]);

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => 'Updated Title',
            'content' => 'Updated content',
            'status' => 'published',
        ]);
    }

    public function test_user_cannot_update_other_users_post()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        Sanctum::actingAs($user1);

        $post = $user2->posts()->create([
            'title' => 'Other User Post',
            'content' => 'Other user content',
            'status' => 'published',
        ]);

        $updateData = [
            'title' => 'Hacked Title',
            'content' => 'Hacked content',
            'status' => 'published',
        ];

        $response = $this->putJson("/api/posts/{$post->id}", $updateData);

        $response->assertStatus(403);
    }

    public function test_user_can_delete_own_post()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $post = $user->posts()->create([
            'title' => 'Post to Delete',
            'content' => 'Content to delete',
            'status' => 'published',
        ]);

        $response = $this->deleteJson("/api/posts/{$post->id}");

        $response->assertStatus(200)
            ->assertJsonStructure(['message']);

        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }

    public function test_user_cannot_delete_other_users_post()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        Sanctum::actingAs($user1);

        $post = $user2->posts()->create([
            'title' => 'Other User Post',
            'content' => 'Other user content',
            'status' => 'published',
        ]);

        $response = $this->deleteJson("/api/posts/{$post->id}");

        $response->assertStatus(403);
        $this->assertDatabaseHas('posts', ['id' => $post->id]);
    }

    public function test_admin_can_view_all_posts()
    {
        $admin = Admin::factory()->create();
        $user = User::factory()->create();
        Sanctum::actingAs($admin);

        // Create posts by both admin and user
        $admin->posts()->create([
            'title' => 'Admin Post',
            'content' => 'Admin content',
            'status' => 'published',
        ]);

        $user->posts()->create([
            'title' => 'User Post',
            'content' => 'User content',
            'status' => 'published',
        ]);

        $response = $this->getJson('/api/posts');

        $response->assertStatus(200);
        $this->assertEquals(2, count($response->json('posts')));
    }

    public function test_admin_can_view_any_post()
    {
        $admin = Admin::factory()->create();
        $user = User::factory()->create();
        Sanctum::actingAs($admin);

        $post = $user->posts()->create([
            'title' => 'User Post',
            'content' => 'User content',
            'status' => 'published',
        ]);

        $response = $this->getJson("/api/posts/{$post->id}");

        $response->assertStatus(200);
        $this->assertEquals('User Post', $response->json('post.title'));
    }

    public function test_admin_can_update_any_post()
    {
        $admin = Admin::factory()->create();
        $user = User::factory()->create();
        Sanctum::actingAs($admin);

        $post = $user->posts()->create([
            'title' => 'User Post',
            'content' => 'User content',
            'status' => 'draft',
        ]);

        $updateData = [
            'title' => 'Updated by Admin',
            'content' => 'Updated by admin',
            'status' => 'published',
        ];

        $response = $this->putJson("/api/posts/{$post->id}", $updateData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => 'Updated by Admin',
        ]);
    }

    public function test_admin_can_delete_any_post()
    {
        $admin = Admin::factory()->create();
        $user = User::factory()->create();
        Sanctum::actingAs($admin);

        $post = $user->posts()->create([
            'title' => 'User Post',
            'content' => 'User content',
            'status' => 'published',
        ]);

        $response = $this->deleteJson("/api/posts/{$post->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }

    public function test_unauthenticated_user_cannot_access_posts()
    {
        $response = $this->getJson('/api/posts');
        $response->assertStatus(401);

        $response = $this->postJson('/api/posts', []);
        $response->assertStatus(401);

        $response = $this->getJson('/api/posts/1');
        $response->assertStatus(401);

        $response = $this->putJson('/api/posts/1', []);
        $response->assertStatus(401);

        $response = $this->deleteJson('/api/posts/1');
        $response->assertStatus(401);
    }

    public function test_posts_are_ordered_by_created_at_desc()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $post1 = $user->posts()->create([
            'title' => 'First Post',
            'content' => 'First content',
            'status' => 'published',
        ]);

        // Wait a moment to ensure different timestamps
        sleep(1);

        $post2 = $user->posts()->create([
            'title' => 'Second Post',
            'content' => 'Second content',
            'status' => 'published',
        ]);

        $response = $this->getJson('/api/posts');

        $response->assertStatus(200);
        $posts = $response->json('posts');
        $this->assertEquals('Second Post', $posts[0]['title']);
        $this->assertEquals('First Post', $posts[1]['title']);
    }
}
