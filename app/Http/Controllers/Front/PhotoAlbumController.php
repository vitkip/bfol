<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\PhotoAlbum;

class PhotoAlbumController extends Controller
{
    public function index()
    {
        $albums = PhotoAlbum::public()
            ->withCount('images')
            ->with(['images' => fn($q) => $q->orderBy('sort_order')->limit(1)])
            ->latest()
            ->paginate(18);

        return view('front.albums.index', compact('albums'));
    }

    public function show(PhotoAlbum $album)
    {
        abort_if(!$album->is_public, 404);

        $album->load([
            'images' => fn($q) => $q->orderBy('sort_order')->orderBy('id'),
            'event',
        ]);

        return view('front.albums.show', compact('album'));
    }
}
