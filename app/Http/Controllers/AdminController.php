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
}
