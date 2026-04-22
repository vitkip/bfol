<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::with(['category', 'author'])->latest();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('title_lo', 'like', "%$s%")
                  ->orWhere('title_en', 'like', "%$s%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $events = $query->paginate($request->get('per_page', 15));

        return response()->json($events->through(fn($e) => $this->format($e)));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title_lo'              => ['required', 'string', 'max:300'],
            'title_en'              => ['nullable', 'string', 'max:300'],
            'title_zh'              => ['nullable', 'string', 'max:300'],
            'description_lo'        => ['nullable', 'string'],
            'description_en'        => ['nullable', 'string'],
            'description_zh'        => ['nullable', 'string'],
            'location_lo'           => ['nullable', 'string'],
            'location_en'           => ['nullable', 'string'],
            'country'               => ['nullable', 'string'],
            'start_date'            => ['nullable', 'date'],
            'end_date'              => ['nullable', 'date'],
            'start_time'            => ['nullable', 'date_format:H:i'],
            'end_time'              => ['nullable', 'date_format:H:i'],
            'organizer_lo'          => ['nullable', 'string'],
            'organizer_en'          => ['nullable', 'string'],
            'category_id'           => ['nullable', 'exists:categories,id'],
            'registration_url'      => ['nullable', 'url'],
            'registration_deadline' => ['nullable', 'date'],
            'max_participants'      => ['nullable', 'integer', 'min:1'],
            'status'                => ['required', Rule::in(['upcoming', 'ongoing', 'completed', 'cancelled'])],
            'is_featured'           => ['boolean'],
            'is_international'      => ['boolean'],
            'thumbnail'             => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
        ]);

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('events/thumbnails', 'public');
        }

        $data['author_id'] = $request->user()->id;

        $event = Event::create($data);

        return response()->json($this->format($event->fresh(['category', 'author'])), 201);
    }

    public function show(Event $event)
    {
        return response()->json($this->format($event->load(['category', 'author'])));
    }

    public function update(Request $request, Event $event)
    {
        $data = $request->validate([
            'title_lo'              => ['required', 'string', 'max:300'],
            'title_en'              => ['nullable', 'string', 'max:300'],
            'description_lo'        => ['nullable', 'string'],
            'description_en'        => ['nullable', 'string'],
            'location_lo'           => ['nullable', 'string'],
            'location_en'           => ['nullable', 'string'],
            'country'               => ['nullable', 'string'],
            'start_date'            => ['nullable', 'date'],
            'end_date'              => ['nullable', 'date'],
            'start_time'            => ['nullable', 'date_format:H:i'],
            'end_time'              => ['nullable', 'date_format:H:i'],
            'organizer_lo'          => ['nullable', 'string'],
            'organizer_en'          => ['nullable', 'string'],
            'category_id'           => ['nullable', 'exists:categories,id'],
            'registration_url'      => ['nullable', 'url'],
            'registration_deadline' => ['nullable', 'date'],
            'max_participants'      => ['nullable', 'integer', 'min:1'],
            'status'                => ['required', Rule::in(['upcoming', 'ongoing', 'completed', 'cancelled'])],
            'is_featured'           => ['boolean'],
            'is_international'      => ['boolean'],
            'thumbnail'             => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
        ]);

        if ($request->hasFile('thumbnail')) {
            if ($event->thumbnail) {
                Storage::disk('public')->delete($event->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store('events/thumbnails', 'public');
        } else {
            unset($data['thumbnail']);
        }

        $event->update($data);

        return response()->json($this->format($event->fresh(['category', 'author'])));
    }

    public function destroy(Event $event)
    {
        if ($event->thumbnail) {
            Storage::disk('public')->delete($event->thumbnail);
        }
        $event->delete();

        return response()->json(['message' => 'ລຶບ Event ສຳເລັດ']);
    }

    public function updateStatus(Request $request, Event $event)
    {
        $request->validate(['status' => ['required', Rule::in(['upcoming', 'ongoing', 'completed', 'cancelled'])]]);
        $event->update(['status' => $request->status]);

        return response()->json(['message' => 'ອັບເດດສຳເລັດ', 'status' => $event->status]);
    }

    private function format(Event $e): array
    {
        return [
            'id'               => $e->id,
            'title_lo'         => $e->title_lo,
            'title_en'         => $e->title_en,
            'slug'             => $e->slug,
            'description_lo'   => $e->description_lo,
            'description_en'   => $e->description_en,
            'location_lo'      => $e->location_lo,
            'location_en'      => $e->location_en,
            'country'          => $e->country,
            'start_date'       => $e->start_date?->toDateString(),
            'end_date'         => $e->end_date?->toDateString(),
            'start_time'       => $e->start_time,
            'end_time'         => $e->end_time,
            'organizer_lo'     => $e->organizer_lo,
            'organizer_en'     => $e->organizer_en,
            'registration_url' => $e->registration_url,
            'registration_deadline' => $e->registration_deadline?->toDateString(),
            'max_participants' => $e->max_participants,
            'status'           => $e->status,
            'is_featured'      => $e->is_featured,
            'is_international' => $e->is_international,
            'view_count'       => $e->view_count,
            'thumbnail'        => $e->thumbnail ? asset('storage/' . $e->thumbnail) : null,
            'category'         => $e->category ? ['id' => $e->category->id, 'name' => $e->category->name_lo] : null,
            'author'           => $e->author ? ['id' => $e->author->id, 'name' => $e->author->full_name_lo ?: $e->author->email] : null,
            'created_at'       => $e->created_at?->toDateTimeString(),
        ];
    }
}
