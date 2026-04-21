@extends('admin.layouts.app')

@section('page_title', 'Tags')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
    <div>
        <h2 class="text-base font-bold text-on-surface">Tags</h2>
        <p class="text-xs text-outline mt-0.5">{{ $tags->count() }} ລາຍການ</p>
    </div>
    <a href="{{ route('admin.tags.create') }}"
         class="inline-flex items-center gap-2 text-sm font-semibold text-white primary-gradient px-4 py-2 rounded-lg hover:opacity-90 transition-opacity whitespace-nowrap">
        <i class="fas fa-plus text-xs"></i> ສ້າງ Tag ໃໝ່
    </a>
</div>
@if(session('success'))
    <div class="alert alert-success mb-4">{{ session('success') }}</div>
@endif
<div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-surface-container-high bg-surface-container-low text-left">
                    <th class="px-4 sm:px-5 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide">ID</th>
                    <th class="px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide">ຊື່ (Lao)</th>
                    <th class="px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide">ຊື່ (EN)</th>
                    <th class="px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide">ຊື່ (ZH)</th>
                    <th class="px-4 sm:px-5 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide text-right">ຈັດການ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-surface-container-high">
                @forelse($tags as $tag)
                    <tr class="hover:bg-surface-container-low transition-colors group">
                        <td class="px-4 sm:px-5 py-3">{{ $tag->id }}</td>
                        <td class="px-4 py-3">{{ $tag->name_lo }}</td>
                        <td class="px-4 py-3">{{ $tag->name_en }}</td>
                        <td class="px-4 py-3">{{ $tag->name_zh }}</td>
                        <td class="px-4 sm:px-5 py-3 text-right">
                            <a href="{{ route('admin.tags.show', $tag) }}" class="inline-flex items-center gap-1 text-xs font-semibold text-info-700 hover:underline mr-2"><i class="fas fa-eye"></i>View</a>
                            <a href="{{ route('admin.tags.edit', $tag) }}" class="inline-flex items-center gap-1 text-xs font-semibold text-warning-700 hover:underline mr-2"><i class="fas fa-edit"></i>Edit</a>
                            <form action="{{ route('admin.tags.destroy', $tag) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center gap-1 text-xs font-semibold text-danger-700 hover:underline"><i class="fas fa-trash"></i>Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-outline py-8">ບໍ່ມີຂໍ້ມູນ</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection