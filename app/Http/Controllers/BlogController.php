<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        abort_unless(Auth::user()->isTechnical(), 403);

        $q = BlogPost::with('author:id,name')
            ->latest();

        if ($request->status) {
            $q->where('status', $request->status);
        }
        if ($request->search) {
            $q->where('title', 'like', '%' . $request->search . '%');
        }

        $perPage = (int) ($request->per_page ?? 20);
        $posts   = $q->paginate($perPage);

        $mapped = collect($posts->items())->map(fn ($p) => $this->format($p));

        return response()->json([
            'success' => true,
            'data'    => $mapped,
            'meta'    => [
                'total'        => $posts->total(),
                'current_page' => $posts->currentPage(),
                'last_page'    => $posts->lastPage(),
                'published'    => BlogPost::where('status', 'published')->count(),
                'drafts'       => BlogPost::where('status', 'draft')->count(),
            ],
        ]);
    }

    public function show(BlogPost $blogPost): JsonResponse
    {
        abort_unless(Auth::user()->isTechnical(), 403);
        return response()->json(['success' => true, 'data' => $this->format($blogPost)]);
    }

    public function store(Request $request): JsonResponse
    {
        abort_unless(Auth::user()->isTechnical(), 403);

        $data = $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'content'     => ['required', 'string'],
            'excerpt'     => ['nullable', 'string', 'max:500'],
            'status'      => ['nullable', 'in:draft,published,archived'],
            'cover_image' => ['nullable', 'file', 'image', 'mimes:jpeg,jpg,png,webp', 'max:4096'],
        ]);

        $data['author_id'] = Auth::id();
        $data['status']    = $data['status'] ?? 'draft';
        $data['content']   = $this->sanitizeHtml($data['content']);

        if ($request->hasFile('cover_image')) {
            $mime = $request->file('cover_image')->getMimeType();
            abort_unless(in_array($mime, ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'], true), 422, 'Type de fichier non autorisé.');
            $data['cover_image'] = $request->file('cover_image')->store('blog', 'public');
        }

        if ($data['status'] === 'published') {
            $data['published_at'] = now();
        }

        $post = BlogPost::create($data);

        return response()->json([
            'success' => true,
            'message' => "Article \"{$post->title}\" créé.",
            'data'    => $this->format($post),
        ], 201);
    }

    public function update(Request $request, BlogPost $blogPost): JsonResponse
    {
        abort_unless(Auth::user()->isTechnical(), 403);

        $data = $request->validate([
            'title'       => ['sometimes', 'string', 'max:255'],
            'content'     => ['sometimes', 'string'],
            'excerpt'     => ['nullable', 'string', 'max:500'],
            'status'      => ['nullable', 'in:draft,published,archived'],
            'cover_image' => ['nullable', 'file', 'image', 'mimes:jpeg,jpg,png,webp', 'max:4096'],
        ]);

        if (isset($data['content'])) {
            $data['content'] = $this->sanitizeHtml($data['content']);
        }

        if ($request->hasFile('cover_image')) {
            $mime = $request->file('cover_image')->getMimeType();
            abort_unless(in_array($mime, ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'], true), 422, 'Type de fichier non autorisé.');
            if ($blogPost->cover_image) {
                Storage::disk('public')->delete($blogPost->cover_image);
            }
            $data['cover_image'] = $request->file('cover_image')->store('blog', 'public');
        }

        if (isset($data['status']) && $data['status'] === 'published' && !$blogPost->published_at) {
            $data['published_at'] = now();
        }

        $blogPost->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Article mis à jour.',
            'data'    => $this->format($blogPost->fresh()),
        ]);
    }

    public function destroy(BlogPost $blogPost): JsonResponse
    {
        abort_unless(Auth::user()->isTechnical(), 403);

        if ($blogPost->cover_image) {
            Storage::disk('public')->delete($blogPost->cover_image);
        }

        $blogPost->delete();

        return response()->json(['success' => true, 'message' => 'Article supprimé.']);
    }

    public function publish(BlogPost $blogPost): JsonResponse
    {
        abort_unless(Auth::user()->isTechnical(), 403);

        $blogPost->update([
            'status'       => 'published',
            'published_at' => $blogPost->published_at ?? now(),
        ]);

        return response()->json(['success' => true, 'message' => "Article \"{$blogPost->title}\" publié.", 'data' => $this->format($blogPost->fresh())]);
    }

    public function archive(BlogPost $blogPost): JsonResponse
    {
        abort_unless(Auth::user()->isTechnical(), 403);

        $blogPost->update(['status' => 'archived']);

        return response()->json(['success' => true, 'message' => "Article archivé.", 'data' => $this->format($blogPost->fresh())]);
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    private function sanitizeHtml(string $html): string
    {
        // strip_tags() seul ne supprime pas les attributs dangereux (onerror, onclick, href=javascript:…)
        // On utilise un purificateur maison basé sur DOMDocument pour supprimer les attributs d'événements
        // et les valeurs de href/src non-HTTP, sans dépendance externe.

        $allowedTags = [
            'p', 'br', 'b', 'strong', 'i', 'em', 'u', 's',
            'ul', 'ol', 'li', 'h1', 'h2', 'h3', 'h4',
            'a', 'img', 'blockquote', 'hr',
            'table', 'thead', 'tbody', 'tr', 'td', 'th',
            'span', 'div', 'figure', 'figcaption',
        ];

        $allowedAttributes = [
            'a'   => ['href', 'title', 'target'],
            'img' => ['src', 'alt', 'title', 'width', 'height'],
            'td'  => ['colspan', 'rowspan'],
            'th'  => ['colspan', 'rowspan'],
        ];

        // Supprimer d'abord les tags non autorisés
        $clean = strip_tags($html, '<' . implode('><', $allowedTags) . '>');

        // Purifier les attributs via DOMDocument
        $doc = new \DOMDocument();
        @$doc->loadHTML('<?xml encoding="utf-8"?><div id="__wrap">' . $clean . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        $xpath = new \DOMXPath($doc);
        foreach ($xpath->query('//*') as $node) {
            /** @var \DOMElement $node */
            if (! in_array(strtolower($node->tagName), $allowedTags, true)) {
                continue;
            }
            $allowed = $allowedAttributes[$node->tagName] ?? [];
            $toRemove = [];
            foreach ($node->attributes as $attr) {
                $name  = strtolower($attr->name);
                $value = strtolower(trim($attr->value));
                // Supprimer tout attribut non autorisé ou contenant javascript:
                if (! in_array($name, $allowed, true) || str_starts_with($value, 'javascript:')) {
                    $toRemove[] = $attr->name;
                }
            }
            foreach ($toRemove as $a) {
                $node->removeAttribute($a);
            }
        }

        $wrap = $doc->getElementById('__wrap');
        $result = '';
        if ($wrap) {
            foreach ($wrap->childNodes as $child) {
                $result .= $doc->saveHTML($child);
            }
        }

        return $result ?: $clean;
    }

    // ── Private ────────────────────────────────────────────────────────────────

    private function format(BlogPost $post): array
    {
        return [
            'id'           => $post->id,
            'title'        => $post->title,
            'slug'         => $post->slug,
            'excerpt_auto' => $post->excerpt_auto,
            'content'      => $post->content,
            'excerpt'      => $post->excerpt,
            'cover_url'    => $post->cover_url,
            'status'       => $post->status,
            'status_label' => $post->status_label,
            'status_color' => $post->status_color,
            'author'       => $post->author ? ['id' => $post->author->id, 'name' => $post->author->name] : null,
            'published_at' => $post->published_at?->format('d/m/Y'),
            'created_at'   => $post->created_at->format('d/m/Y'),
            'views_count'  => $post->views_count,
        ];
    }
}
