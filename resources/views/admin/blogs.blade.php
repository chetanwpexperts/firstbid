@extends('layout')

@section('title', 'Admin Blog & Comment Moderation — FirstBidIn')

@section('content')
<div style="max-width: 1140px; margin: 0 auto 50px;">
  <!-- Header Bar -->
  <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 28px; flex-wrap: wrap; gap: 16px;">
    <div>
      <h1 style="font-size: 26px; font-weight: 800; color: var(--text-dark); margin-bottom: 4px;">📰 Admin Blog Management</h1>
      <p style="font-size: 14px; color: var(--text-muted); margin: 0;">Monitor article analytics, moderate user comments, and trigger AI blog generation on demand.</p>
    </div>

    <!-- Trigger AI Blog Generator Button -->
    <form method="POST" action="{{ route('admin.blogs.generate') }}">
      @csrf
      <button class="btn" type="submit" style="background: var(--upwork-green); padding: 11px 22px; font-size: 14px; font-weight: 700; display: inline-flex; align-items: center; gap: 8px;">
        ⚡ Generate New AI Blog Article
      </button>
    </form>
  </div>

  <!-- Analytics Summary Grid -->
  <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 18px; margin-bottom: 32px;">
    <div class="glass-panel" style="padding: 20px; background: #ffffff;">
      <div style="font-size: 12px; font-family: var(--font-mono); color: var(--text-muted); text-transform: uppercase; font-weight: 700; margin-bottom: 6px;">Total Articles</div>
      <div style="font-size: 26px; font-weight: 800; color: var(--text-dark);">{{ number_format(\App\Models\Blog::count()) }}</div>
    </div>

    <div class="glass-panel" style="padding: 20px; background: #ffffff;">
      <div style="font-size: 12px; font-family: var(--font-mono); color: var(--text-muted); text-transform: uppercase; font-weight: 700; margin-bottom: 6px;">Unique Views</div>
      <div style="font-size: 26px; font-weight: 800; color: var(--upwork-green);">👁️ {{ number_format(\App\Models\Blog::sum('views_count')) }}</div>
    </div>

    <div class="glass-panel" style="padding: 20px; background: #ffffff;">
      <div style="font-size: 12px; font-family: var(--font-mono); color: var(--text-muted); text-transform: uppercase; font-weight: 700; margin-bottom: 6px;">Total Likes</div>
      <div style="font-size: 26px; font-weight: 800; color: #dc2626;">❤️ {{ number_format(\App\Models\Blog::sum('likes_count')) }}</div>
    </div>

    <div class="glass-panel" style="padding: 20px; background: #ffffff;">
      <div style="font-size: 12px; font-family: var(--font-mono); color: var(--text-muted); text-transform: uppercase; font-weight: 700; margin-bottom: 6px;">Total Comments</div>
      <div style="font-size: 26px; font-weight: 800; color: #2563eb;">💬 {{ number_format(\App\Models\BlogComment::count()) }}</div>
    </div>
  </div>

  <!-- Articles Table -->
  <div class="glass-panel" style="padding: 28px; background: #ffffff; margin-bottom: 40px;">
    <h3 style="font-size: 18px; font-weight: 800; color: var(--text-dark); margin-bottom: 18px;">Published Blog Articles</h3>

    <div style="overflow-x: auto;">
      <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 14px;">
        <thead>
          <tr style="border-bottom: 2px solid var(--border); color: var(--text-muted); font-size: 12px; font-family: var(--font-mono); text-transform: uppercase;">
            <th style="padding: 12px 10px;">Article Title</th>
            <th style="padding: 12px 10px;">Category</th>
            <th style="padding: 12px 10px;">Views</th>
            <th style="padding: 12px 10px;">Likes</th>
            <th style="padding: 12px 10px;">Comments</th>
            <th style="padding: 12px 10px;">Published</th>
            <th style="padding: 12px 10px; text-align: right;">Action</th>
          </tr>
        </thead>
        <tbody>
          @forelse($blogs as $b)
            <tr style="border-bottom: 1px solid var(--border);">
              <td style="padding: 14px 10px; font-weight: 700; max-width: 320px;">
                <a href="{{ route('blog.show', $b->slug) }}" target="_blank" style="color: var(--text-dark); text-decoration: none;">
                  {{ $b->title }} ↗
                </a>
              </td>
              <td style="padding: 14px 10px;">
                <span class="badge" style="background: var(--upwork-tint); color: var(--upwork-tint-text); font-size: 11px;">{{ $b->category }}</span>
              </td>
              <td style="padding: 14px 10px; font-family: var(--font-mono);">👁️ {{ number_format($b->views_count) }}</td>
              <td style="padding: 14px 10px; font-family: var(--font-mono);">❤️ {{ number_format($b->likes_count) }}</td>
              <td style="padding: 14px 10px; font-family: var(--font-mono);">💬 {{ number_format($b->comments_count) }}</td>
              <td style="padding: 14px 10px; font-size: 12.5px; color: var(--text-muted);">{{ $b->published_at->format('M d, Y') }}</td>
              <td style="padding: 14px 10px; text-align: right;">
                <form method="POST" action="{{ route('admin.blogs.delete', $b->id) }}" onsubmit="return confirm('Delete this article?')" style="margin: 0; display: inline;">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-ghost btn-sm" style="color: #dc2626; font-size: 12px; padding: 4px 8px;">Delete</button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" style="padding: 24px; text-align: center; color: var(--text-muted);">No blog articles found.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div style="margin-top: 20px;">
      {{ $blogs->links() }}
    </div>
  </div>

  <!-- Comments Moderation Table -->
  <div class="glass-panel" style="padding: 28px; background: #ffffff;">
    <h3 style="font-size: 18px; font-weight: 800; color: var(--text-dark); margin-bottom: 18px;">Blog Comments Moderation</h3>

    <div style="overflow-x: auto;">
      <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 13.5px;">
        <thead>
          <tr style="border-bottom: 2px solid var(--border); color: var(--text-muted); font-size: 12px; font-family: var(--font-mono); text-transform: uppercase;">
            <th style="padding: 12px 10px;">Author</th>
            <th style="padding: 12px 10px;">Article</th>
            <th style="padding: 12px 10px;">Comment Text</th>
            <th style="padding: 12px 10px;">Status</th>
            <th style="padding: 12px 10px;">Posted</th>
            <th style="padding: 12px 10px; text-align: right;">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($comments as $c)
            <tr style="border-bottom: 1px solid var(--border);">
              <td style="padding: 14px 10px;">
                <div style="font-weight: 700; color: var(--text-dark);">{{ $c->author_name }}</div>
                <div style="font-size: 11.5px; color: var(--text-muted);">{{ $c->author_email }}</div>
              </td>
              <td style="padding: 14px 10px; max-width: 200px;">
                @if($c->blog)
                  <a href="{{ route('blog.show', $c->blog->slug) }}" target="_blank" style="color: var(--upwork-green); font-size: 12.5px; font-weight: 600; text-decoration: none;">
                    {{ Str::limit($c->blog->title, 35) }} ↗
                  </a>
                @else
                  <span style="color: var(--text-muted);">Deleted Article</span>
                @endif
              </td>
              <td style="padding: 14px 10px; max-width: 300px; color: var(--text-main); font-size: 13px;">
                {{ Str::limit($c->comment, 120) }}
              </td>
              <td style="padding: 14px 10px;">
                @if($c->is_approved)
                  <span class="badge" style="background: #dcfce7; color: #166534; font-size: 11px;">Approved</span>
                @else
                  <span class="badge" style="background: #fef3c7; color: #92400e; font-size: 11px;">Pending</span>
                @endif
              </td>
              <td style="padding: 14px 10px; font-size: 12px; color: var(--text-muted); font-family: var(--font-mono);">
                {{ $c->created_at->diffForHumans() }}
              </td>
              <td style="padding: 14px 10px; text-align: right; white-space: nowrap;">
                <form method="POST" action="{{ route('admin.comments.toggle', $c->id) }}" style="margin: 0; display: inline;">
                  @csrf
                  <button type="submit" class="btn btn-ghost btn-sm" style="font-size: 12px; padding: 4px 8px;">
                    {{ $c->is_approved ? 'Unapprove' : 'Approve' }}
                  </button>
                </form>

                <form method="POST" action="{{ route('admin.comments.delete', $c->id) }}" onsubmit="return confirm('Delete this comment?')" style="margin: 0; display: inline;">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-ghost btn-sm" style="color: #dc2626; font-size: 12px; padding: 4px 8px;">Delete</button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" style="padding: 24px; text-align: center; color: var(--text-muted);">No blog comments found.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div style="margin-top: 20px;">
      {{ $comments->links() }}
    </div>
  </div>
</div>
@endsection
