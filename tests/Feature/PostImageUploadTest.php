<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PostImageUploadTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    public function test_user_can_create_post_with_image()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $image = UploadedFile::fake()->image('post-image.jpg', 800, 600);

        $postData = [
            'title' => 'Post with Image',
            'content' => 'This post has an image',
            'status' => 'published',
            'image' => $image,
        ];

        $response = $this->postJson('/api/posts', $postData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'post' => [
                    'id',
                    'title',
                    'content',
                    'status',
                    'image',
                    'author',
                ],
                'message'
            ]);

        $this->assertDatabaseHas('posts', [
            'title' => 'Post with Image',
            'content' => 'This post has an image',
            'status' => 'published',
        ]);

        // Check if image was stored
        $post = Post::where('title', 'Post with Image')->first();
        $this->assertNotNull($post->image);
        Storage::disk('public')->assertExists($post->image);
    }

    public function test_user_can_create_post_without_image()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $postData = [
            'title' => 'Post without Image',
            'content' => 'This post has no image',
            'status' => 'published',
        ];

        $response = $this->postJson('/api/posts', $postData);

        $response->assertStatus(201);

        $this->assertDatabaseHas('posts', [
            'title' => 'Post without Image',
            'content' => 'This post has no image',
            'status' => 'published',
            'image' => null,
        ]);
    }

    public function test_user_can_update_post_with_new_image()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        // Create post with initial image
        $post = new Post([
            'title' => 'Original Post',
            'content' => 'Original content',
            'status' => 'published',
        ]);
        $post->author()->associate($user);
        $post->save();

        // Add initial image
        $post->update(['image' => 'posts/initial-image.jpg']);
        Storage::disk('public')->put('posts/initial-image.jpg', 'fake-image-content');

        // Update with new image
        $newImage = UploadedFile::fake()->image('new-image.jpg', 800, 600);
        $updateData = [
            'title' => 'Updated Post',
            'content' => 'Updated content',
            'status' => 'published',
            'image' => $newImage,
        ];

        $response = $this->putJson("/api/posts/{$post->id}", $updateData);

        $response->assertStatus(200);

        // Check if old image was deleted
        Storage::disk('public')->assertMissing('posts/initial-image.jpg');

        // Check if new image was stored
        $updatedPost = Post::find($post->id);
        $this->assertNotNull($updatedPost->image);
        Storage::disk('public')->assertExists($updatedPost->image);
    }

    public function test_user_can_update_post_remove_image()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        // Create post with image
        $post = new Post([
            'title' => 'Post with Image',
            'content' => 'Content with image',
            'status' => 'published',
        ]);
        $post->author()->associate($user);
        $post->save();

        // Add image
        $post->update(['image' => 'posts/test-image.jpg']);
        Storage::disk('public')->put('posts/test-image.jpg', 'fake-image-content');

        // Update without image (should keep existing image)
        $updateData = [
            'title' => 'Updated Post',
            'content' => 'Updated content',
            'status' => 'published',
        ];

        $response = $this->putJson("/api/posts/{$post->id}", $updateData);

        $response->assertStatus(200);

        // Check if image still exists
        $updatedPost = Post::find($post->id);
        $this->assertEquals('posts/test-image.jpg', $updatedPost->image);
        Storage::disk('public')->assertExists('posts/test-image.jpg');
    }

    public function test_image_validation_rejects_invalid_file_types()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $invalidFile = UploadedFile::fake()->create('document.pdf', 1000);

        $postData = [
            'title' => 'Post with Invalid Image',
            'content' => 'This post has an invalid image',
            'status' => 'published',
            'image' => $invalidFile,
        ];

        $response = $this->postJson('/api/posts', $postData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['image']);
    }

    public function test_image_validation_rejects_large_files()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $largeImage = UploadedFile::fake()->image('large-image.jpg', 800, 600)->size(3000); // 3MB

        $postData = [
            'title' => 'Post with Large Image',
            'content' => 'This post has a large image',
            'status' => 'published',
            'image' => $largeImage,
        ];

        $response = $this->postJson('/api/posts', $postData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['image']);
    }

    public function test_image_validation_accepts_valid_image_types()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $validImages = [
            UploadedFile::fake()->image('image.jpg', 800, 600),
            UploadedFile::fake()->image('image.jpeg', 800, 600),
            UploadedFile::fake()->image('image.png', 800, 600),
            UploadedFile::fake()->image('image.gif', 800, 600),
        ];

        foreach ($validImages as $image) {
            $postData = [
                'title' => 'Post with ' . $image->getClientOriginalName(),
                'content' => 'This post has a valid image',
                'status' => 'published',
                'image' => $image,
            ];

            $response = $this->postJson('/api/posts', $postData);

            $response->assertStatus(201);
        }
    }

    public function test_post_deletion_removes_associated_image()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        // Create post with image
        $image = UploadedFile::fake()->image('delete-test.jpg', 800, 600);
        $postData = [
            'title' => 'Post to Delete',
            'content' => 'This post will be deleted',
            'status' => 'published',
            'image' => $image,
        ];

        $createResponse = $this->postJson('/api/posts', $postData);
        $createResponse->assertStatus(201);

        $post = Post::where('title', 'Post to Delete')->first();
        $imagePath = $post->image;

        // Verify image exists
        Storage::disk('public')->assertExists($imagePath);

        // Delete post
        $deleteResponse = $this->deleteJson("/api/posts/{$post->id}");
        $deleteResponse->assertStatus(200);

        // Verify image was deleted
        Storage::disk('public')->assertMissing($imagePath);
    }

    public function test_post_model_image_url_attribute()
    {
        $user = User::factory()->create();
        $post = new Post([
            'title' => 'Test Post',
            'content' => 'Test content',
            'status' => 'published',
            'image' => 'posts/test-image.jpg',
        ]);
        $post->author()->associate($user);
        $post->save();

        // Test image URL generation
        $this->assertEquals(url('storage/posts/test-image.jpg'), $post->image_url);
        $this->assertTrue($post->hasImage());

        // Test post without image
        $postWithoutImage = new Post([
            'title' => 'Post without Image',
            'content' => 'Test content',
            'status' => 'published',
        ]);
        $postWithoutImage->author()->associate($user);
        $postWithoutImage->save();

        $this->assertNull($postWithoutImage->image_url);
        $this->assertFalse($postWithoutImage->hasImage());
    }

    public function test_admin_can_upload_image_to_any_post()
    {
        $admin = Admin::factory()->create();
        $user = User::factory()->create();
        Sanctum::actingAs($admin);

        // Create post by user
        $post = new Post([
            'title' => 'User Post',
            'content' => 'User content',
            'status' => 'published',
        ]);
        $post->author()->associate($user);
        $post->save();

        // Admin uploads image to user's post
        $image = UploadedFile::fake()->image('admin-upload.jpg', 800, 600);
        $updateData = [
            'title' => 'Updated by Admin',
            'content' => 'Updated by admin',
            'status' => 'published',
            'image' => $image,
        ];

        $response = $this->putJson("/api/posts/{$post->id}", $updateData);

        $response->assertStatus(200);

        $updatedPost = Post::find($post->id);
        $this->assertNotNull($updatedPost->image);
        Storage::disk('public')->assertExists($updatedPost->image);
    }

    public function test_user_cannot_upload_image_to_other_users_post()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        Sanctum::actingAs($user1);

        // Create post by user2
        $post = new Post([
            'title' => 'Other User Post',
            'content' => 'Other user content',
            'status' => 'published',
        ]);
        $post->author()->associate($user2);
        $post->save();

        // User1 tries to upload image to user2's post
        $image = UploadedFile::fake()->image('hacked-image.jpg', 800, 600);
        $updateData = [
            'title' => 'Hacked Post',
            'content' => 'Hacked content',
            'status' => 'published',
            'image' => $image,
        ];

        $response = $this->putJson("/api/posts/{$post->id}", $updateData);

        $response->assertStatus(403);
    }

    public function test_image_upload_with_multipart_form_data()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $image = UploadedFile::fake()->image('multipart-test.jpg', 800, 600);

        $response = $this->post('/api/posts', [
            'title' => 'Multipart Post',
            'content' => 'This post uses multipart form data',
            'status' => 'published',
            'image' => $image,
        ], [
            'Accept' => 'application/json',
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('posts', [
            'title' => 'Multipart Post',
            'content' => 'This post uses multipart form data',
            'status' => 'published',
        ]);
    }
}
