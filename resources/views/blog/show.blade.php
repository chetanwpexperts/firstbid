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
      <div>👁️ {{ number_format($blog->views_count) }} views</div>
    </div>
  </div>

  <!-- Article Main Content Box -->
  <div class="glass-panel" style="padding: 40px; background: #ffffff; margin-bottom: 32px; border-color: var(--border);">
    <div class="blog-article-content" style="font-size: 15px; color: var(--text-main); line-height: 1.75;">
      {!! $blog->content !!}
    </div>

    <!-- Social Sharing Bar -->
    <div style="border-top: 1px solid var(--border); margin-top: 36px; padding-top: 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
      <span style="font-weight: 700; font-size: 13.5px; color: var(--text-dark);">Share this article:</span>
      <div style="display: flex; gap: 10px;">
        <a href="https://twitter.com/intent/tweet?text={{ urlencode($blog->title) }}&url={{ urlencode(url()->current()) }}" target="_blank" class="btn btn-sm btn-ghost" style="font-size: 12.5px;">🐦 Twitter / X</a>
        <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(url()->current()) }}" target="_blank" class="btn btn-sm btn-ghost" style="font-size: 12.5px;">💼 LinkedIn</a>
      </div>
    </div>
  </div>

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
