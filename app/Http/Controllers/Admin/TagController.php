<?php

namespace App\Http\Controllers\Admin;

use App\Models\Tag;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::all();
        return view('admin.tags.index', compact('tags'));
    }

    public function create()
    {
        return view('admin.tags.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name_lo' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'name_zh' => 'required|string|max:255',
        ]);
        Tag::create($request->only(['name_lo', 'name_en', 'name_zh']));
        return redirect()->route('admin.tags.index')->with('success', 'Tag created successfully.');
    }

    public function show(Tag $tag)
    {
        return view('admin.tags.show', compact('tag'));
    }

    public function edit(Tag $tag)
    {
        return view('admin.tags.edit', compact('tag'));
    }

    public function update(Request $request, Tag $tag)
    {
        $request->validate([
            'name_lo' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'name_zh' => 'required|string|max:255',
        ]);
        $tag->update($request->only(['name_lo', 'name_en', 'name_zh']));
        return redirect()->route('admin.tags.index')->with('success', 'Tag updated successfully.');
    }

    public function destroy(Tag $tag)
    {
        $tag->delete();
        return redirect()->route('admin.tags.index')->with('success', 'Tag deleted successfully.');
    }
}
