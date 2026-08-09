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

    public function show(Request $request, string $slug)
    {
        $blog = Blog::where('slug', $slug)->where('is_published', true)->firstOrFail();

        // Anti-Spam & Bot Filtering: Only count real unique visitor views per session
        $userAgent = strtolower($request->header('User-Agent', ''));
        $isBot = preg_match('/(googlebot|bingbot|slurp|duckduckbot|baiduspider|yandexbot|crawler|spider|bot)/i', $userAgent);
        $sessionKey = 'viewed_blog_' . $blog->id;

        if (! $isBot && ! $request->session()->has($sessionKey)) {
            $blog->increment('views_count');
            $request->session()->put($sessionKey, true);
        }

        $relatedBlogs = Blog::where('is_published', true)
            ->where('id', '!=', $blog->id)
            ->latest('published_at')
            ->take(3)
            ->get();

        $isLiked = $request->session()->has('liked_blog_' . $blog->id);

        return view('blog.show', compact('blog', 'relatedBlogs', 'isLiked'));
    }

    public function toggleLike(Request $request, string $slug)
    {
        $blog = Blog::where('slug', $slug)->where('is_published', true)->firstOrFail();
        $sessionKey = 'liked_blog_' . $blog->id;

        if ($request->session()->has($sessionKey)) {
            $blog->decrement('likes_count');
            $request->session()->forget($sessionKey);
            $liked = false;
        } else {
            $blog->increment('likes_count');
            $request->session()->put($sessionKey, true);
            $liked = true;
        }

        $freshCount = max(0, $blog->fresh()->likes_count);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success'     => true,
                'liked'       => $liked,
                'likes_count' => $freshCount,
            ]);
        }

        return back();
    }
}
