@extends('admin.layouts.app')

@section('page_title', 'Event Tags')

@section('content')
<div class="flex justify-between items-center mb-6">
  <h1 class="text-2xl font-bold">Event Tags</h1>
  <a href="{{ route('admin.event_tags.create') }}" class="primary-gradient text-white font-semibold px-5 py-2 rounded-lg hover:opacity-90 transition">+ Add Event Tag</a>
</div>
@if(session('success'))
  <div class="mb-4 text-green-700 bg-green-100 rounded p-3">{{ session('success') }}</div>
@endif
<div class="bg-surface-container-lowest rounded-xl shadow overflow-x-auto">
  <table class="min-w-full divide-y divide-surface-container-high">
    <thead>
      <tr>
        <th class="px-4 py-2 text-left">#</th>
        <th class="px-4 py-2 text-left">Name (Lao)</th>
        <th class="px-4 py-2 text-left">Actions</th>
      </tr>
    </thead>
    <tbody>
      @foreach($eventTags as $eventTag)
      <tr class="border-b border-surface-container-high">
        <td class="px-4 py-2">{{ $eventTag->id }}</td>
        <td class="px-4 py-2">{{ $eventTag->name_lo }}</td>
        <td class="px-4 py-2 flex gap-2">
          <a href="{{ route('admin.event_tags.show', $eventTag) }}" class="text-blue-600 hover:underline">View</a>
          <a href="{{ route('admin.event_tags.edit', $eventTag) }}" class="text-yellow-600 hover:underline">Edit</a>
          <form action="{{ route('admin.event_tags.destroy', $eventTag) }}" method="POST" onsubmit="return confirm('Delete this event tag?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-red-600 hover:underline">Delete</button>
          </form>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
  <div class="p-4">{{ $eventTags->links() }}</div>
</div>
@endsection
