<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Post::with('author');

        // Search functionality
        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('content', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by status
        if ($request->has('status') && in_array($request->status, ['published', 'draft'])) {
            $query->where('status', $request->status);
        }

        // If user is not admin, show only their posts
        if ($request->user() instanceof \App\Models\User) {
            $query->where('author_type', \App\Models\User::class)
                ->where('author_id', $request->user()->id);
        }

        // Pagination
        $posts = $query->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 5));

        $postsWithUrls = $posts->items();
        foreach ($postsWithUrls as $post) {
            $post->image_url = $post->image ? asset('storage/' . $post->image) : null;
        }

        return response()->json([
            'posts' => $postsWithUrls,
            'pagination' => [
                'current_page' => $posts->currentPage(),
                'last_page' => $posts->lastPage(),
                'per_page' => $posts->perPage(),
                'total' => $posts->total(),
            ]
        ]);
    }

    /**
     * Get post statistics for dashboard.
     */
    public function stats(Request $request): JsonResponse
    {
        $baseConditions = [];
        if ($request->user() instanceof \App\Models\User) {
            $baseConditions = [
                'author_type' => \App\Models\User::class,
                'author_id' => $request->user()->id
            ];
        }

        $totalPosts = Post::where($baseConditions)->count();
        $publishedPosts = Post::where($baseConditions)->where('status', 'published')->count();
        $draftPosts = Post::where($baseConditions)->where('status', 'draft')->count();

        return response()->json([
            'stats' => [
                'total' => $totalPosts,
                'published' => $publishedPosts,
                'draft' => $draftPosts,
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostRequest $request): JsonResponse
    {
        // Check if user is authenticated
        if (!$request->user()) {
            return response()->json(['message' => 'Unauthorized. Please login first.'], 401);
        }

        $data = $request->validated();

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('posts', 'public');
            $data['image'] = $imagePath;
        }

        $post = new Post($data);
        $post->author()->associate($request->user());
        $post->save();

        return response()->json([
            'post' => $post->load('author'),
            'message' => 'Post created successfully'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id): JsonResponse
    {
        $post = Post::with('author')->findOrFail($id);


        if (
            $request->user() instanceof \App\Models\User &&
            $post->author_type === \App\Models\User::class &&
            $post->author_id !== $request->user()->id
        ) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json(['post' => $post]);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(PostRequest $request, string $id): JsonResponse
    {
        $post = Post::findOrFail($id);

        // Check if user can update this post
        if (
            $request->user() instanceof \App\Models\User &&
            $post->author_type === \App\Models\User::class &&
            $post->author_id !== $request->user()->id
        ) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $data = $request->validated();

        // Handle image removal
        if ($request->has('remove_image') && $request->remove_image) {
            // Delete old image if exists
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }
            $data['image'] = null;
        }
        // Handle image upload
        elseif ($request->hasFile('image')) {
            // Delete old image if exists
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }

            $imagePath = $request->file('image')->store('posts', 'public');
            $data['image'] = $imagePath;
        }

        $post->update($data);

        return response()->json([
            'post' => $post->load('author'),
            'message' => 'Post updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id): JsonResponse
    {
        $post = Post::findOrFail($id);

        // Check if user can delete this post
        if (
            $request->user() instanceof \App\Models\User &&
            $post->author_type === \App\Models\User::class &&
            $post->author_id !== $request->user()->id
        ) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Delete associated image if exists
        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }

        $post->delete();

        return response()->json([
            'message' => 'Post deleted successfully'
        ]);
    }
}
