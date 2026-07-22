@extends('layout')

@section('title', 'Admin Portal — User Management')

@section('content')
<div class="glass-panel">
  <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 14px; margin-bottom: 24px;">
    <div>
      <h1 style="font-size: 24px; font-weight: 800; color: var(--text-dark); margin-bottom: 4px;">User Management</h1>
      <p style="color: var(--text-muted); font-size: 14px;">Approve users, assign admin access, and manage letter quotas.</p>
    </div>
    
    <form method="GET" action="{{ route('admin.users') }}" style="display: flex; gap: 8px;">
      <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or email..." style="width: 250px;">
      <button class="btn btn-sm" type="submit">Search</button>
    </form>
  </div>

  <div style="overflow-x: auto;">
    <table style="width: 100%; border-collapse: collapse; font-size: 14px; text-align: left;">
      <thead>
        <tr style="border-bottom: 2px solid var(--border); font-family: var(--font-mono); color: var(--text-muted); font-size: 11.5px; text-transform: uppercase;">
          <th style="padding: 12px 10px;">User</th>
          <th style="padding: 12px 10px;">Status</th>
          <th style="padding: 12px 10px;">Role</th>
          <th style="padding: 12px 10px;">Plan & Quota</th>
          <th style="padding: 12px 10px;">Registered</th>
          <th style="padding: 12px 10px; text-align: right;">Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($users as $u)
        <tr style="border-bottom: 1px solid var(--border);">
          <td style="padding: 14px 10px;">
            <div style="font-weight: 700; color: var(--text-dark);">{{ $u->name }}</div>
            <div style="font-size: 12.5px; color: var(--text-muted);">{{ $u->email }}</div>
          </td>
          
          <td style="padding: 14px 10px;">
            @if($u->is_approved)
              <span class="badge badge-notified">Approved</span>
            @else
              <span class="badge badge-pending">Pending</span>
            @endif
          </td>

          <td style="padding: 14px 10px;">
            @if($u->is_admin)
              <span class="badge" style="background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe;">Admin</span>
            @else
              <span class="badge badge-skipped">User</span>
            @endif
          </td>

          <td style="padding: 14px 10px;">
            <form method="POST" action="{{ route('admin.users.update', $u->id) }}" style="display: flex; gap: 6px; align-items: center;">
              @csrf
              <select name="plan" style="font-size: 12.5px; padding: 4px 8px; border-radius: 6px; width: 75px;">
                <option value="free" {{ $u->plan === 'free' ? 'selected' : '' }}>Free</option>
                <option value="pro" {{ $u->plan === 'pro' ? 'selected' : '' }}>Pro</option>
              </select>
              <input type="number" name="letters_quota" value="{{ $u->letters_quota }}" title="Letters Quota" style="width: 70px; font-size: 12.5px; padding: 4px 8px; border-radius: 6px;">
              <button class="btn btn-ghost btn-sm" type="submit" style="padding: 4px 8px;">Save</button>
            </form>
            <div style="font-size: 11.5px; color: var(--text-muted); margin-top: 3px; font-family: var(--font-mono);">Used: {{ $u->letters_used }} / {{ $u->letters_quota }}</div>
          </td>

          <td style="padding: 14px 10px; font-family: var(--font-mono); font-size: 12.5px; color: var(--text-muted);">
            {{ $u->created_at->format('M d, Y') }}
          </td>

          <td style="padding: 14px 10px; text-align: right;">
            <div style="display: flex; gap: 6px; justify-content: flex-end; align-items: center;">
              <form method="POST" action="{{ route('admin.users.toggle-approval', $u->id) }}">
                @csrf
                <button class="btn btn-sm {{ $u->is_approved ? 'btn-ghost' : '' }}" type="submit">
                  {{ $u->is_approved ? 'Revoke' : 'Approve' }}
                </button>
              </form>

              @if(auth()->id() !== $u->id)
              <form method="POST" action="{{ route('admin.users.toggle-admin', $u->id) }}">
                @csrf
                <button class="btn btn-ghost btn-sm" type="submit">
                  {{ $u->is_admin ? 'Demote' : 'Make Admin' }}
                </button>
              </form>

              <form method="POST" action="{{ route('admin.users.delete', $u->id) }}" onsubmit="return confirm('Delete user {{ $u->name }}? This action cannot be undone.');">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger btn-sm" type="submit">Delete</button>
              </form>
              @endif
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="6" style="padding: 30px; text-align: center; color: var(--text-muted);">No users found.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div style="margin-top: 20px;">
    {{ $users->links() }}
  </div>
</div>
@endsection
