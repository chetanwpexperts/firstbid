<?php

namespace App\Http\Controllers;

use App\Models\ExtensionReview;
use Illuminate\Http\Request;

class ExtensionReviewController extends Controller
{
    /**
     * Store or update user's review for the extension
     * Route: POST /extension/review
     */
    public function store(Request $request)
    {
        $request->validate([
            'rating'      => 'required|integer|min:1|max:5',
            'review_text' => 'nullable|string|max:1000',
        ]);

        ExtensionReview::updateOrCreate(
            ['user_id' => auth()->id()],
            [
                'rating'      => $request->input('rating'),
                'review_text' => $request->input('review_text'),
            ]
        );

        return back()->with('success', 'Thank you! Your extension rating & feedback has been saved.');
    }
}
