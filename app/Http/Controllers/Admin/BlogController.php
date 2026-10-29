<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Services\ActivityLogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    /**
     * List all blog articles (with search, category, status filters).
     */
    public function index(Request $request): JsonResponse
    {
        $query = Blog::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%")
                  ->orWhere('author_name', 'like', "%{$search}%");
            });
        }

        if ($category = $request->input('category')) {
            if ($category !== 'all') {
                $query->where('category', $category);
            }
        }

        if ($request->has('is_published') && $request->input('is_published') !== 'all') {
            $query->where('is_published', filter_var($request->input('is_published'), FILTER_VALIDATE_BOOLEAN));
        }

        $blogs = $query->orderBy('created_at', 'desc')
            ->paginate($request->input('per_page', 15));

        $stats = [
            'total' => Blog::count(),
            'published' => Blog::where('is_published', true)->count(),
            'draft' => Blog::where('is_published', false)->count(),
            'featured' => Blog::where('is_featured', true)->count(),
        ];

        return response()->json([
            'data' => $blogs->items(),
            'stats' => $stats,
            'meta' => [
                'current_page' => $blogs->currentPage(),
                'last_page' => $blogs->lastPage(),
                'total' => $blogs->total(),
                'per_page' => $blogs->perPage(),
            ],
        ]);
    }

    /**
     * Store a newly created blog post.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blogs,slug',
            'category' => 'required|string|max:100',
            'excerpt' => 'required|string',
            'content' => 'required|string',
            'image' => 'nullable|string',
            'author_name' => 'nullable|string|max:150',
            'read_time' => 'nullable|string|max:50',
            'tags' => 'nullable|array',
            'is_published' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'published_at' => 'nullable|date',
        ]);

        $slug = !empty($validated['slug'])
            ? Blog::generateUniqueSlug($validated['slug'])
            : Blog::generateUniqueSlug($validated['title']);

        $readTime = !empty($validated['read_time'])
            ? $validated['read_time']
            : Blog::estimateReadTime($validated['content']);

        $isPublished = $validated['is_published'] ?? true;

        $blog = Blog::create([
            'title' => $validated['title'],
            'slug' => $slug,
            'category' => $validated['category'],
            'excerpt' => $validated['excerpt'],
            'content' => $validated['content'],
            'image' => $validated['image'] ?? '/images/destinations/dubai.jpg',
            'author_name' => $validated['author_name'] ?: 'EasyTicket Editorial',
            'read_time' => $readTime,
            'tags' => $validated['tags'] ?? [],
            'is_published' => $isPublished,
            'is_featured' => $validated['is_featured'] ?? false,
            'views_count' => 0,
            'published_at' => $isPublished ? ($validated['published_at'] ?? Carbon::now()) : null,
        ]);

        ActivityLogService::log(
            $request->user(),
            'blog_created',
            "Created blog article: {$blog->title}",
            ['blog_id' => $blog->id, 'slug' => $blog->slug]
        );

        return response()->json([
            'message' => 'Blog article published successfully.',
            'data' => $blog,
        ], 201);
    }

    /**
     * Show single blog for editing.
     */
    public function show(int $id): JsonResponse
    {
        $blog = Blog::findOrFail($id);

        return response()->json([
            'data' => $blog,
        ]);
    }

    /**
     * Update an existing blog post.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $blog = Blog::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => "nullable|string|max:255|unique:blogs,slug,{$id}",
            'category' => 'required|string|max:100',
            'excerpt' => 'required|string',
            'content' => 'required|string',
            'image' => 'nullable|string',
            'author_name' => 'nullable|string|max:150',
            'read_time' => 'nullable|string|max:50',
            'tags' => 'nullable|array',
            'is_published' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'published_at' => 'nullable|date',
        ]);

        $slug = !empty($validated['slug'])
            ? Blog::generateUniqueSlug($validated['slug'], $id)
            : ($blog->slug ?: Blog::generateUniqueSlug($validated['title'], $id));

        $readTime = !empty($validated['read_time'])
            ? $validated['read_time']
            : Blog::estimateReadTime($validated['content']);

        $isPublished = isset($validated['is_published']) ? (bool) $validated['is_published'] : $blog->is_published;

        $blog->update([
            'title' => $validated['title'],
            'slug' => $slug,
            'category' => $validated['category'],
            'excerpt' => $validated['excerpt'],
            'content' => $validated['content'],
            'image' => $validated['image'] ?? $blog->image,
            'author_name' => $validated['author_name'] ?: $blog->author_name,
            'read_time' => $readTime,
            'tags' => $validated['tags'] ?? $blog->tags,
            'is_published' => $isPublished,
            'is_featured' => $validated['is_featured'] ?? $blog->is_featured,
            'published_at' => $isPublished ? ($validated['published_at'] ?? $blog->published_at ?? Carbon::now()) : null,
        ]);

        ActivityLogService::log(
            $request->user(),
            'blog_updated',
            "Updated blog article: {$blog->title}",
            ['blog_id' => $blog->id, 'slug' => $blog->slug]
        );

        return response()->json([
            'message' => 'Blog article updated successfully.',
            'data' => $blog,
        ]);
    }

    /**
     * Delete a blog article.
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $blog = Blog::findOrFail($id);
        $title = $blog->title;

        $blog->delete();

        ActivityLogService::log(
            $request->user(),
            'blog_deleted',
            "Deleted blog article: {$title}",
            ['blog_id' => $id]
        );

        return response()->json([
            'message' => 'Blog article deleted successfully.',
        ]);
    }

    /**
     * Upload an image for a blog article.
     */
    public function uploadImage(Request $request): JsonResponse
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $path = $request->file('image')->store('blogs', 'public');

        return response()->json([
            'message' => 'Blog image uploaded successfully.',
            'data' => [
                'url' => Storage::disk('public')->url($path),
            ],
        ]);
    }
}
