<x-public-layout title="Galerie" description="Galerie photos — Ligue de Fatick Taekwondo">

<div style="background: #000; min-height: 100vh; padding-top: 80px;">

    {{-- Header --}}
    <div style="background: #000; padding: 5.5rem 0 4.5rem; position: relative; overflow: hidden;">
        <div style="position: absolute; top: 0; left: 50%; transform: translateX(-50%); width: 700px; height: 300px; background: radial-gradient(ellipse 50% 100% at 50% 0%, rgba(245,158,11,0.08) 0%, transparent 70%); pointer-events: none;"></div>
        <div style="position: absolute; top: 0; left: 0; right: 0; height: 1px; background: rgba(255,255,255,0.05);"></div>
        <div style="max-width: 1280px; margin: 0 auto; padding: 0 2.5rem; position: relative;">
            <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 1.5rem;">
                <span style="font-size: 0.6rem; font-weight: 700; color: rgba(245,158,11,0.4); letter-spacing: 0.22em; font-family: 'Space Grotesk', sans-serif;">02</span>
                <div style="width: 28px; height: 1px; background: rgba(245,158,11,0.35);"></div>
                <span style="color: #f59e0b; font-size: 0.62rem; font-weight: 700; letter-spacing: 0.28em; text-transform: uppercase; font-family: 'Space Grotesk', sans-serif;">Médias</span>
            </div>
            <h1 style="font-size: clamp(2.5rem, 6vw, 5.5rem); font-weight: 700; color: #fff; line-height: 1; letter-spacing: -0.03em; margin: 0 0 1.25rem; font-family: 'Space Grotesk', sans-serif;">Galerie photos</h1>
            <p style="color: rgba(255,255,255,0.3); font-size: 0.95rem;">Revivez les plus beaux moments de nos compétitions.</p>
        </div>
    </div>

    {{-- Event filter --}}
    <div style="max-width: 1280px; margin: 0 auto; padding: 1.75rem 2.5rem;">
        <div style="display: flex; flex-wrap: wrap; gap: 8px; align-items: center;">
            <span style="color: #525252; font-size: 0.75rem; font-weight: 600; margin-right: 6px; text-transform: uppercase; letter-spacing: 0.1em;">Filtrer :</span>
            <a href="{{ route('public.gallery') }}"
               style="padding: 6px 14px; border-radius: 99px; font-size: 0.75rem; font-weight: 600; text-decoration: none; transition: all 0.15s;
                      {{ !request('event_id') ? 'background: #f59e0b; color: #000; border: 1px solid #f59e0b;' : 'background: transparent; color: rgba(255,255,255,0.4); border: 1px solid rgba(255,255,255,0.1);' }}"
               @if(!request('event_id')) @else
               onmouseover="this.style.color='#fff'; this.style.borderColor='rgba(255,255,255,0.3)'"
               onmouseout="this.style.color='rgba(255,255,255,0.4)'; this.style.borderColor='rgba(255,255,255,0.1)'"
               @endif>
                Tous
            </a>
            @foreach($events as $event)
            <a href="{{ route('public.gallery') }}?event_id={{ $event->id }}"
               style="padding: 6px 14px; border-radius: 99px; font-size: 0.75rem; font-weight: 600; text-decoration: none; transition: all 0.15s;
                      {{ request('event_id') == $event->id ? 'background: #f59e0b; color: #000; border: 1px solid #f59e0b;' : 'background: transparent; color: rgba(255,255,255,0.4); border: 1px solid rgba(255,255,255,0.1);' }}"
               @if(request('event_id') != $event->id)
               onmouseover="this.style.color='#fff'; this.style.borderColor='rgba(255,255,255,0.3)'"
               onmouseout="this.style.color='rgba(255,255,255,0.4)'; this.style.borderColor='rgba(255,255,255,0.1)'"
               @endif>
                {{ $event->name }}
            </a>
            @endforeach
        </div>
    </div>

    {{-- Photo grid --}}
    <div style="max-width: 1280px; margin: 0 auto; padding: 0 2.5rem 6rem;">
        @if($photos->count())

        <div id="gallery-grid" style="columns: 2; column-gap: 3px; margin-bottom: 3rem;">
            @foreach($photos as $i => $photo)
            <div style="break-inside: avoid; margin-bottom: 3px; cursor: pointer;"
                 onclick="openLightbox({{ $i }})">
                <img src="{{ $photo->url }}"
                     alt="{{ $photo->caption ?? 'Photo' }}"
                     loading="lazy"
                     style="width: 100%; display: block; transition: filter 0.3s ease; border-radius: 0;"
                     onmouseover="this.style.filter='brightness(1.12)'" onmouseout="this.style.filter='brightness(1)'">
            </div>
            @endforeach
        </div>

        {{-- CSS columns responsive --}}
        <style>
            @media (min-width: 640px)  { #gallery-grid { columns: 3; } }
            @media (min-width: 1024px) { #gallery-grid { columns: 4; } }
        </style>

        {{-- Pagination --}}
        @if($photos->hasPages())
        <div style="display: flex; align-items: center; justify-content: space-between; border-top: 1px solid rgba(255,255,255,0.08); padding-top: 2rem;">
            <span style="color: #525252; font-size: 0.8rem;">Page {{ $photos->currentPage() }} sur {{ $photos->lastPage() }}</span>
            <div style="display: flex; gap: 8px;">
                @if(!$photos->onFirstPage())
                <a href="{{ $photos->previousPageUrl() }}" style="padding: 8px 18px; background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.1); color: rgba(255,255,255,0.5); font-size: 0.8rem; border-radius: 6px; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,0.5)'">← Précédent</a>
                @endif
                @if($photos->hasMorePages())
                <a href="{{ $photos->nextPageUrl() }}" style="padding: 8px 18px; background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.1); color: rgba(255,255,255,0.5); font-size: 0.8rem; border-radius: 6px; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,0.5)'">Suivant →</a>
                @endif
            </div>
        </div>
        @endif

        @else
        <div style="text-align: center; padding: 6rem 0; color: #525252;">
            <div style="font-size: 3rem; margin-bottom: 1rem;">📷</div>
            <h3 style="color: #737373; font-size: 1.1rem; font-weight: 700; margin-bottom: 0.5rem;">Aucune photo</h3>
            <p style="font-size: 0.875rem;">La galerie est vide pour le moment.</p>
        </div>
        @endif
    </div>

    {{-- Lightbox --}}
    <div id="lightbox" style="display: none; position: fixed; inset: 0; z-index: 100; background: rgba(0,0,0,0.97); align-items: center; justify-content: center; padding: 2rem;"
         onclick="closeLightbox()">
        <button onclick="moveLightbox(-1); event.stopPropagation()"
                style="position: absolute; left: 1.5rem; top: 50%; transform: translateY(-50%); background: rgba(255,255,255,0.08); border: none; color: #fff; width: 48px; height: 48px; border-radius: 50%; cursor: pointer; font-size: 1.2rem; transition: background 0.2s; z-index: 1;"
                onmouseover="this.style.background='rgba(255,255,255,0.15)'" onmouseout="this.style.background='rgba(255,255,255,0.08)'">‹</button>
        <button onclick="moveLightbox(1); event.stopPropagation()"
                style="position: absolute; right: 1.5rem; top: 50%; transform: translateY(-50%); background: rgba(255,255,255,0.08); border: none; color: #fff; width: 48px; height: 48px; border-radius: 50%; cursor: pointer; font-size: 1.2rem; transition: background 0.2s; z-index: 1;"
                onmouseover="this.style.background='rgba(255,255,255,0.15)'" onmouseout="this.style.background='rgba(255,255,255,0.08)'">›</button>
        <button onclick="closeLightbox()"
                style="position: absolute; top: 1.5rem; right: 1.5rem; background: rgba(255,255,255,0.08); border: none; color: #fff; width: 40px; height: 40px; border-radius: 50%; cursor: pointer; font-size: 1rem;">✕</button>
        <img id="lightbox-img" src="" alt="" onclick="event.stopPropagation()"
             style="max-width: 100%; max-height: 88vh; object-fit: contain; border-radius: 6px; box-shadow: 0 40px 80px rgba(0,0,0,0.8);">
        <div id="lightbox-counter" style="position: absolute; bottom: 1.5rem; left: 50%; transform: translateX(-50%); color: rgba(255,255,255,0.3); font-size: 0.75rem;"></div>
    </div>
</div>

<script>
var photos = @json($photos->map(fn($p) => ['url' => $p->url, 'caption' => $p->caption])->values());
var current = 0;

function openLightbox(i) {
    current = i;
    updateLightbox();
    var lb = document.getElementById('lightbox');
    lb.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}
function closeLightbox() {
    document.getElementById('lightbox').style.display = 'none';
    document.body.style.overflow = '';
}
function moveLightbox(dir) {
    current = (current + dir + photos.length) % photos.length;
    updateLightbox();
}
function updateLightbox() {
    document.getElementById('lightbox-img').src = photos[current].url;
    document.getElementById('lightbox-counter').textContent = (current + 1) + ' / ' + photos.length;
}
document.addEventListener('keydown', function(e) {
    if (document.getElementById('lightbox').style.display === 'flex') {
        if (e.key === 'ArrowLeft') moveLightbox(-1);
        if (e.key === 'ArrowRight') moveLightbox(1);
        if (e.key === 'Escape') closeLightbox();
    }
});
</script>

</x-public-layout>
