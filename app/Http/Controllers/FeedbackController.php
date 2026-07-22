<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'rating'   => ['nullable', 'integer', 'between:1,5'],
            'category' => ['required', 'string', 'in:general,feature_request,bug,competitor_review,extension_waitlist'],
            'message'  => ['required', 'string', 'min:5', 'max:2000'],
            'email'    => ['nullable', 'email', 'max:255'],
        ]);

        $message = $data['message'];
        if (!auth()->check() && !empty($data['email'])) {
            $message = "[Waitlist Email: {$data['email']}] " . $message;
        }

        Feedback::create([
            'user_id'  => auth()->id(),
            'rating'   => $data['rating'] ?? 0,
            'category' => $data['category'],
            'message'  => $message,
            'status'   => 'new',
        ]);

        return back()->with('ok', 'Thank you! You have been added to the Chrome Extension early waitlist.');
    }
}
