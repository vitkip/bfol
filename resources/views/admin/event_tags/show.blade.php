@extends('admin.layouts.app')

@section('page_title', 'Event Tag Details')

@section('content')
<div class="max-w-xl mx-auto">
  <div class="bg-surface-container-lowest rounded-xl shadow p-6 mb-6">
    <h2 class="text-lg font-bold mb-4">Event Tag Details</h2>
    <div class="mb-4">
      <div class="flex justify-between py-2 border-b border-surface-container-high">
        <span class="font-semibold text-outline">ID:</span>
        <span>{{ $eventTag->id }}</span>
      </div>
      <div class="flex justify-between py-2 border-b border-surface-container-high">
        <span class="font-semibold text-outline">Name (Lao):</span>
        <span>{{ $eventTag->name_lo }}</span>
      </div>
      <div class="flex justify-between py-2 border-b border-surface-container-high">
        <span class="font-semibold text-outline">Name (English):</span>
        <span>{{ $eventTag->name_en }}</span>
      </div>
      <div class="flex justify-between py-2 border-b border-surface-container-high">
        <span class="font-semibold text-outline">Name (Chinese):</span>
        <span>{{ $eventTag->name_zh }}</span>
      </div>
    </div>
    <div class="flex gap-2 mt-4">
      <a href="{{ route('admin.event_tags.edit', $eventTag) }}" class="primary-gradient text-white font-semibold px-5 py-2 rounded-lg hover:opacity-90 transition">Edit</a>
      <a href="{{ route('admin.event_tags.index') }}" class="px-5 py-2 rounded-lg border border-surface-container-high text-outline hover:bg-surface-container transition">Back</a>
    </div>
  </div>
</div>
@endsection
