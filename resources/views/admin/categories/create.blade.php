@extends('admin.layouts.app')

@section('page_title', 'Create Category')

@section('content')
<div class="max-w-xl mx-auto">
    <div class="bg-surface-container-lowest rounded-xl shadow p-6 mb-6">
        <h2 class="text-lg font-bold mb-4">Create Category</h2>
        <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="name_lo" class="block text-sm font-semibold mb-1">Name (Lao)</label>
                <input type="text" name="name_lo" id="name_lo" class="w-full rounded-lg border border-surface-container-high px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary/30" value="{{ old('name_lo') }}" required>
                @error('name_lo')
                    <div class="text-danger-700 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div>
                <label for="name_en" class="block text-sm font-semibold mb-1">Name (English)</label>
                <input type="text" name="name_en" id="name_en" class="w-full rounded-lg border border-surface-container-high px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary/30" value="{{ old('name_en') }}" required>
                @error('name_en')
                    <div class="text-danger-700 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div>
                <label for="name_zh" class="block text-sm font-semibold mb-1">Name (Chinese)</label>
                <input type="text" name="name_zh" id="name_zh" class="w-full rounded-lg border border-surface-container-high px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary/30" value="{{ old('name_zh') }}" required>
                @error('name_zh')
                    <div class="text-danger-700 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="flex gap-2 mt-4">
                <button type="submit" class="primary-gradient text-white font-semibold px-5 py-2 rounded-lg hover:opacity-90 transition">Create</button>
                <a href="{{ route('admin.categories.index') }}" class="px-5 py-2 rounded-lg border border-surface-container-high text-outline hover:bg-surface-container transition">Back</a>
            </div>
        </form>
    </div>
</div>
@endsection