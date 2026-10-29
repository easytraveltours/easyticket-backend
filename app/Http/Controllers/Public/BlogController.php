<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Get published blogs with search and filtering.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Blog::published();

        // Filter by category
        if ($category = $request->input('category')) {
            if ($category !== 'all') {
                $query->where('category', $category);
            }
        }

        // Search in title, excerpt, content, tags
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        // Tag filter
        if ($tag = $request->input('tag')) {
            $query->whereJsonContains('tags', $tag);
        }

        // Order by featured, then published_at descending
        $blogs = $query->orderBy('is_featured', 'desc')
            ->orderBy('published_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate($request->input('per_page', 12));

        // Available categories with post count
        $categories = Blog::published()
            ->selectRaw('category, COUNT(*) as count')
            ->groupBy('category')
            ->orderBy('category')
            ->get();

        return response()->json([
            'data' => $blogs->items(),
            'categories' => $categories,
            'meta' => [
                'current_page' => $blogs->currentPage(),
                'last_page' => $blogs->lastPage(),
                'total' => $blogs->total(),
                'per_page' => $blogs->perPage(),
            ],
        ]);
    }

    /**
     * Get single blog post by slug.
     */
    public function show(string $slug): JsonResponse
    {
        $blog = Blog::published()
            ->where('slug', $slug)
            ->orWhere('id', $slug)
            ->firstOrFail();

        // Increment views
        $blog->increment('views_count');

        // Related posts in same category or recent
        $related = Blog::published()
            ->where('id', '!=', $blog->id)
            ->where(function ($q) use ($blog) {
                $q->where('category', $blog->category)
                  ->orWhere('is_featured', true);
            })
            ->latest('published_at')
            ->take(3)
            ->get();

        return response()->json([
            'data' => $blog,
            'related' => $related,
        ]);
    }

    /**
     * Get featured blogs for homepage or hero cards.
     */
    public function featured(): JsonResponse
    {
        $featured = Blog::published()
            ->featured()
            ->latest('published_at')
            ->take(3)
            ->get();

        if ($featured->isEmpty()) {
            $featured = Blog::published()
                ->latest('published_at')
                ->take(3)
                ->get();
        }

        return response()->json([
            'data' => $featured,
        ]);
    }
}
