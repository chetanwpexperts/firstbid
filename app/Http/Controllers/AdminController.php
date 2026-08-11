<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.users', compact('users'));
    }

    public function toggleApproval(User $user)
    {
        $user->is_approved = ! $user->is_approved;
        $user->save();

        $status = $user->is_approved ? "User '{$user->name}' has been approved." : "Approval for '{$user->name}' has been revoked.";

        return back()->with('ok', $status);
    }

    public function toggleAdmin(User $user)
    {
        if (auth()->id() === $user->id) {
            return back()->with('err', 'You cannot remove your own admin status.');
        }

        $user->is_admin = ! $user->is_admin;
        $user->save();

        $status = $user->is_admin ? "Granted admin access to '{$user->name}'." : "Revoked admin access for '{$user->name}'.";

        return back()->with('ok', $status);
    }

    public function updateUser(Request $request, User $user)
    {
        $data = $request->validate([
            'plan'          => ['required', 'in:free,pro'],
            'letters_quota' => ['required', 'integer', 'min:0'],
        ]);

        $user->update([
            'plan'          => $data['plan'],
            'letters_quota' => $data['letters_quota'],
        ]);

        return back()->with('ok', "Updated settings for '{$user->name}'.");
    }

    public function deleteUser(User $user)
    {
        if (auth()->id() === $user->id) {
            return back()->with('err', 'You cannot delete your own account.');
        }

        $name = $user->name;
        $user->delete();

        return back()->with('ok', "User '{$name}' was deleted.");
    }

    public function feedback(Request $request)
    {
        $feedbacks = \App\Models\Feedback::with('user')
            ->latest()
            ->paginate(15);

        return view('admin.feedback', compact('feedbacks'));
    }

    public function deleteFeedback(\App\Models\Feedback $feedback)
    {
        $feedback->delete();

        return back()->with('ok', 'Feedback record deleted.');
    }

    public function blogs(Request $request)
    {
        $blogs = \App\Models\Blog::withCount(['comments' => function ($q) {
            $q->where('is_approved', true);
        }])->latest('published_at')->paginate(10);

        $comments = \App\Models\BlogComment::with('blog', 'user')->latest()->paginate(15);

        return view('admin.blogs', compact('blogs', 'comments'));
    }

    public function triggerBlogGenerator()
    {
        try {
            \Illuminate\Support\Facades\Artisan::call('blog:generate');
            return back()->with('ok', 'AI Blog Generator triggered successfully! New article published.');
        } catch (\Throwable $e) {
            return back()->with('err', 'Failed to generate blog: ' . $e->getMessage());
        }
    }

    public function deleteBlog(\App\Models\Blog $blog)
    {
        $title = $blog->title;
        $blog->delete();

        return back()->with('ok', "Deleted blog article '{$title}'.");
    }

    public function toggleCommentApproval(\App\Models\BlogComment $comment)
    {
        $comment->is_approved = ! $comment->is_approved;
        $comment->save();

        $status = $comment->is_approved ? 'Comment approved.' : 'Comment unapproved.';

        return back()->with('ok', $status);
    }

    public function deleteComment(\App\Models\BlogComment $comment)
    {
        $comment->delete();

        return back()->with('ok', 'Comment deleted successfully.');
    }

    public function storeBlog(Request $request)
    {
        $data = $request->validate([
            'title'            => 'required|string|max:255',
            'category'         => 'required|string|max:100',
            'meta_description' => 'required|string|max:300',
            'content'          => 'required|string',
        ]);

        $slug = \Illuminate\Support\Str::slug($data['title']);
        $count = \App\Models\Blog::where('slug', 'like', "{$slug}%")->count();
        if ($count > 0) {
            $slug .= '-' . ($count + 1);
        }

        $wordCount = str_word_count(strip_tags($data['content']));
        $readingTime = max(1, (int) ceil($wordCount / 200));

        \App\Models\Blog::create([
            'slug'                 => $slug,
            'title'                => $data['title'],
            'meta_title'           => $data['title'] . ' | FirstBidIn',
            'meta_description'     => $data['meta_description'],
            'content'              => $data['content'],
            'category'             => $data['category'],
            'reading_time_minutes' => $readingTime,
            'views_count'          => 0,
            'likes_count'          => 0,
            'is_published'         => true,
            'published_at'         => now(),
        ]);

        return back()->with('ok', 'New blog article published successfully!');
    }
}
