@extends('admin.layouts.app')

@section('page_title', 'Hero Slides')

@section('content')

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
  <div>
    <h2 class="text-base font-bold text-on-surface">ຈັດການ Hero Slides</h2>
    <p class="text-xs text-outline mt-0.5">{{ $slides->total() }} ລາຍການ · ສະແດງໃນໜ້າຫຼັກ</p>
  </div>
  <a href="{{ route('admin.slides.create') }}"
     class="inline-flex items-center gap-2 text-sm font-semibold text-white primary-gradient px-4 py-2 rounded-lg hover:opacity-90 transition whitespace-nowrap">
    <i class="fas fa-plus text-xs"></i> ເພີ່ມ Slide ໃໝ່
  </a>
</div>

{{-- ຕາຕະລາງ --}}
<div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] overflow-hidden">
  <div class="overflow-x-auto">
    <table class="w-full text-sm">
      <thead>
        <tr class="border-b border-surface-container-high bg-surface-container-low text-left">
          <th class="px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide w-10">ລ/ດ</th>
          <th class="px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide w-20">ຮູບ</th>
          <th class="px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide">ຫົວຂໍ້ / Tag</th>
          <th class="px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide hidden md:table-cell">ປຸ່ມ</th>
          <th class="px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide text-center w-24">ສະຖານະ</th>
          <th class="px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide text-right">ຈັດການ</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-surface-container-high">
        @forelse($slides as $slide)
        <tr class="hover:bg-surface-container-low transition-colors">
          <td class="px-4 py-3 text-center">
            <span class="inline-flex items-center justify-center w-6 h-6 rounded bg-surface-container text-xs font-bold text-outline">
              {{ $slide->sort_order }}
            </span>
          </td>
          <td class="px-4 py-3">
            <img src="{{ Str::startsWith($slide->image_url, 'http') ? $slide->image_url : asset($slide->image_url) }}"
                 class="w-16 h-10 object-cover rounded-lg border border-surface-container-high bg-surface-container"
                 alt="slide"
                 onerror="this.src=''; this.className='w-16 h-10 rounded-lg bg-surface-container-high flex items-center justify-center';">
          </td>
          <td class="px-4 py-3">
            @if($slide->tag_lo)
              <span class="inline-block px-1.5 py-0.5 text-[10px] font-bold bg-primary/10 text-primary rounded mb-1">
                {{ $slide->tag_lo }}
              </span>
            @endif
            <p class="font-semibold text-on-surface leading-tight">{{ $slide->title_lo }}</p>
            @if($slide->subtitle_lo)
              <p class="text-xs text-outline mt-0.5 truncate max-w-xs">{{ Str::limit($slide->subtitle_lo, 60) }}</p>
            @endif
          </td>
          <td class="px-4 py-3 hidden md:table-cell">
            <div class="flex flex-col gap-1">
              @if($slide->btn1_text_lo)
                <span class="inline-flex items-center gap-1 text-xs text-on-surface-variant">
                  <i class="fas fa-mouse-pointer text-[10px] text-primary"></i>
                  {{ $slide->btn1_text_lo }}
                </span>
              @endif
              @if($slide->btn2_text_lo)
                <span class="inline-flex items-center gap-1 text-xs text-outline">
                  <i class="fas fa-mouse-pointer text-[10px]"></i>
                  {{ $slide->btn2_text_lo }}
                </span>
              @endif
              @if(!$slide->btn1_text_lo && !$slide->btn2_text_lo)
                <span class="text-xs text-outline">—</span>
              @endif
            </div>
          </td>
          <td class="px-4 py-3 text-center">
            @if($slide->is_active)
              <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">
                <i class="fas fa-circle text-[6px]"></i> ໃຊ້ງານ
              </span>
            @else
              <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-500">
                <i class="fas fa-circle text-[6px]"></i> ປິດ
              </span>
            @endif
          </td>
          <td class="px-4 py-3 text-right">
            <div class="flex items-center justify-end gap-2">
              <a href="{{ route('admin.slides.show', $slide) }}"
                 class="inline-flex items-center gap-1 text-xs font-semibold text-blue-600 hover:underline">
                <i class="fas fa-eye"></i><span class="hidden sm:inline">ລາຍລະອຽດ</span>
              </a>
              <a href="{{ route('admin.slides.edit', $slide) }}"
                 class="inline-flex items-center gap-1 text-xs font-semibold text-yellow-600 hover:underline">
                <i class="fas fa-edit"></i><span class="hidden sm:inline">ແກ້ໄຂ</span>
              </a>
              <form action="{{ route('admin.slides.destroy', $slide) }}" method="POST" class="inline-block"
                    onsubmit="return confirm('ທ່ານຕ້ອງການລຶບ Slide «{{ $slide->title_lo }}» ແທ້ບໍ?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center gap-1 text-xs font-semibold text-red-600 hover:underline">
                  <i class="fas fa-trash"></i><span class="hidden sm:inline">ລຶບ</span>
                </button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="6" class="text-center text-outline py-12">
            <i class="fas fa-images text-3xl mb-2 block opacity-30"></i>
            ຍັງບໍ່ມີ Slide
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($slides->hasPages())
    <div class="px-4 py-3 border-t border-surface-container-high">
      {{ $slides->links() }}
    </div>
  @endif
</div>

@endsection
