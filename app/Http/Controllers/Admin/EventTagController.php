<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EventTag;
use Illuminate\Http\Request;

class EventTagController extends Controller
{
    public function index()
    {
        $eventTags = EventTag::latest()->paginate(15);
        return view('admin.event_tags.index', compact('eventTags'));
    }

    public function create()
    {
        return view('admin.event_tags.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name_lo' => 'required',
            'name_en' => 'required',
            'name_zh' => 'required',
        ]);
        $eventTag = EventTag::create($validated);
        return redirect()->route('admin.event_tags.index')->with('success', 'Event Tag created successfully.');
    }

    public function show(EventTag $eventTag)
    {
        return view('admin.event_tags.show', compact('eventTag'));
    }

    public function edit(EventTag $eventTag)
    {
        return view('admin.event_tags.edit', compact('eventTag'));
    }

    public function update(Request $request, EventTag $eventTag)
    {
        $validated = $request->validate([
            'name_lo' => 'required',
            'name_en' => 'required',
            'name_zh' => 'required',
        ]);
        $eventTag->update($validated);
        return redirect()->route('admin.event_tags.index')->with('success', 'Event Tag updated successfully.');
    }

    public function destroy(EventTag $eventTag)
    {
        $eventTag->delete();
        return redirect()->route('admin.event_tags.index')->with('success', 'Event Tag deleted successfully.');
    }
}
