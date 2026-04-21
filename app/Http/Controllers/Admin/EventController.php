<?php

namespace App\Http\Controllers\Admin;


use App\Models\Event;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::latest()->paginate(15);
        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        return view('admin.events.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title_lo'        => 'required|string|max:255',
            'title_en'        => 'required|string|max:255',
            'title_zh'        => 'required|string|max:255',
            'description_lo'  => 'nullable|string',
            'description_en'  => 'nullable|string',
            'description_zh'  => 'nullable|string',
            'start_date'      => 'required|date',
            'end_date'        => 'nullable|date|after_or_equal:start_date',
            'start_time'      => 'nullable|date_format:H:i',
            'end_time'        => 'nullable|date_format:H:i',
            'location_lo'     => 'nullable|string|max:255',
            'location_en'     => 'nullable|string|max:255',
            'location_zh'     => 'nullable|string|max:255',
            'country'         => 'nullable|string|max:255',
            'status'          => 'nullable|in:upcoming,ongoing,completed,cancelled',
            'is_featured'     => 'nullable|boolean',
            'is_international'=> 'nullable|boolean',
            'thumbnail'       => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('events/thumbnails', 'public');
        }

        $validated['is_featured']      = $request->boolean('is_featured');
        $validated['is_international'] = $request->boolean('is_international');

        $event = Event::create($validated);
        return redirect()->route('admin.events.index')->with('success', 'Event created successfully.');
    }

    public function show(Event $event)
    {
        return view('admin.events.show', compact('event'));
    }

    public function edit(Event $event)
    {
        return view('admin.events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title_lo'        => 'required|string|max:255',
            'title_en'        => 'required|string|max:255',
            'title_zh'        => 'required|string|max:255',
            'description_lo'  => 'nullable|string',
            'description_en'  => 'nullable|string',
            'description_zh'  => 'nullable|string',
            'start_date'      => 'required|date',
            'end_date'        => 'nullable|date|after_or_equal:start_date',
            'start_time'      => 'nullable|date_format:H:i',
            'end_time'        => 'nullable|date_format:H:i',
            'location_lo'     => 'nullable|string|max:255',
            'location_en'     => 'nullable|string|max:255',
            'location_zh'     => 'nullable|string|max:255',
            'country'         => 'nullable|string|max:255',
            'status'          => 'nullable|in:upcoming,ongoing,completed,cancelled',
            'is_featured'     => 'nullable|boolean',
            'is_international'=> 'nullable|boolean',
            'thumbnail'       => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('events/thumbnails', 'public');
        }

        $validated['is_featured']      = $request->boolean('is_featured');
        $validated['is_international'] = $request->boolean('is_international');

        $event->update($validated);
        return redirect()->route('admin.events.index')->with('success', 'Event updated successfully.');
    }

    public function destroy(Event $event)
    {
        $event->delete();
        return redirect()->route('admin.events.index')->with('success', 'Event deleted successfully.');
    }
}
