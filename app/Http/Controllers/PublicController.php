<?php

namespace App\Http\Controllers;

use App\Models\Athlete;
use App\Models\BlogPost;
use App\Models\Event;
use App\Models\GalleryPhoto;
use App\Models\Draw;
use App\Models\LiveSession;
use App\Models\Ranking;
use App\Models\User;
use App\Services\WeightCategoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PublicController extends Controller
{
    public function __construct(private WeightCategoryService $categories) {}

    public function home(): View
    {
        $upcomingEvents = Event::whereIn('status', ['upcoming', 'open'])
            ->orderBy('start_date')
            ->limit(4)
            ->get();

        $nextEvent = $upcomingEvents->first();

        // Hero background: first photo linked to next event, else any recent photo
        $heroPhoto = null;
        if ($nextEvent) {
            $heroPhoto = GalleryPhoto::where('event_id', $nextEvent->id)->latest()->first();
        }
        if (!$heroPhoto) {
            $heroPhoto = GalleryPhoto::latest()->first();
        }

        $latestPosts = BlogPost::where('status', 'published')
            ->with('author:id,name')
            ->latest('published_at')
            ->limit(3)
            ->get();

        $recentPhotos = GalleryPhoto::with('event:id,name')
            ->latest()
            ->limit(9)
            ->get();

        $stats = [
            'athletes' => Athlete::where('registration_status', 'validated')->count(),
            'events'   => Event::where('status', 'finished')->count(),
            'coaches'  => User::whereHas('roles', fn($q) => $q->where('name', 'coach'))->count(),
            'clubs'    => Athlete::where('registration_status', 'validated')->distinct('club')->count('club'),
        ];

        return view('public.home', compact('upcomingEvents', 'nextEvent', 'heroPhoto', 'latestPosts', 'recentPhotos', 'stats'));
    }

    public function events(Request $request): View
    {
        // Événements actifs d'abord, archives (terminés/annulés) ensuite ; récents en tête.
        $q = Event::query()
            ->orderByRaw("CASE WHEN status IN ('finished', 'cancelled') THEN 1 ELSE 0 END")
            ->latest('start_date');

        if ($request->type) {
            $q->where('type', $request->type);
        }
        if ($request->status) {
            $q->where('status', $request->status);
        }
        if ($request->search) {
            $q->where(function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%')
                      ->orWhere('location', 'like', '%' . $request->search . '%');
            });
        }

        $events = $q->paginate(12);

        return view('public.events', compact('events'));
    }

    public function eventDetail(string $slug): View
    {
        $event = Event::where('slug', $slug)->firstOrFail();

        $categories = Athlete::where('event_id', $event->id)
            ->where('registration_status', 'validated')
            ->selectRaw('age_category, gender, weight_category, COUNT(*) as count')
            ->groupBy('age_category', 'gender', 'weight_category')
            ->orderBy('age_category')
            ->get();

        $rankings = Ranking::where('event_id', $event->id)
            ->with('athlete:id,first_name,last_name,club,gender')
            ->orderBy('position')
            ->get();

        $photos = GalleryPhoto::where('event_id', $event->id)->latest()->limit(12)->get();

        // Direct en cours pour cet événement (pour l'encart « EN DIRECT »)
        $liveSession = LiveSession::where('event_id', $event->id)->live()->latest()->first();

        return view('public.event-detail', compact('event', 'categories', 'rankings', 'photos', 'liveSession'));
    }

    public function live(LiveSession $liveSession): View
    {
        // Consultable seulement si en cours (direct) ou terminé (replay)
        abort_if($liveSession->status === 'scheduled', 404);

        $liveSession->load('event');

        return view('public.live', compact('liveSession'));
    }

    public function gallery(Request $request): View
    {
        $q = GalleryPhoto::with('event:id,name')->latest();

        if ($request->event_id) {
            $q->where('event_id', $request->event_id);
        }

        $photos = $q->paginate(24);
        $events = Event::orderByDesc('start_date')->get(['id', 'name']);

        return view('public.gallery', compact('photos', 'events'));
    }

    public function blog(Request $request): View
    {
        $q = BlogPost::where('status', 'published')
            ->with('author:id,name')
            ->latest('published_at');

        if ($request->search) {
            $q->where('title', 'like', '%' . $request->search . '%');
        }

        $posts = $q->paginate(9);

        return view('public.blog', compact('posts'));
    }

    public function blogPost(string $slug): View
    {
        $post = BlogPost::where('slug', $slug)
            ->where('status', 'published')
            ->with('author:id,name')
            ->firstOrFail();

        $post->increment('views_count');

        $related = BlogPost::where('status', 'published')
            ->where('id', '!=', $post->id)
            ->latest('published_at')
            ->limit(3)
            ->get();

        return view('public.blog-post', compact('post', 'related'));
    }

    public function verify(Request $request): View
    {
        $athlete = null;

        if ($request->filled('q')) {
            $q = trim($request->q);
            // Escape LIKE wildcards to prevent pattern injection
            $qLike = str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $q);
            $athlete = Athlete::with(['event:id,name', 'coach:id,name'])
                ->where(function ($query) use ($q, $qLike) {
                    $query->where('license_number', $q)
                          ->orWhere('first_name', 'like', '%' . $qLike . '%')
                          ->orWhere('last_name', 'like', '%' . $qLike . '%')
                          ->orWhereRaw("TRIM(CONCAT(first_name, ' ', last_name)) LIKE ?", ['%' . $qLike . '%']);
                })
                ->first();
        }

        return view('public.verify', compact('athlete'));
    }

    public function athleteList(string $slug): View
    {
        $event = Event::where('slug', $slug)->firstOrFail();

        $athletes = Athlete::where('event_id', $event->id)
            ->where('registration_status', 'validated')
            ->with('coach:id,name')
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        $ageOrder = ['Minime' => 1, 'Cadet' => 2, 'Junior' => 3, 'Senior' => 4];

        $grouped = $athletes
            ->sortBy([
                [fn($a) => $ageOrder[$a->age_category] ?? 99, 'asc'],
                ['gender', 'asc'],
                [fn($a) => (int) preg_replace('/[^0-9]/', '', $a->weight_category), 'asc'],
                ['last_name', 'asc'],
                ['first_name', 'asc'],
            ])
            ->groupBy(fn($a) => $a->age_category . '||' . $a->gender . '||' . $a->weight_category);

        return view('public.athlete-list', compact('event', 'grouped'));
    }

    public function athleteListCsv(string $slug)
    {
        $event = Event::where('slug', $slug)->firstOrFail();

        $ageOrder = ['Minime' => 1, 'Cadet' => 2, 'Junior' => 3, 'Senior' => 4];

        $athletes = Athlete::where('event_id', $event->id)
            ->where('registration_status', 'validated')
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get()
            ->sortBy([
                [fn($a) => $ageOrder[$a->age_category] ?? 99, 'asc'],
                ['gender', 'asc'],
                [fn($a) => (int) preg_replace('/[^0-9]/', '', $a->weight_category), 'asc'],
                ['last_name', 'asc'],
                ['first_name', 'asc'],
            ])
            ->values();

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="athletes-' . $event->slug . '.csv"',
        ];

        $callback = function () use ($athletes) {
            $out = fopen('php://output', 'w');
            fprintf($out, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($out, ['Rang', 'Catégorie âge', 'Genre', 'Catégorie poids', 'Prénom', 'Nom', 'Club', 'N° Licence'], ';');
            $rank = 1;
            $prevKey = null;
            foreach ($athletes as $a) {
                $key = $a->age_category . '||' . $a->gender . '||' . $a->weight_category;
                if ($key !== $prevKey) { $rank = 1; $prevKey = $key; }
                fputcsv($out, [
                    $rank++,
                    $a->age_category,
                    $a->gender === 'M' ? 'Masculin' : 'Féminin',
                    $a->weight_category,
                    $a->first_name,
                    $a->last_name,
                    $a->club ?? '—',
                    $a->license_number ?? '',
                ], ';');
            }
            fclose($out);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function draws(string $slug): View
    {
        $event = Event::where('slug', $slug)->firstOrFail();

        // Draws not yet released — show a "coming soon" page instead of 404
        if (! in_array($event->status, ['closed', 'ongoing', 'finished'], true)) {
            return view('public.draws-unavailable', compact('event'));
        }

        $ageOrder = ['Minime' => 1, 'Cadet' => 2, 'Junior' => 3, 'Senior' => 4];

        $draws = Draw::where('event_id', $event->id)
            ->get()
            ->sortBy([
                [fn($d) => $ageOrder[$d->age_category] ?? 99, 'asc'],
                ['gender', 'asc'],
                [fn($d) => (int) preg_replace('/[^0-9]/', '', $d->weight_category), 'asc'],
            ]);

        $isAdmin = Auth::check() && Auth::user()?->isTechnical();

        return view('public.draws', compact('event', 'draws', 'isAdmin'));
    }

    public function rankings(Request $request): View
    {
        $season  = (int) ($request->season ?? now()->year);
        $seasons = Ranking::distinct('season')->orderByDesc('season')->pluck('season');

        // Aggregate per athlete per category across all events in the season
        $byCategory = Ranking::where('season', $season)
            ->with(['athlete:id,first_name,last_name,club,gender', 'event:id,name,slug'])
            ->get()
            ->groupBy('category')
            ->map(function ($catRankings) {
                return $catRankings
                    ->groupBy('athlete_id')
                    ->map(function ($rows) {
                        $first = $rows->first();
                        return [
                            'athlete_id'    => $first->athlete_id,
                            'athlete'       => $first->athlete,
                            'total_points'  => $rows->sum('points'),
                            'total_wins'    => $rows->sum('wins'),
                            'events_count'  => $rows->count(),
                            'best_position' => $rows->whereNotNull('position')->min('position'),
                            'events'        => $rows->map(fn($r) => [
                                'name'     => $r->event?->name,
                                'slug'     => $r->event?->slug,
                                'position' => $r->position,
                                'points'   => $r->points,
                            ])->values(),
                        ];
                    })
                    ->sortByDesc('total_points')
                    ->values();
            })
            ->sortKeys();

        return view('public.rankings', compact('byCategory', 'season', 'seasons'));
    }

    public function contact(): View
    {
        return view('public.contact');
    }

    public function inscription(): View|RedirectResponse
    {
        $user = Auth::user();

        if (! $user->isCoach()) {
            return redirect()->route('public.home')
                ->with('inscription_error', 'Le formulaire d\'inscription est réservé aux coaches accrédités de la Ligue.');
        }

        if (! $user->is_validated) {
            return redirect()->route('public.home')
                ->with('inscription_error', 'Votre compte coach est en attente de validation. Vous pourrez inscrire des athlètes une fois votre compte approuvé.');
        }

        $events = Event::where('status', 'open')
            ->where(fn ($q) => $q->whereNull('registration_deadline')->orWhere('registration_deadline', '>', now()))
            ->orderBy('start_date')
            ->get();

        return view('public.inscription', compact('events'));
    }

    public function inscriptionStore(Request $request): RedirectResponse
    {
        $user = Auth::user();

        if (! $user->isCoach() || ! $user->is_validated) {
            abort(403, 'Accès réservé aux coaches validés.');
        }

        $data = $request->validate([
            'first_name'      => ['required', 'string', 'max:100'],
            'last_name'       => ['required', 'string', 'max:100'],
            'birth_date'      => ['required', 'date', 'before:today'],
            'birth_place'     => ['nullable', 'string', 'max:100'],
            'gender'          => ['required', 'in:M,F'],
            'nationality'     => ['nullable', 'string', 'max:100'],
            'weight'          => ['nullable', 'numeric', 'min:10', 'max:200'],
            'club'            => ['required', 'string', 'max:150'],
            'license_number'  => ['nullable', 'string', 'max:50'],
            'weight_category' => ['nullable', 'string', 'max:20'],
            'age_category'    => ['nullable', 'string', 'max:20'],
            'event_id'        => ['required', 'exists:events,id'],
            'photo'           => ['nullable', 'file', 'image', 'mimes:jpeg,jpg,png', 'max:2048'],
        ]);

        // Force coach identity from authenticated session — never trust form input
        $data['coach_id']   = $user->id;
        $data['created_by'] = $user->id;

        if ($request->hasFile('photo')) {
            $file         = $request->file('photo');
            $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png'];
            abort_unless(in_array($file->getMimeType(), $allowedMimes, true), 422, 'Type de fichier non autorisé.');
            $data['photo'] = $file->store('athletes', 'public');
        }

        $event     = Event::findOrFail($data['event_id']);

        if (! $event->isRegistrationOpen()) {
            return back()->withErrors(['event_id' => 'Les inscriptions pour cet événement sont fermées.'])->withInput();
        }

        $birthDate = \Carbon\Carbon::parse($data['birth_date']);
        $age       = $birthDate->age;

        $ageCategory = $this->categories->getAgeCategoryFromAge($age);
        if (! $ageCategory) {
            return back()->withErrors(['birth_date' => "L'âge minimum de participation est 10 ans (catégorie Minime)."])->withInput();
        }

        $validWeightCategories = $this->categories->getWeightCategories($ageCategory, $data['gender']);
        $chosenCategory        = $data['weight_category'] ?? null;

        $data['age_category']    = $ageCategory;
        $data['weight_category'] = ($chosenCategory && in_array($chosenCategory, $validWeightCategories))
            ? $chosenCategory
            : $this->categories->getWeightCategoryFromWeight((float) ($data['weight'] ?? 0), $ageCategory, $data['gender']);
        $data['registration_status'] = 'pending';
        $data['payment_status']      = 'unpaid';
        $data['payment_amount']      = $event->registration_fee ?? 0;

        $athlete = Athlete::create($data);

        return redirect()->route('public.inscription', [
            'event_id' => $athlete->event_id,
        ])->with('last_inscription', [
            'name'     => $athlete->full_name,
            'id'       => $athlete->id,
            'category' => $athlete->category_label,
            'license'  => $athlete->license_number,
        ]);
    }

}
