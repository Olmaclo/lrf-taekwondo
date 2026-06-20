<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Event;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $events = Event::whereIn('status', ['upcoming', 'open', 'ongoing', 'finished'])
            ->latest('updated_at')
            ->get(['slug', 'updated_at']);

        $posts = BlogPost::where('status', 'published')
            ->latest('published_at')
            ->get(['slug', 'published_at', 'updated_at']);

        $staticPages = [
            ['url' => route('public.home'),    'priority' => '1.0',  'freq' => 'weekly'],
            ['url' => route('public.events'),    'priority' => '0.9',  'freq' => 'daily'],
            ['url' => route('public.rankings'),  'priority' => '0.8',  'freq' => 'weekly'],
            ['url' => route('public.gallery'),   'priority' => '0.7',  'freq' => 'weekly'],
            ['url' => route('public.blog'),    'priority' => '0.8',  'freq' => 'weekly'],
            ['url' => route('public.verify'),  'priority' => '0.6',  'freq' => 'monthly'],
            ['url' => route('public.contact'), 'priority' => '0.5',  'freq' => 'monthly'],
        ];

        $xml = view('sitemap', compact('staticPages', 'events', 'posts'))->render();

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }
}
