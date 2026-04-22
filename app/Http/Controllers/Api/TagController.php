<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tag;

class TagController extends Controller
{
    public function index()
    {
        return response()->json(Tag::orderBy('name_lo')->get()->map(fn($t) => [
            'id'   => $t->id,
            'name' => $t->name_lo ?: $t->name_en,
            'slug' => $t->slug,
        ]));
    }
}
