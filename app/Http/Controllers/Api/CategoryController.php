<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::active()->orderBy('name_lo');

        if ($request->filled('type')) {
            $query->ofType($request->type);
        }

        return response()->json($query->get()->map(fn($c) => [
            'id'   => $c->id,
            'name' => $c->name_lo ?: $c->name_en,
            'type' => $c->type,
            'slug' => $c->slug,
        ]));
    }
}
