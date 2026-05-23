<x-public-layout
    :title="$post->title"
    :description="$post->excerpt ?? $post->excerpt_auto"
    :image="$post->cover_url"
    type="article"
>

<div style="background: #000; min-height: 100vh; padding-top: 80px;">

    {{-- Cover --}}
    @if($post->cover_url)
    <div style="position: relative; height: 420px; overflow: hidden;">
        <img src="{{ $post->cover_url }}" alt="{{ $post->title }}" style="width: 100%; height: 100%; object-fit: cover; display: block;">
        <div style="position: absolute; inset: 0; background: linear-gradient(to bottom, rgba(0,0,0,0.2) 0%, rgba(0,0,0,0.85) 100%);"></div>
    </div>
    @else
    <div style="height: 80px; background: #000;"></div>
    @endif

    {{-- Article --}}
    <div style="max-width: 780px; margin: 0 auto; padding: 3rem 2rem 6rem;">

        <a href="{{ route('public.blog') }}"
           style="display: inline-flex; align-items: center; gap: 8px; color: #525252; font-size: 0.8rem; text-decoration: none; margin-bottom: 2.5rem; transition: color 0.2s;"
           onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#525252'">
            <svg style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            Retour aux actualités
        </a>

        {{-- Meta --}}
        <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 12px; margin-bottom: 1.5rem;">
            @if($post->author)
            <div style="display: flex; align-items: center; gap: 8px;">
                <div style="width: 28px; height: 28px; background: #f59e0b; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #000; font-weight: 800; font-size: 0.75rem;">{{ strtoupper(substr($post->author->name, 0, 1)) }}</div>
                <span style="color: #fff; font-size: 0.875rem; font-weight: 600;">{{ $post->author->name }}</span>
            </div>
            <span style="color: #333;">·</span>
            @endif
            <span style="color: #525252; font-size: 0.875rem;">{{ $post->published_at?->format('d M Y') }}</span>
            @if($post->views_count)
            <span style="color: #333;">·</span>
            <span style="color: #525252; font-size: 0.875rem;">{{ $post->views_count }} vues</span>
            @endif
        </div>

        {{-- Title --}}
        <h1 style="font-size: clamp(1.75rem, 4vw, 3rem); font-weight: 900; color: #fff; line-height: 1.1; letter-spacing: -0.02em; margin: 0 0 2rem;">
            {{ $post->title }}
        </h1>

        {{-- Excerpt --}}
        @if($post->excerpt)
        <p style="font-size: 1.1rem; color: #737373; line-height: 1.75; border-left: 3px solid #f59e0b; padding-left: 1.25rem; margin-bottom: 2.5rem; font-style: italic;">
            {{ $post->excerpt }}
        </p>
        @endif

        {{-- Separator --}}
        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 2.5rem;">
            <div style="width: 32px; height: 2px; background: #f59e0b;"></div>
            <div style="height: 1px; flex: 1; background: rgba(255,255,255,0.07);"></div>
        </div>

        {{-- Content --}}
        <div style="color: #a3a3a3; font-size: 1rem; line-height: 1.85;">
            {!! nl2br(e($post->content)) !!}
        </div>

        {{-- Bottom --}}
        <div style="margin-top: 4rem; padding-top: 2rem; border-top: 1px solid rgba(255,255,255,0.08); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
            <a href="{{ route('public.blog') }}"
               style="display: inline-flex; align-items: center; gap: 8px; color: rgba(255,255,255,0.4); font-size: 0.8rem; text-decoration: none; transition: color 0.2s;"
               onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,0.4)'">
                ← Tous les articles
            </a>
        </div>
    </div>

    {{-- Related --}}
    @if($related->count())
    <div style="background: #0a0a0a; border-top: 1px solid rgba(255,255,255,0.08); padding: 4rem 0 5rem;">
        <div style="max-width: 1280px; margin: 0 auto; padding: 0 2rem;">
            <div style="display: flex; align-items: center; gap: 14px; margin-bottom: 2.5rem;">
                <div style="width: 24px; height: 2px; background: #f59e0b;"></div>
                <h2 style="font-size: 1.25rem; font-weight: 900; color: #fff; margin: 0;">À lire aussi</h2>
            </div>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1px; background: rgba(255,255,255,0.08);">
                @foreach($related as $relPost)
                <a href="{{ route('public.blog-post', $relPost->slug) }}"
                   style="background: #0a0a0a; display: flex; flex-direction: column; text-decoration: none; transition: background 0.2s;"
                   onmouseover="this.style.background='#111'" onmouseout="this.style.background='#0a0a0a'">
                    @if($relPost->cover_url)
                    <div style="aspect-ratio: 16/9; overflow: hidden;">
                        <img src="{{ $relPost->cover_url }}" alt="{{ $relPost->title }}" style="width: 100%; height: 100%; object-fit: cover; display: block; transition: transform 0.5s;" onmouseover="this.style.transform='scale(1.04)'" onmouseout="this.style.transform='scale(1)'">
                    </div>
                    @endif
                    <div style="padding: 1.5rem 2rem;">
                        <div style="color: #525252; font-size: 0.7rem; margin-bottom: 0.5rem;">{{ $relPost->published_at?->format('d M Y') }}</div>
                        <h3 style="font-size: 0.95rem; font-weight: 700; color: #fff; line-height: 1.35; margin: 0; transition: color 0.2s; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;"
                            onmouseover="this.style.color='#f59e0b'" onmouseout="this.style.color='#fff'">
                            {{ $relPost->title }}
                        </h3>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </div>
    @endif

</div>

</x-public-layout>
