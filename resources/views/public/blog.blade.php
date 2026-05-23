<x-public-layout title="Actualités" description="Toutes les actualités — Ligue de Fatick Taekwondo">

<div style="background: #000; min-height: 100vh; padding-top: 80px;">

    {{-- Header --}}
    <div style="background: #000; padding: 5.5rem 0 4.5rem; position: relative; overflow: hidden;">
        <div style="position: absolute; top: 0; left: 50%; transform: translateX(-50%); width: 700px; height: 300px; background: radial-gradient(ellipse 50% 100% at 50% 0%, rgba(245,158,11,0.08) 0%, transparent 70%); pointer-events: none;"></div>
        <div style="position: absolute; top: 0; left: 0; right: 0; height: 1px; background: rgba(255,255,255,0.05);"></div>
        <div style="max-width: 1280px; margin: 0 auto; padding: 0 2.5rem; position: relative; display: flex; flex-wrap: wrap; align-items: flex-end; justify-content: space-between; gap: 2rem;">
            <div>
                <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 1.5rem;">
                    <span style="font-size: 0.6rem; font-weight: 700; color: rgba(245,158,11,0.4); letter-spacing: 0.22em; font-family: 'Space Grotesk', sans-serif;">03</span>
                    <div style="width: 28px; height: 1px; background: rgba(245,158,11,0.35);"></div>
                    <span style="color: #f59e0b; font-size: 0.62rem; font-weight: 700; letter-spacing: 0.28em; text-transform: uppercase; font-family: 'Space Grotesk', sans-serif;">Blog</span>
                </div>
                <h1 style="font-size: clamp(2.5rem, 6vw, 5.5rem); font-weight: 700; color: #fff; line-height: 1; letter-spacing: -0.03em; margin: 0 0 1.25rem; font-family: 'Space Grotesk', sans-serif;">Actualités</h1>
                <p style="color: rgba(255,255,255,0.3); font-size: 0.95rem;">Les dernières nouvelles du Taekwondo sénégalais.</p>
            </div>
            {{-- Search --}}
            <form method="GET" style="display: flex; gap: 8px; align-items: center;">
                <div style="position: relative;">
                    <svg style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); width: 15px; height: 15px; color: #525252;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher…"
                           style="padding: 10px 14px 10px 36px; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #fff; font-size: 0.875rem; outline: none; width: 220px; transition: border-color 0.2s;"
                           onfocus="this.style.borderColor='#f59e0b'" onblur="this.style.borderColor='rgba(255,255,255,0.1)'">
                </div>
                @if(request('search'))
                <a href="{{ route('public.blog') }}" style="padding: 10px 14px; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: rgba(255,255,255,0.4); font-size: 0.8rem; text-decoration: none;">✕</a>
                @endif
            </form>
        </div>
    </div>

    {{-- Articles --}}
    <div style="max-width: 1280px; margin: 0 auto; padding: 3rem 2rem 6rem;">
        @if($posts->count())

        {{-- Featured --}}
        @php $featured = $posts->first() @endphp
        <a href="{{ route('public.blog-post', $featured->slug) }}"
           style="display: grid; grid-template-columns: 1fr 1fr; gap: 0; border: 1px solid rgba(255,255,255,0.08); border-radius: 10px; overflow: hidden; margin-bottom: 1px; text-decoration: none; transition: border-color 0.2s; background: #0a0a0a;"
           onmouseover="this.style.borderColor='rgba(255,255,255,0.18)'" onmouseout="this.style.borderColor='rgba(255,255,255,0.08)'">
            @if($featured->cover_url)
            <div style="aspect-ratio: 4/3; overflow: hidden; background: #111;">
                <img src="{{ $featured->cover_url }}" alt="{{ $featured->title }}"
                     style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.6s ease;"
                     onmouseover="this.style.transform='scale(1.04)'" onmouseout="this.style.transform='scale(1)'">
            </div>
            @else
            <div style="aspect-ratio: 4/3; background: #0f0f0f; display: flex; align-items: center; justify-content: center;">
                <span style="font-size: 4rem; opacity: 0.2;">📰</span>
            </div>
            @endif
            <div style="padding: 2.5rem 3rem; display: flex; flex-direction: column; gap: 1.25rem; justify-content: center;">
                <span style="display: inline-block; background: #f59e0b; color: #000; font-size: 0.6rem; font-weight: 800; letter-spacing: 0.15em; text-transform: uppercase; padding: 4px 10px; border-radius: 4px; width: fit-content;">À la une</span>
                <h2 style="font-size: 1.6rem; font-weight: 900; color: #fff; line-height: 1.2; margin: 0; transition: color 0.2s;"
                    onmouseover="this.style.color='#f59e0b'" onmouseout="this.style.color='#fff'">
                    {{ $featured->title }}
                </h2>
                <p style="color: #737373; font-size: 0.9rem; line-height: 1.7; flex: 1; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                    {{ $featured->excerpt_auto }}
                </p>
                <div style="display: flex; gap: 10px; align-items: center; font-size: 0.75rem; color: #525252;">
                    @if($featured->author)<span style="color: #737373; font-weight: 600;">{{ $featured->author->name }}</span><span>·</span>@endif
                    <span>{{ $featured->published_at?->format('d M Y') }}</span>
                </div>
                <span style="display: inline-flex; align-items: center; gap: 6px; color: #f59e0b; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.12em;">
                    Lire l'article
                    <svg style="width: 12px; height: 12px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </span>
            </div>
        </a>

        {{-- Other articles --}}
        @if($posts->count() > 1)
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1px; background: rgba(255,255,255,0.08); margin-top: 1px;">
            @foreach($posts->skip(1) as $post)
            <a href="{{ route('public.blog-post', $post->slug) }}"
               style="background: #000; display: flex; flex-direction: column; text-decoration: none; transition: background 0.2s;"
               onmouseover="this.style.background='#0a0a0a'" onmouseout="this.style.background='#000'">
                @if($post->cover_url)
                <div style="aspect-ratio: 16/9; overflow: hidden;">
                    <img src="{{ $post->cover_url }}" alt="{{ $post->title }}"
                         style="width: 100%; height: 100%; object-fit: cover; display: block; transition: transform 0.5s ease;"
                         onmouseover="this.style.transform='scale(1.04)'" onmouseout="this.style.transform='scale(1)'">
                </div>
                @else
                <div style="aspect-ratio: 16/9; background: #0f0f0f; display: flex; align-items: center; justify-content: center;">
                    <span style="font-size: 2rem; opacity: 0.15;">📰</span>
                </div>
                @endif
                <div style="padding: 1.5rem 2rem; display: flex; flex-direction: column; gap: 0.75rem; flex: 1;">
                    <div style="display: flex; gap: 8px; align-items: center; font-size: 0.7rem; color: #525252;">
                        @if($post->author)<span style="font-weight: 600; color: #737373;">{{ $post->author->name }}</span><span>·</span>@endif
                        <span>{{ $post->published_at?->format('d M Y') }}</span>
                    </div>
                    <h3 style="font-size: 1rem; font-weight: 800; color: #fff; line-height: 1.35; margin: 0; flex: 1; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; transition: color 0.2s;"
                        onmouseover="this.style.color='#f59e0b'" onmouseout="this.style.color='#fff'">
                        {{ $post->title }}
                    </h3>
                    <span style="display: inline-flex; align-items: center; gap: 5px; color: #f59e0b; font-size: 0.65rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.12em;">
                        Lire
                        <svg style="width: 10px; height: 10px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </span>
                </div>
            </a>
            @endforeach
        </div>
        @endif

        {{-- Pagination --}}
        @if($posts->hasPages())
        <div style="margin-top: 3rem; display: flex; align-items: center; justify-content: space-between; border-top: 1px solid rgba(255,255,255,0.08); padding-top: 2rem;">
            <span style="color: #525252; font-size: 0.8rem;">Page {{ $posts->currentPage() }} sur {{ $posts->lastPage() }}</span>
            <div style="display: flex; gap: 8px;">
                @if(!$posts->onFirstPage())<a href="{{ $posts->previousPageUrl() }}" style="padding: 8px 18px; background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.1); color: rgba(255,255,255,0.5); font-size: 0.8rem; border-radius: 6px; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,0.5)'">← Précédent</a>@endif
                @if($posts->hasMorePages())<a href="{{ $posts->nextPageUrl() }}" style="padding: 8px 18px; background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.1); color: rgba(255,255,255,0.5); font-size: 0.8rem; border-radius: 6px; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,0.5)'">Suivant →</a>@endif
            </div>
        </div>
        @endif

        @else
        <div style="text-align: center; padding: 6rem 0; color: #525252;">
            <div style="font-size: 3rem; margin-bottom: 1rem;">📰</div>
            <h3 style="color: #737373; font-size: 1.1rem; font-weight: 700; margin-bottom: 0.5rem;">Aucun article</h3>
            <p style="font-size: 0.875rem;">Aucun article publié pour le moment.</p>
        </div>
        @endif
    </div>
</div>

</x-public-layout>
