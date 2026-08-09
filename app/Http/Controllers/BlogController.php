<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $query = Blog::where('is_published', true)->latest('published_at');

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $blogs = $query->paginate(9);

        return view('blog.index', compact('blogs'));
    }

    public function show(string $slug)
    {
        $blog = Blog::where('slug', $slug)->where('is_published', true)->firstOrFail();
        $blog->increment('views_count');

        $relatedBlogs = Blog::where('is_published', true)
            ->where('id', '!=', $blog->id)
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('blog.show', compact('blog', 'relatedBlogs'));
    }
}
