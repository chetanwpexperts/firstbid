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

    <!-- Header Actions -->
    <div style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
      <button type="button" class="btn btn-ghost" onclick="toggleCustomBlogForm()" style="border: 1px solid var(--border); padding: 10px 18px; font-size: 14px; font-weight: 700; background: #ffffff;">
        ✍️ Write Custom Blog
      </button>

      <!-- Trigger AI Blog Generator Button -->
      <form method="POST" action="{{ route('admin.blogs.generate') }}" style="margin: 0;">
        @csrf
        <button class="btn" type="submit" style="background: var(--upwork-green); padding: 11px 22px; font-size: 14px; font-weight: 700; display: inline-flex; align-items: center; gap: 8px;">
          ⚡ Generate New AI Blog Article
        </button>
      </form>
    </div>
  </div>

  <!-- Custom Blog Article Creation Form Card (Hidden by default) -->
  <div id="customBlogFormCard" class="glass-panel" style="display: none; padding: 28px; background: #ffffff; margin-bottom: 32px; border-color: var(--upwork-green);">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px;">
      <h3 style="font-size: 18px; font-weight: 800; color: var(--text-dark); margin: 0;">✍️ Publish New Custom Blog Article</h3>
      <button type="button" onclick="toggleCustomBlogForm()" style="background: none; border: none; font-size: 18px; cursor: pointer; color: var(--text-muted);">✕</button>
    </div>

    <form method="POST" action="{{ route('admin.blogs.store') }}">
      @csrf
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 16px; margin-bottom: 16px;">
        <div>
          <label class="form-label" style="font-size: 13px; font-weight: 700;">Article Title *</label>
          <input type="text" name="title" required placeholder="e.g., 5 Upwork Proposal Hacks to Double Response Rates" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: 8px; font-size: 14px;">
        </div>

        <div>
          <label class="form-label" style="font-size: 13px; font-weight: 700;">Category *</label>
          <select name="category" required style="width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: 8px; font-size: 14px; background: #ffffff;">
            <option value="Proposal Strategy">Proposal Strategy</option>
            <option value="Upwork Tips">Upwork Tips</option>
            <option value="Client Communication">Client Communication</option>
            <option value="AI Freelancing">AI Freelancing</option>
            <option value="Pricing & Rates">Pricing & Rates</option>
          </select>
        </div>
      </div>

      <div style="margin-bottom: 16px;">
        <label class="form-label" style="font-size: 13px; font-weight: 700;">SEO Meta Description * (max 300 chars)</label>
        <input type="text" name="meta_description" required placeholder="Short summary for Google search results..." style="width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: 8px; font-size: 14px;">
      </div>

      <div style="margin-bottom: 20px;">
        <label class="form-label" style="font-size: 13px; font-weight: 700;">Article Content * (Supports HTML & Formatting)</label>
        <textarea name="content" rows="12" required placeholder="Write your full blog post here. You can use HTML tags like <h2>, <p>, <ul>, <li>, <strong>, <blockquote>..." style="width: 100%; padding: 14px; border: 1px solid var(--border); border-radius: 8px; font-size: 14px; font-family: inherit; line-height: 1.6; resize: vertical;"></textarea>
      </div>

      <div style="display: flex; gap: 12px; justify-content: flex-end;">
        <button type="button" class="btn btn-ghost" onclick="toggleCustomBlogForm()">Cancel</button>
        <button type="submit" class="btn" style="background: var(--upwork-green); padding: 10px 24px; font-weight: 700;">
          🚀 Publish Article Now
        </button>
      </div>
    </form>
  </div>

  <script>
  function toggleCustomBlogForm() {
    const card = document.getElementById('customBlogFormCard');
    card.style.display = card.style.display === 'none' ? 'block' : 'none';
  }
  </script>

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
