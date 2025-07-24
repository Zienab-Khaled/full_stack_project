<?php

namespace Tests\Unit;

use App\Models\Post;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    public function test_post_can_be_created_with_valid_data()
    {
        $user = User::factory()->create();
        $post = new Post([
            'title' => 'Test Post',
            'content' => 'This is a test post content',
            'status' => 'published',
        ]);
        $post->author()->associate($user);
        $post->save();

        $this->assertDatabaseHas('posts', [
            'title' => 'Test Post',
            'content' => 'This is a test post content',
            'status' => 'published',
            'author_type' => User::class,
            'author_id' => $user->id,
        ]);
    }

    public function test_post_belongs_to_user_author()
    {
        $user = User::factory()->create();
        $post = new Post([
            'title' => 'Test Post',
            'content' => 'Test content',
            'status' => 'published',
        ]);
        $post->author()->associate($user);
        $post->save();

        $this->assertInstanceOf(User::class, $post->author);
        $this->assertEquals($user->id, $post->author->id);
    }

    public function test_post_belongs_to_admin_author()
    {
        $admin = Admin::factory()->create();
        $post = new Post([
            'title' => 'Test Post',
            'content' => 'Test content',
            'status' => 'published',
        ]);
        $post->author()->associate($admin);
        $post->save();

        $this->assertInstanceOf(Admin::class, $post->author);
        $this->assertEquals($admin->id, $post->author->id);
    }

    public function test_published_scope_returns_only_published_posts()
    {
        $user = User::factory()->create();

        $publishedPost = new Post([
            'title' => 'Published Post',
            'content' => 'Content',
            'status' => 'published',
        ]);
        $publishedPost->author()->associate($user);
        $publishedPost->save();

        $draftPost = new Post([
            'title' => 'Draft Post',
            'content' => 'Content',
            'status' => 'draft',
        ]);
        $draftPost->author()->associate($user);
        $draftPost->save();

        $publishedPosts = Post::published()->get();

        $this->assertEquals(1, $publishedPosts->count());
        $this->assertEquals('Published Post', $publishedPosts->first()->title);
    }

    public function test_draft_scope_returns_only_draft_posts()
    {
        $user = User::factory()->create();

        $publishedPost = new Post([
            'title' => 'Published Post',
            'content' => 'Content',
            'status' => 'published',
        ]);
        $publishedPost->author()->associate($user);
        $publishedPost->save();

        $draftPost = new Post([
            'title' => 'Draft Post',
            'content' => 'Content',
            'status' => 'draft',
        ]);
        $draftPost->author()->associate($user);
        $draftPost->save();

        $draftPosts = Post::draft()->get();

        $this->assertEquals(1, $draftPosts->count());
        $this->assertEquals('Draft Post', $draftPosts->first()->title);
    }

    public function test_post_can_be_updated()
    {
        $user = User::factory()->create();
        $post = new Post([
            'title' => 'Original Title',
            'content' => 'Original content',
            'status' => 'draft',
        ]);
        $post->author()->associate($user);
        $post->save();

        $post->update([
            'title' => 'Updated Title',
            'content' => 'Updated content',
            'status' => 'published',
        ]);

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => 'Updated Title',
            'content' => 'Updated content',
            'status' => 'published',
        ]);
    }

    public function test_post_can_be_deleted()
    {
        $user = User::factory()->create();
        $post = new Post([
            'title' => 'Test Post',
            'content' => 'Test content',
            'status' => 'published',
        ]);
        $post->author()->associate($user);
        $post->save();

        $postId = $post->id;
        $post->delete();

        $this->assertDatabaseMissing('posts', ['id' => $postId]);
    }

    public function test_post_has_correct_fillable_attributes()
    {
        $post = new Post();
        $fillable = $post->getFillable();

        $this->assertContains('title', $fillable);
        $this->assertContains('content', $fillable);
        $this->assertContains('status', $fillable);
        $this->assertContains('image', $fillable);
    }

    public function test_post_has_correct_casts()
    {
        $post = new Post();
        $casts = $post->getCasts();

        $this->assertEquals('string', $casts['status']);
    }

    public function test_post_image_url_attribute()
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

        $this->assertEquals(asset('storage/posts/test-image.jpg'), $post->image_url);
    }

    public function test_post_has_image_method()
    {
        $user = User::factory()->create();

        $postWithImage = new Post([
            'title' => 'Post with Image',
            'content' => 'Content',
            'status' => 'published',
            'image' => 'posts/test-image.jpg',
        ]);
        $postWithImage->author()->associate($user);
        $postWithImage->save();

        $postWithoutImage = new Post([
            'title' => 'Post without Image',
            'content' => 'Content',
            'status' => 'published',
        ]);
        $postWithoutImage->author()->associate($user);
        $postWithoutImage->save();

        $this->assertTrue($postWithImage->hasImage());
        $this->assertFalse($postWithoutImage->hasImage());
    }
}
