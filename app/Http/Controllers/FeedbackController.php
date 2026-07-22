<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'rating'   => ['required', 'integer', 'min:1', 'max:5'],
            'category' => ['required', 'string', 'in:general,feature_request,bug,competitor_review'],
            'message'  => ['required', 'string', 'min:10', 'max:2000'],
        ]);

        Feedback::create([
            'user_id'  => auth()->id(),
            'rating'   => $data['rating'],
            'category' => $data['category'],
            'message'  => $data['message'],
            'status'   => 'new',
        ]);

        return back()->with('ok', 'Thank you! Your feedback has been submitted successfully.');
    }
}
