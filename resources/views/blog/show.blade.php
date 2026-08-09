@extends('layout')

@section('title', $blog->meta_title ?: $blog->title . ' — FirstBidIn Blog')

@section('content')
<!-- Schema.org Article JSON-LD Structured Data -->
<script type="application/ld+json">
{
  "{{ '@' }}context": "https://schema.org",
  "{{ '@' }}type": "Article",
  "headline": "{{ addslashes($blog->title) }}",
  "description": "{{ addslashes($blog->meta_description) }}",
  "datePublished": "{{ $blog->published_at->toIso8601String() }}",
  "dateModified": "{{ $blog->updated_at->toIso8601String() }}",
  "mainEntityOfPage": {
    "{{ '@' }}type": "WebPage",
    "{{ '@' }}id": "{{ url()->current() }}"
  },
  "author": {
    "{{ '@' }}type": "Organization",
    "name": "FirstBidIn Editorial Team"
  },
  "publisher": {
    "{{ '@' }}type": "Organization",
    "name": "FirstBidIn AI",
    "logo": {
      "{{ '@' }}type": "ImageObject",
      "url": "{{ asset('favicon.svg') }}"
    }
  }
}
</script>

<div style="max-width: 820px; margin: 0 auto 50px;">
  <!-- Article Header -->
  <div class="glass-panel" style="padding: 36px 40px; background: #ffffff; margin-bottom: 28px; border-color: var(--border);">
    <div style="display: flex; justify-content: space-between; align-items: center; gap: 12px; margin-bottom: 16px; flex-wrap: wrap;">
      <a href="{{ route('blog.index') }}" style="color: var(--upwork-green); font-size: 13px; font-weight: 700; text-decoration: none;">
        ← Back to all articles
      </a>
      <span class="badge" style="background: var(--upwork-tint); color: var(--upwork-tint-text); font-size: 11.5px; font-family: var(--font-mono); border: 1px solid var(--upwork-tint-border);">
        {{ $blog->category }} · ⏱️ {{ $blog->reading_time_minutes }} min read
      </span>
    </div>

    <h1 style="font-size: clamp(26px, 4vw, 38px); font-weight: 800; color: var(--text-dark); margin-bottom: 14px; line-height: 1.25; letter-spacing: -0.02em;">
      {{ $blog->title }}
    </h1>

    <div style="display: flex; justify-content: space-between; align-items: center; font-size: 13px; color: var(--text-muted); border-top: 1px solid var(--border); padding-top: 16px; flex-wrap: wrap; gap: 10px;">
      <div>Published on <strong>{{ $blog->published_at->format('F j, Y') }}</strong> · FirstBidIn Team</div>
      <div style="display: flex; align-items: center; gap: 14px;">
        <span>👁️ {{ number_format($blog->views_count) }} views</span>
        <button type="button" id="likeBtn" onclick="toggleBlogLike()" style="background: {{ ($isLiked ?? false) ? '#fef2f2' : '#ffffff' }}; border: 1px solid {{ ($isLiked ?? false) ? '#fca5a5' : 'var(--border)' }}; color: {{ ($isLiked ?? false) ? '#dc2626' : 'var(--text-dark)' }}; border-radius: 20px; padding: 4px 14px; font-size: 13px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 6px; transition: all 0.2s ease;">
          <span id="likeIcon">{{ ($isLiked ?? false) ? '❤️' : '🤍' }}</span>
          <span id="likeText">{{ ($isLiked ?? false) ? 'Liked' : 'Like' }}</span>
          <span id="likeCountBadge" style="background: {{ ($isLiked ?? false) ? '#fee2e2' : 'var(--upwork-tint)' }}; color: {{ ($isLiked ?? false) ? '#991b1b' : 'var(--upwork-tint-text)' }}; padding: 1px 7px; border-radius: 10px; font-size: 11.5px; font-family: var(--font-mono);">{{ number_format($blog->likes_count) }}</span>
        </button>
      </div>
    </div>
  </div>

  <!-- Article Main Content Box -->
  <div class="glass-panel" style="padding: 40px; background: #ffffff; margin-bottom: 32px; border-color: var(--border);">
    <div class="blog-article-content" style="font-size: 15px; color: var(--text-main); line-height: 1.75;">
      {!! $blog->content !!}
    </div>

    <!-- Social Sharing Bar -->
    <div style="border-top: 1px solid var(--border); margin-top: 36px; padding-top: 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
      <div style="display: flex; align-items: center; gap: 10px;">
        <span style="font-weight: 700; font-size: 13.5px; color: var(--text-dark);">Enjoyed this post?</span>
        <button type="button" onclick="toggleBlogLike()" style="background: var(--upwork-green); color: #ffffff; border: none; border-radius: 8px; padding: 7px 16px; font-size: 13px; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">
          ❤️ Show Support
        </button>
      </div>
      <div style="display: flex; gap: 10px;">
        <a href="https://twitter.com/intent/tweet?text={{ urlencode($blog->title) }}&url={{ urlencode(url()->current()) }}" target="_blank" class="btn btn-sm btn-ghost" style="font-size: 12.5px;">🐦 Twitter / X</a>
        <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(url()->current()) }}" target="_blank" class="btn btn-sm btn-ghost" style="font-size: 12.5px;">💼 LinkedIn</a>
      </div>
    </div>
  </div>

  <!-- Comments Section -->
  <div class="glass-panel" style="padding: 36px 40px; background: #ffffff; margin-bottom: 32px; border-color: var(--border);">
    <h3 style="font-size: 20px; font-weight: 800; color: var(--text-dark); margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
      💬 Comments <span class="badge" style="background: var(--upwork-tint); color: var(--upwork-tint-text); font-family: var(--font-mono); font-size: 12px; font-weight: 800;">{{ $comments->count() }}</span>
    </h3>

    <!-- Published Comments List -->
    @if($comments->count() > 0)
      <div style="display: flex; flex-direction: column; gap: 18px; margin-bottom: 32px;">
        @foreach($comments as $c)
          <div style="padding: 16px 20px; background: #f8fafc; border: 1px solid var(--border); border-radius: 10px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
              <div style="display: flex; align-items: center; gap: 10px;">
                <div style="width: 30px; height: 30px; background: var(--upwork-green); color: #ffffff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 13px; font-family: var(--font-mono);">
                  {{ strtoupper(substr($c->author_name, 0, 1)) }}
                </div>
                <div>
                  <span style="font-weight: 700; font-size: 14px; color: var(--text-dark);">{{ $c->author_name }}</span>
                  @if($c->user_id)
                    <span class="badge" style="background: #dcfce7; color: #166534; font-size: 10px; padding: 1px 6px; margin-left: 4px;">Verified User</span>
                  @endif
                </div>
              </div>
              <span style="font-size: 12px; color: var(--text-muted); font-family: var(--font-mono);">{{ $c->created_at->diffForHumans() }}</span>
            </div>
            <p style="font-size: 14px; color: var(--text-main); line-height: 1.55; margin: 0; white-space: pre-line;">{{ $c->comment }}</p>
          </div>
        @endforeach
      </div>
    @else
      <div style="padding: 20px; text-align: center; background: #f8fafc; border: 1px dashed var(--border); border-radius: 10px; color: var(--text-muted); font-size: 14px; margin-bottom: 28px;">
        No comments yet. Be the first to share your thoughts on this article!
      </div>
    @endif

    <!-- Leave a Comment Form -->
    <h4 style="font-size: 16px; font-weight: 800; color: var(--text-dark); margin-bottom: 14px;">Leave a Comment</h4>
    <form method="POST" action="{{ route('blog.comment', $blog->slug) }}">
      @csrf
      <!-- Hidden Anti-Bot Honeypot -->
      <div style="display:none !important; visibility:hidden !important; opacity:0 !important; position:absolute !important; left:-9999px !important;">
        <input type="text" name="comment_hp" tabindex="-1" autocomplete="off">
      </div>

      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 14px; margin-bottom: 14px;">
        <div>
          <label class="form-label" style="font-size: 12.5px;">Your Name *</label>
          <input type="text" name="author_name" value="{{ auth()->user()->name ?? old('author_name') }}" required placeholder="Jane Doe" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: 8px; font-size: 14px;">
        </div>

        <div>
          <label class="form-label" style="font-size: 12.5px;">Your Email *</label>
          <input type="email" name="author_email" value="{{ auth()->user()->email ?? old('author_email') }}" required placeholder="jane@example.com" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: 8px; font-size: 14px;">
        </div>
      </div>

      <div style="margin-bottom: 16px;">
        <label class="form-label" style="font-size: 12.5px;">Your Comment *</label>
        <textarea name="comment" rows="4" required placeholder="Share your experience or ask a question about this proposal strategy..." style="width: 100%; padding: 12px 14px; border: 1px solid var(--border); border-radius: 8px; font-size: 14px; resize: vertical;">{{ old('comment') }}</textarea>
      </div>

      <button type="submit" class="btn" style="background: var(--upwork-green); padding: 10px 24px; font-size: 14px; font-weight: 700;">
        Post Comment 💬
      </button>
    </form>
  </div>

<script>
function toggleBlogLike() {
  fetch('{{ route("blog.like", $blog->slug) }}', {
    method: 'POST',
    headers: {
      'X-CSRF-TOKEN': '{{ csrf_token() }}',
      'Accept': 'application/json'
    }
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      const btn = document.getElementById('likeBtn');
      const icon = document.getElementById('likeIcon');
      const text = document.getElementById('likeText');
      const badge = document.getElementById('likeCountBadge');

      if (badge) badge.innerText = data.likes_count;
      if (data.liked) {
        if (btn) { btn.style.background = '#fef2f2'; btn.style.borderColor = '#fca5a5'; btn.style.color = '#dc2626'; }
        if (icon) icon.innerText = '❤️';
        if (text) text.innerText = 'Liked';
        if (badge) { badge.style.background = '#fee2e2'; badge.style.color = '#991b1b'; }
      } else {
        if (btn) { btn.style.background = '#ffffff'; btn.style.borderColor = 'var(--border)'; btn.style.color = 'var(--text-dark)'; }
        if (icon) icon.innerText = '🤍';
        if (text) text.innerText = 'Like';
        if (badge) { badge.style.background = 'var(--upwork-tint)'; badge.style.color = 'var(--upwork-tint-text)'; }
      }
    }
  });
}
</script>

  <!-- Related Articles -->
  @if(($relatedBlogs ?? collect())->count() > 0)
    <div style="margin-top: 40px;">
      <h3 style="font-size: 18px; font-weight: 800; color: var(--text-dark); margin-bottom: 16px;">Read Next</h3>
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px;">
        @foreach($relatedBlogs as $rel)
          <a href="{{ route('blog.show', $rel->slug) }}" class="glass-panel" style="padding: 18px; background: #ffffff; text-decoration: none; display: block; border-color: var(--border);">
            <div style="font-size: 11px; font-family: var(--font-mono); color: var(--upwork-green); font-weight: 700; margin-bottom: 4px;">
              {{ $rel->category }}
            </div>
            <div style="font-weight: 800; font-size: 14.5px; color: var(--text-dark); line-height: 1.35; margin-bottom: 6px;">
              {{ $rel->title }}
            </div>
            <div style="font-size: 12px; color: var(--text-muted);">{{ $rel->published_at->format('M d, Y') }}</div>
          </a>
        @endforeach
      </div>
    </div>
  @endif
</div>
@endsection
