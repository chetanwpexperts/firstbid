@extends('layout')

@section('title', 'Upwork Proposal Strategy & AI Bidding Blog — FirstBidIn')

@section('content')
<div style="max-width: 1080px; margin: 0 auto 50px;">
  <!-- Blog Header Banner -->
  <div class="glass-panel" style="padding: 40px; background: #ffffff; text-align: center; margin-bottom: 32px; border-color: var(--border);">
    <div style="display: inline-flex; align-items: center; gap: 8px; background: var(--upwork-tint); border: 1px solid var(--upwork-tint-border); color: var(--upwork-tint-text); font-family: var(--font-mono); font-size: 12px; padding: 5px 14px; border-radius: 20px; font-weight: 700; text-transform: uppercase; margin-bottom: 16px;">
      📚 FREELANCE GROWTH & PROPOSAL STRATEGY
    </div>

    <h1 style="font-size: clamp(28px, 4vw, 42px); font-weight: 800; color: var(--text-dark); margin-bottom: 12px; letter-spacing: -0.02em;">
      FirstBidIn Growth & Bidding Blog
    </h1>

    <p style="font-size: 16px; color: var(--text-muted); max-width: 640px; margin: 0 auto 24px; line-height: 1.6;">
      Actionable strategies, opener hook formulas, and AI productivity guides for Upwork freelancers and agency owners.
    </p>

    <!-- Search Box -->
    <form method="GET" action="{{ route('blog.index') }}" style="max-width: 480px; margin: 0 auto; display: flex; gap: 10px;">
      <input type="text" name="search" value="{{ request('search') }}" placeholder="Search articles (e.g. Hooks, Budget, Cover Letter)..." style="flex: 1; padding: 11px 16px; border: 1px solid var(--border); border-radius: 10px; font-size: 14px;">
      <button class="btn" type="submit" style="background: var(--upwork-green); padding: 11px 22px; font-size: 14px; font-weight: 700;">Search</button>
    </form>
  </div>

  <!-- Articles Grid -->
  @if($blogs->count() > 0)
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 24px;">
      @foreach($blogs as $b)
        <div class="glass-panel" style="padding: 24px; background: #ffffff; display: flex; flex-direction: column; justify-content: space-between; border-color: var(--border); transition: transform 0.2s ease;">
          <div>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
              <span class="badge" style="background: var(--upwork-tint); color: var(--upwork-tint-text); font-size: 11px; font-family: var(--font-mono); border: 1px solid var(--upwork-tint-border);">
                {{ $b->category }}
              </span>
              <span style="font-size: 12px; color: var(--text-muted); font-family: var(--font-mono);">
                ⏱️ {{ $b->reading_time_minutes }} min read
              </span>
            </div>

            <h2 style="font-size: 18px; font-weight: 800; color: var(--text-dark); margin-bottom: 10px; line-height: 1.35;">
              <a href="{{ route('blog.show', $b->slug) }}" style="color: var(--text-dark); text-decoration: none;">
                {{ $b->title }}
              </a>
            </h2>

            <p style="font-size: 13.5px; color: var(--text-muted); line-height: 1.55; margin-bottom: 20px;">
              {{ Str::limit($b->meta_description, 130) }}
            </p>
          </div>

          <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--border); padding-top: 14px; font-size: 12.5px; color: var(--text-muted);">
            <div style="display: flex; align-items: center; gap: 10px;">
              <span>{{ $b->published_at->format('M d, Y') }}</span>
              <span>•</span>
              <span style="font-family: var(--font-mono); font-size: 11.5px; color: var(--text-muted);">❤️ {{ number_format($b->likes_count) }}</span>
            </div>
            <a href="{{ route('blog.show', $b->slug) }}" style="color: var(--upwork-green); font-weight: 700; text-decoration: none;">
              Read Article ↗
            </a>
          </div>
        </div>
      @endforeach
    </div>

    <div style="margin-top: 32px;">
      {{ $blogs->appends(request()->query())->links('partials.pagination', ['itemLabel' => 'articles']) }}
    </div>
  @else
    <div class="glass-panel" style="padding: 40px; text-align: center; background: #ffffff;">
      <div style="font-size: 32px; margin-bottom: 10px;">📰</div>
      <h3 style="font-size: 18px; font-weight: 800; color: var(--text-dark); margin-bottom: 6px;">No blog articles found</h3>
      <p style="font-size: 14px; color: var(--text-muted); margin-bottom: 16px;">Check back soon as our automated AI system publishes daily proposal strategy guides!</p>
      <a href="{{ route('blog.index') }}" class="btn btn-sm" style="background: var(--upwork-green);">View All Articles</a>
    </div>
  @endif
</div>
@endsection
