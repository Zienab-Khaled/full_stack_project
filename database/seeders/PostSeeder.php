<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some users and admins for creating posts
        $users = User::all();
        $admins = Admin::all();

        // Create posts by users
        foreach ($users as $user) {
            Post::create([
                'title' => "User Post by {$user->name}",
                'content' => "This is a sample post created by user {$user->name}. It contains some interesting content about various topics.",
                'status' => 'published',
                'author_type' => User::class,
                'author_id' => $user->id,
            ]);

            Post::create([
                'title' => "Draft Post by {$user->name}",
                'content' => "This is a draft post by {$user->name} that hasn't been published yet.",
                'status' => 'draft',
                'author_type' => User::class,
                'author_id' => $user->id,
            ]);
        }

        // Create posts by admins
        foreach ($admins as $admin) {
            Post::create([
                'title' => "Admin Post by {$admin->name}",
                'content' => "This is an official post created by admin {$admin->name}. It contains important information and announcements.",
                'status' => 'published',
                'author_type' => Admin::class,
                'author_id' => $admin->id,
            ]);

            Post::create([
                'title' => "Admin Draft by {$admin->name}",
                'content' => "This is a draft post by admin {$admin->name} that is being reviewed before publication.",
                'status' => 'draft',
                'author_type' => Admin::class,
                'author_id' => $admin->id,
            ]);
        }

        // Create some additional posts with different content
        Post::create([
            'title' => 'Welcome to Our Platform',
            'content' => 'Welcome to our amazing platform! This post introduces new users to the features and capabilities available.',
            'status' => 'published',
            'author_type' => Admin::class,
            'author_id' => $admins->first()->id,
        ]);

        Post::create([
            'title' => 'Getting Started Guide',
            'content' => 'This comprehensive guide will help you get started with our platform. Follow these steps to make the most of your experience.',
            'status' => 'published',
            'author_type' => Admin::class,
            'author_id' => $admins->first()->id,
        ]);

        Post::create([
            'title' => 'My First Blog Post',
            'content' => 'This is my first blog post on this platform. I\'m excited to share my thoughts and experiences with the community.',
            'status' => 'published',
            'author_type' => User::class,
            'author_id' => $users->first()->id,
        ]);
    }
}
