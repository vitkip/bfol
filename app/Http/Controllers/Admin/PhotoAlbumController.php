<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\PhotoAlbum;
use App\Models\PhotoAlbumImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PhotoAlbumController extends Controller
{
    public function index(Request $request)
    {
        $query = PhotoAlbum::withCount('images')->latest();

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(fn($s) => $s->where('title_lo', 'like', "%{$q}%")
                                      ->orWhere('title_en', 'like', "%{$q}%")
                                      ->orWhere('title_zh', 'like', "%{$q}%"));
        }
        if ($request->filled('visibility')) {
            $query->where('is_public', $request->visibility === 'public');
        }

        $albums = $query->paginate(20)->withQueryString();
        return view('admin.albums.index', compact('albums'));
    }

    public function create()
    {
        $events = Event::orderByDesc('start_date')->limit(50)->get();
        return view('admin.albums.create', compact('events'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title_lo'       => 'required|string|max:200',
            'title_en'       => 'nullable|string|max:200',
            'title_zh'       => 'nullable|string|max:200',
            'description_lo' => 'nullable|string',
            'description_en' => 'nullable|string',
            'description_zh' => 'nullable|string',
            'event_id'       => 'nullable|exists:events,id',
            'is_public'      => 'nullable|boolean',
            'cover_file'     => 'nullable|image|max:4096',
            'images.*'       => 'nullable|image|max:4096',
        ]);

        $coverPath = null;
        if ($request->hasFile('cover_file')) {
            $coverPath = '/storage/' . $request->file('cover_file')->store('albums/covers', 'public');
        }

        $album = PhotoAlbum::create([
            'title_lo'       => $request->title_lo,
            'title_en'       => $request->title_en,
            'title_zh'       => $request->title_zh,
            'description_lo' => $request->description_lo,
            'description_en' => $request->description_en,
            'description_zh' => $request->description_zh,
            'event_id'       => $request->event_id ?: null,
            'cover_image'    => $coverPath,
            'is_public'      => $request->boolean('is_public'),
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $i => $file) {
                $path = '/storage/' . $file->store('albums/' . $album->id, 'public');
                PhotoAlbumImage::create([
                    'album_id'   => $album->id,
                    'image_url'  => $path,
                    'sort_order' => $i,
                ]);
            }
        }

        return redirect()->route('admin.albums.edit', $album)
                         ->with('success', 'ສ້າງອາລ໌ບໍ່ສຳເລັດ — ສາມາດເພີ່ມຮູບໄດ້ດ່ານລຸ່ມ');
    }

    public function show(PhotoAlbum $album)
    {
        return redirect()->route('admin.albums.edit', $album);
    }

    public function edit(PhotoAlbum $album)
    {
        $album->load(['images' => fn($q) => $q->orderBy('sort_order')->orderBy('id')]);
        $events = Event::orderByDesc('start_date')->limit(50)->get();
        return view('admin.albums.edit', compact('album', 'events'));
    }

    public function update(Request $request, PhotoAlbum $album)
    {
        $request->validate([
            'title_lo'       => 'required|string|max:200',
            'title_en'       => 'nullable|string|max:200',
            'title_zh'       => 'nullable|string|max:200',
            'description_lo' => 'nullable|string',
            'description_en' => 'nullable|string',
            'description_zh' => 'nullable|string',
            'event_id'       => 'nullable|exists:events,id',
            'is_public'      => 'nullable|boolean',
            'cover_file'     => 'nullable|image|max:4096',
            'images.*'       => 'nullable|image|max:4096',
        ]);

        $data = [
            'title_lo'       => $request->title_lo,
            'title_en'       => $request->title_en,
            'title_zh'       => $request->title_zh,
            'description_lo' => $request->description_lo,
            'description_en' => $request->description_en,
            'description_zh' => $request->description_zh,
            'event_id'       => $request->event_id ?: null,
            'is_public'      => $request->boolean('is_public'),
        ];

        if ($request->hasFile('cover_file')) {
            if ($album->cover_image) {
                $old = str_replace('/storage/', '', $album->cover_image);
                Storage::disk('public')->delete($old);
            }
            $data['cover_image'] = '/storage/' . $request->file('cover_file')->store('albums/covers', 'public');
        }

        $album->update($data);

        if ($request->hasFile('images')) {
            $next = $album->images()->max('sort_order') + 1;
            foreach ($request->file('images') as $i => $file) {
                $path = '/storage/' . $file->store('albums/' . $album->id, 'public');
                PhotoAlbumImage::create([
                    'album_id'   => $album->id,
                    'image_url'  => $path,
                    'sort_order' => $next + $i,
                ]);
            }
        }

        return redirect()->route('admin.albums.edit', $album)
                         ->with('success', 'ບັນທຶກສຳເລັດແລ້ວ');
    }

    public function destroyImage(PhotoAlbum $album, PhotoAlbumImage $image)
    {
        abort_if($image->album_id !== $album->id, 404);

        $path = str_replace('/storage/', '', $image->image_url);
        Storage::disk('public')->delete($path);
        $image->delete();

        return back()->with('success', 'ລຶບຮູບສຳເລັດ');
    }

    public function destroy(PhotoAlbum $album)
    {
        foreach ($album->images as $img) {
            $path = str_replace('/storage/', '', $img->image_url);
            Storage::disk('public')->delete($path);
            $img->delete();
        }
        if ($album->cover_image) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $album->cover_image));
        }

        $album->delete();
        return redirect()->route('admin.albums.index')->with('success', 'ລຶບອາລ໌ບໍ່ສຳເລັດ');
    }
}
