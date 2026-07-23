@if ($paginator->hasPages())
  <div class="pagination-wrapper">
    <div class="pager-capsule" role="navigation" aria-label="Pagination">
      <!-- Progress Bar Track -->
      @php
        $currentPage = $paginator->currentPage();
        $lastPage = method_exists($paginator, 'lastPage') ? max(1, $paginator->lastPage()) : 1;
        $progressPct = round(($currentPage / $lastPage) * 100);

        $prettyUrl = function($page) {
            return $page <= 1 ? route('dashboard') : route('dashboard.page', ['page' => $page]);
        };
      @endphp
      <div class="pager-progress-track">
        <div class="pager-progress-fill" style="width: {{ $progressPct }}%;"></div>
      </div>

      <div class="pager-inner">
        <!-- Prev Button -->
        @if ($paginator->onFirstPage())
          <span class="pager-btn disabled" aria-disabled="true" title="Previous Page">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18l-6-6 6-6"/></svg>
            <span>Prev</span>
          </span>
        @else
          <a href="{{ $prettyUrl($currentPage - 1) }}" class="pager-btn" rel="prev" title="Previous Page">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18l-6-6 6-6"/></svg>
            <span>Prev</span>
          </a>
        @endif

        <!-- Page Numbers Container -->
        <div class="pager-numbers">
          @foreach ($elements as $element)
            @if (is_string($element))
              <span class="pager-ellipsis" aria-hidden="true">{{ $element }}</span>
            @endif

            @if (is_array($element))
              @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                  <span class="pager-num active" aria-current="page">{{ $page }}</span>
                @else
                  <a href="{{ $prettyUrl($page) }}" class="pager-num">{{ $page }}</a>
                @endif
              @endforeach
            @endif
          @endforeach
        </div>

        <!-- Next Button -->
        @if ($paginator->hasMorePages())
          <a href="{{ $prettyUrl($currentPage + 1) }}" class="pager-btn" rel="next" title="Next Page">
            <span>Next</span>
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18l6-6-6-6"/></svg>
          </a>
        @else
          <span class="pager-btn disabled" aria-disabled="true" title="Next Page">
            <span>Next</span>
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18l6-6-6-6"/></svg>
          </span>
        @endif
      </div>

      <!-- Footer Info Meta -->
      <div class="pager-meta">
        <span>Page <strong>{{ $currentPage }}</strong> of <strong>{{ $lastPage }}</strong></span>
        @if(method_exists($paginator, 'total') && $paginator->total() > 0)
          <span class="pager-meta-dot">•</span>
          <span>Showing <strong>{{ $paginator->firstItem() }}–{{ $paginator->lastItem() }}</strong> of <strong>{{ $paginator->total() }}</strong> jobs</span>
        @endif
      </div>
    </div>
  </div>
@endif
