<a href="{{ route('public.live', $live) }}"
   style="display: flex; flex-direction: column; text-decoration: none; background: #0a0a0a; border: 1px solid rgba(255,255,255,0.07); border-radius: 14px; overflow: hidden; transition: border-color 0.2s, transform 0.2s;"
   onmouseover="this.style.borderColor='rgba(245,158,11,0.35)'; this.style.transform='translateY(-3px)'"
   onmouseout="this.style.borderColor='rgba(255,255,255,0.07)'; this.style.transform='translateY(0)'">

    {{-- Vignette --}}
    <div style="position: relative; aspect-ratio: 16/9; background: #000; overflow: hidden;">
        <img src="https://img.youtube.com/vi/{{ $live->youtube_video_id }}/hqdefault.jpg" alt="{{ $live->title }}"
             loading="lazy" style="width: 100%; height: 100%; object-fit: cover;">
        <div style="position: absolute; inset: 0; background: linear-gradient(to top, rgba(0,0,0,0.5) 0%, transparent 45%);"></div>

        @if($isLive)
        <span style="position: absolute; top: 12px; left: 12px; display: inline-flex; align-items: center; gap: 6px; background: #ef4444; color: #fff; font-size: 0.62rem; font-weight: 800; letter-spacing: 0.08em; text-transform: uppercase; padding: 4px 10px; border-radius: 5px; font-family: 'Space Grotesk', sans-serif;">
            <span class="live-dot-list" style="width:6px;height:6px;"></span> En direct
        </span>
        @else
        <span style="position: absolute; top: 12px; left: 12px; background: rgba(0,0,0,0.6); backdrop-filter: blur(6px); color: rgba(255,255,255,0.8); font-size: 0.62rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; padding: 4px 10px; border-radius: 5px; border: 1px solid rgba(255,255,255,0.15);">▶ Replay</span>
        @endif

        {{-- Play overlay --}}
        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%,-50%); width: 52px; height: 52px; border-radius: 50%; background: rgba(0,0,0,0.55); backdrop-filter: blur(4px); display: flex; align-items: center; justify-content: center; border: 1px solid rgba(255,255,255,0.2);">
            <svg style="width: 20px; height: 20px; color: #fff; margin-left: 2px;" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
        </div>
    </div>

    {{-- Corps --}}
    <div style="padding: 1rem 1.25rem 1.25rem;">
        <h3 style="color: #fff; font-size: 0.95rem; font-weight: 700; line-height: 1.35; margin: 0 0 6px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">{{ $live->title }}</h3>
        <div style="color: rgba(255,255,255,0.35); font-size: 0.78rem; display: flex; align-items: center; gap: 6px;">
            <svg style="width: 12px; height: 12px; color: #f59e0b; flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 9v7.5"/></svg>
            <span style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $live->event?->name ?? 'Ligue de Fatick' }}</span>
        </div>
    </div>
</a>
