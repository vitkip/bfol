<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Event;
use App\Models\MediaItem;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MediaController extends Controller
{
    // ──────────────────────────────────────────────
    // MIME → type mapping
    // ──────────────────────────────────────────────
    private const MIME_MAP = [
        'image'    => ['image/jpeg','image/png','image/webp','image/gif','image/svg+xml'],
        'video'    => ['video/mp4','video/avi','video/quicktime','video/webm','video/x-matroska'],
        'audio'    => ['audio/mpeg','audio/mp3','audio/ogg','audio/wav','audio/aac','audio/flac'],
        'document' => ['application/pdf','application/msword',
                       'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                       'application/vnd.ms-excel',
                       'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                       'application/vnd.ms-powerpoint',
                       'application/vnd.openxmlformats-officedocument.presentationml.presentation'],
    ];

    // ──────────────────────────────────────────────
    public function index(Request $request)
    {
        $query = MediaItem::with('category')->latest('published_at')->latest();

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('platform')) {
            $query->where('platform', $request->platform);
        }
        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(fn($s) => $s->where('title_lo', 'like', "%{$q}%")
                                      ->orWhere('title_en', 'like', "%{$q}%"));
        }

        $items      = $query->paginate(20)->withQueryString();
        $typeCounts = MediaItem::selectRaw('type, count(*) as total')
                               ->groupBy('type')->pluck('total', 'type');

        return view('admin.media.index', compact('items', 'typeCounts'));
    }

    // ──────────────────────────────────────────────
    public function create()
    {
        $categories = Category::active()->orderBy('name_lo')->get();
        $events     = Event::orderBy('title_lo')->get();
        return view('admin.media.create', compact('categories', 'events'));
    }

    // ──────────────────────────────────────────────
    public function store(Request $request)
    {
        $validated = $this->validateMedia($request);

        $this->handleFileAndUrl($request, $validated);

        $validated['author_id']    = auth()->id();
        $validated['is_featured']  = $request->boolean('is_featured');
        $validated['published_at'] = $request->filled('publish_now')
            ? now()
            : ($request->filled('published_at') ? $request->published_at : null);

        MediaItem::create($validated);

        return redirect()->route('admin.media.index')->with('success', 'ເພີ່ມສື່ທຳສຳເລັດແລ້ວ');
    }

    // ──────────────────────────────────────────────
    public function show(MediaItem $medium)
    {
        $medium->load('category', 'event', 'author');
        return view('admin.media.show', compact('medium'));
    }

    // ──────────────────────────────────────────────
    public function edit(MediaItem $medium)
    {
        $categories = Category::active()->orderBy('name_lo')->get();
        $events     = Event::orderBy('title_lo')->get();
        return view('admin.media.edit', compact('medium', 'categories', 'events'));
    }

    // ──────────────────────────────────────────────
    public function update(Request $request, MediaItem $medium)
    {
        $validated = $this->validateMedia($request, $medium->id);

        $this->handleFileAndUrl($request, $validated, $medium);

        $validated['is_featured']  = $request->boolean('is_featured');
        $validated['published_at'] = $request->filled('publish_now')
            ? now()
            : ($request->filled('published_at') ? $request->published_at : $medium->published_at);

        $medium->update($validated);

        return redirect()->route('admin.media.index')->with('success', 'ແກ້ໄຂສື່ທຳສຳເລັດແລ້ວ');
    }

    // ──────────────────────────────────────────────
    public function destroy(MediaItem $medium)
    {
        $medium->delete();
        return redirect()->route('admin.media.index')->with('success', 'ລຶບສື່ທຳສຳເລັດແລ້ວ');
    }

    // ══════════════════════════════════════════════
    // Helpers
    // ══════════════════════════════════════════════
    private function validateMedia(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'title_lo'       => 'required|string|max:300',
            'title_en'       => 'nullable|string|max:300',
            'title_zh'       => 'nullable|string|max:300',
            'type'           => ['required', Rule::in(['image','video','audio','document'])],
            'platform'       => ['required', Rule::in(['local','youtube','facebook','soundcloud','other'])],
            'external_url'   => 'nullable|string|max:500',
            'file_upload'    => 'nullable|file|max:102400',
            'thumbnail_file' => 'nullable|image|max:4096',
            'thumbnail_url'  => 'nullable|string|max:500',
            'description_lo' => 'nullable|string',
            'description_en' => 'nullable|string',
            'description_zh' => 'nullable|string',
            'category_id'    => 'nullable|exists:categories,id',
            'event_id'       => 'nullable|exists:events,id',
            'duration_sec'   => 'nullable|integer|min:0',
            'is_featured'    => 'nullable|boolean',
            'published_at'   => 'nullable|date',
        ]);
    }

    private function handleFileAndUrl(Request $request, array &$validated, ?MediaItem $existing = null): void
    {
        $isLocal = ($validated['platform'] === 'local');

        // ── ไฟล์หลัก ──
        if ($request->hasFile('file_upload')) {
            $file = $request->file('file_upload');
            $folder = match($validated['type']) {
                'image'    => 'media/images',
                'video'    => 'media/videos',
                'audio'    => 'media/audio',
                'document' => 'media/documents',
                default    => 'media/other',
            };
            $validated['file_url']     = '/storage/' . $file->store($folder, 'public');
            $validated['mime_type']    = $file->getMimeType();
            $validated['file_size_kb'] = (int) ceil($file->getSize() / 1024);
        } elseif ($isLocal && !$existing) {
            // new local item with no file
            abort(422, 'ກະລຸນາອັບໂຫຼດໄຟລ໌');
        } elseif (!$isLocal) {
            // external: clear local file_url
            if (empty($validated['external_url'])) {
                abort(422, 'ກະລຸນາໃສ່ External URL');
            }
            $validated['file_url'] = null;
        } else {
            // keep existing
            unset($validated['file_url'], $validated['mime_type'], $validated['file_size_kb']);
        }

        // ── thumbnail ──
        if ($request->hasFile('thumbnail_file')) {
            $validated['thumbnail_url'] = '/storage/' . $request->file('thumbnail_file')
                                            ->store('media/thumbnails', 'public');
        } elseif (!empty($validated['thumbnail_url'])) {
            // keep submitted URL string
        } else {
            // keep existing or leave null
            unset($validated['thumbnail_url']);
        }

        // remove upload-only keys
        unset($validated['file_upload'], $validated['thumbnail_file']);
    }
}
