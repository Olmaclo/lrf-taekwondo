<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class EventController extends Controller
{
    public function index(): JsonResponse
    {
        $events = Event::withCount('athletes')
            ->latest()
            ->get()
            ->map(fn ($e) => [
                'id'               => $e->id,
                'name'             => $e->name,
                'slug'             => $e->slug,
                'type'             => $e->type,
                'type_label'       => $e->type_label,
                'start_date'       => $e->start_date?->format('d/m/Y'),
                'end_date'         => $e->end_date?->format('d/m/Y'),
                'location'         => $e->location,
                'cover_url'        => $e->cover_url,
                'status'           => $e->status,
                'status_label'     => $e->status_label,
                'status_color'     => $e->status_color,
                'registration_fee' => $e->registration_fee,
                'athletes_count'   => $e->athletes_count,
            ]);

        return response()->json(['success' => true, 'data' => $events]);
    }

    public function show(Event $event): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => array_merge($event->toArray(), [
                'stats'        => $event->athlete_stats,
                'categories'   => $event->categories,
                'status_label' => $event->status_label,
                'type_label'   => $event->type_label,
            ]),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        abort_unless(Auth::user()->isTechnical(), 403);

        $data = $request->validate([
            'name'                  => ['required', 'string', 'max:200'],
            'description'           => ['nullable', 'string'],
            'type'                  => ['required', Rule::in(['kyorugi', 'poomsae', 'mixed', 'other'])],
            'start_date'            => ['required', 'date'],
            'end_date'              => ['nullable', 'date', 'after_or_equal:start_date'],
            'location'              => ['nullable', 'string', 'max:200'],
            'cover_image'           => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:3072'],
            'registration_fee'      => ['nullable', 'numeric', 'min:0'],
            'status'                => ['nullable', Rule::in(['upcoming', 'open', 'closed', 'ongoing', 'finished', 'cancelled'])],
            'registration_deadline' => ['nullable', 'date'],
        ]);

        if ($request->hasFile('cover_image')) {
            $mime = $request->file('cover_image')->getMimeType();
            abort_unless(in_array($mime, ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'], true), 422, 'Type de fichier non autorisé.');
            $data['cover_image'] = $request->file('cover_image')->store('events', 'public');
        } else {
            unset($data['cover_image']);
        }

        $data['created_by'] = Auth::id();
        $data['status']     = $data['status'] ?? 'upcoming';

        $event = Event::create($data);

        return response()->json([
            'success' => true,
            'message' => "Événement \"{$event->name}\" créé.",
            'data'    => $event,
        ], 201);
    }

    public function update(Request $request, Event $event): JsonResponse
    {
        abort_unless(Auth::user()->isTechnical(), 403);

        $data = $request->validate([
            'name'                  => ['sometimes', 'string', 'max:200'],
            'description'           => ['nullable', 'string'],
            'type'                  => ['sometimes', Rule::in(['kyorugi', 'poomsae', 'mixed', 'other'])],
            'start_date'            => ['sometimes', 'date'],
            'end_date'              => ['nullable', 'date'],
            'location'              => ['nullable', 'string'],
            'cover_image'           => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:3072'],
            'registration_fee'      => ['nullable', 'numeric', 'min:0'],
            'status'                => ['sometimes', Rule::in(['upcoming', 'open', 'closed', 'ongoing', 'finished', 'cancelled'])],
            'registration_deadline' => ['nullable', 'date'],
        ]);

        if ($request->hasFile('cover_image')) {
            $mime = $request->file('cover_image')->getMimeType();
            abort_unless(in_array($mime, ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'], true), 422, 'Type de fichier non autorisé.');
            if ($event->cover_image) {
                Storage::disk('public')->delete($event->cover_image);
            }
            $data['cover_image'] = $request->file('cover_image')->store('events', 'public');
        } else {
            unset($data['cover_image']);
        }

        $event->update($data);

        return response()->json(['success' => true, 'message' => 'Événement mis à jour.', 'data' => $event->fresh()]);
    }

    public function destroy(Event $event): JsonResponse
    {
        abort_unless(Auth::user()->isTechnical(), 403);
        $event->delete();
        return response()->json(['success' => true, 'message' => 'Événement supprimé.']);
    }
}
