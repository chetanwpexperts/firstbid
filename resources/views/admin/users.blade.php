@extends('layout')

@section('title', 'Admin — User Management')

@section('content')
<div class="panel">
  <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; margin-bottom: 20px;">
    <div>
      <h1 style="margin-bottom: 4px;">User Management</h1>
      <p class="help">Approve users, manage subscriptions, and configure access limits.</p>
    </div>
    
    <form method="GET" action="{{ route('admin.users') }}" style="display: flex; gap: 8px;">
      <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or email..." style="width: 240px;">
      <button class="btn sm" type="submit">Search</button>
    </form>
  </div>

  <table style="width: 100%; border-collapse: collapse; font-size: 13.5px; text-align: left;">
    <thead>
      <tr style="border-bottom: 2px solid var(--line); font-family: var(--mono); color: var(--muted); font-size: 11px; text-transform: uppercase;">
        <th style="padding: 10px 8px;">User</th>
        <th style="padding: 10px 8px;">Status</th>
        <th style="padding: 10px 8px;">Role</th>
        <th style="padding: 10px 8px;">Plan & Quota</th>
        <th style="padding: 10px 8px;">Registered</th>
        <th style="padding: 10px 8px; text-align: right;">Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($users as $u)
      <tr style="border-bottom: 1px solid var(--line);">
        <td style="padding: 12px 8px;">
          <div style="font-weight: 600; color: var(--ink);">{{ $u->name }}</div>
          <div style="font-size: 12px; color: var(--muted);">{{ $u->email }}</div>
        </td>
        
        <td style="padding: 12px 8px;">
          @if($u->is_approved)
            <span class="badge notified">Approved</span>
          @else
            <span class="badge skipped" style="color: var(--amber); border-color: #fcd34d; background: #fffbeb;">Pending</span>
          @endif
        </td>

        <td style="padding: 12px 8px;">
          @if($u->is_admin)
            <span class="badge" style="background: #eff6ff; color: #1d4ed8; border-color: #bfdbfe;">Admin</span>
          @else
            <span class="badge">User</span>
          @endif
        </td>

        <td style="padding: 12px 8px;">
          <form method="POST" action="{{ route('admin.users.update', $u->id) }}" style="display: flex; gap: 6px; align-items: center;">
            @csrf
            <select name="plan" style="font-size: 12px; padding: 3px 6px; border-radius: 5px; border: 1px solid var(--line);">
              <option value="free" {{ $u->plan === 'free' ? 'selected' : '' }}>Free</option>
              <option value="pro" {{ $u->plan === 'pro' ? 'selected' : '' }}>Pro</option>
            </select>
            <input type="number" name="letters_quota" value="{{ $u->letters_quota }}" title="Letters Quota" style="width: 65px; font-size: 12px; padding: 3px 6px; border-radius: 5px; border: 1px solid var(--line);">
            <button class="btn ghost sm" type="submit" style="padding: 3px 7px;">Save</button>
          </form>
          <div style="font-size: 11px; color: var(--muted); margin-top: 2px;">Used: {{ $u->letters_used }} / {{ $u->letters_quota }}</div>
        </td>

        <td style="padding: 12px 8px; font-family: var(--mono); font-size: 12px; color: var(--muted);">
          {{ $u->created_at->format('M d, Y') }}
        </td>

        <td style="padding: 12px 8px; text-align: right;">
          <div style="display: flex; gap: 6px; justify-content: flex-end; align-items: center;">
            <form method="POST" action="{{ route('admin.users.toggle-approval', $u->id) }}">
              @csrf
              <button class="btn sm {{ $u->is_approved ? 'ghost' : '' }}" type="submit" style="{{ $u->is_approved ? '' : 'background: #14a800;' }}">
                {{ $u->is_approved ? 'Revoke' : 'Approve' }}
              </button>
            </form>

            @if(auth()->id() !== $u->id)
            <form method="POST" action="{{ route('admin.users.toggle-admin', $u->id) }}">
              @csrf
              <button class="btn ghost sm" type="submit">
                {{ $u->is_admin ? 'Demote' : 'Make Admin' }}
              </button>
            </form>

            <form method="POST" action="{{ route('admin.users.delete', $u->id) }}" onsubmit="return confirm('Delete user {{ $u->name }}? This action cannot be undone.');">
              @csrf
              @method('DELETE')
              <button class="btn ghost sm" type="submit" style="color: var(--red); border-color: #fecaca;">Delete</button>
            </form>
            @endif
          </div>
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="6" style="padding: 20px; text-align: center; color: var(--muted);">No users found.</td>
      </tr>
      @endforelse
    </tbody>
  </table>

  <div style="margin-top: 16px;">
    {{ $users->links() }}
  </div>
</div>
@endsection
