@extends('admin.layouts.app')

@section('page_title', 'Tags')

@section('content')

<div class="grid grid-cols-1 xl:grid-cols-3 gap-5">

  {{-- ═══ LEFT: Tag list ═══ --}}
  <div class="xl:col-span-2">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-4">
      <div>
        <h2 class="text-base font-bold text-on-surface">ທຸກ Tags</h2>
        <p class="text-xs text-outline mt-0.5">{{ $tags->count() }} ລາຍການ</p>
      </div>
      <a href="{{ route('admin.tags.create') }}"
         class="inline-flex items-center gap-2 text-sm font-semibold text-white primary-gradient px-4 py-2 rounded-lg hover:opacity-90 transition-opacity sm:hidden">
        <i class="fas fa-plus text-xs"></i> ເພີ່ມ
      </a>
    </div>

    <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead>
            <tr class="border-b border-surface-container-high bg-surface-container-low text-left">
              <th class="px-5 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide">ຊື່ tag</th>
              <th class="px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide hidden sm:table-cell">Slug</th>
              <th class="px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide text-center">ຂ່າວ</th>
              <th class="px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide text-center hidden md:table-cell">ກິດຈະກໍາ</th>
              <th class="px-5 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide text-right">ຈັດການ</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-surface-container-high">
            @forelse($tags as $tag)
              <tr class="hover:bg-surface-container-low transition-colors">

                {{-- Names --}}
                <td class="px-5 py-3">
                  <p class="font-semibold text-on-surface">{{ $tag->name_lo }}</p>
                  <div class="flex items-center gap-2 mt-0.5 flex-wrap">
                    @if($tag->name_en)
                      <span class="text-xs text-outline">{{ $tag->name_en }}</span>
                    @endif
                    @if($tag->name_zh)
                      <span class="text-xs text-outline border-l border-surface-container-high pl-2">{{ $tag->name_zh }}</span>
                    @endif
                  </div>
                </td>

                {{-- Slug --}}
                <td class="px-4 py-3 hidden sm:table-cell">
                  <span class="text-xs font-mono text-on-surface-variant bg-surface-container-low px-2 py-0.5 rounded">
                    {{ $tag->slug }}
                  </span>
                </td>

                {{-- News count --}}
                <td class="px-4 py-3 text-center">
                  @if($tag->news_count > 0)
                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-blue-100 text-blue-700 text-xs font-bold">
                      {{ $tag->news_count }}
                    </span>
                  @else
                    <span class="text-outline text-xs">0</span>
                  @endif
                </td>

                {{-- Events count --}}
                <td class="px-4 py-3 text-center hidden md:table-cell">
                  @if($tag->events_count > 0)
                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-green-100 text-green-700 text-xs font-bold">
                      {{ $tag->events_count }}
                    </span>
                  @else
                    <span class="text-outline text-xs">0</span>
                  @endif
                </td>

                {{-- Actions --}}
                <td class="px-5 py-3 text-right">
                  <div class="inline-flex items-center gap-1">
                    <a href="{{ route('admin.tags.edit', $tag) }}"
                       class="p-1.5 text-outline hover:text-primary hover:bg-surface-container rounded-lg transition-colors" title="ແກ້ໄຂ">
                      <i class="fas fa-pen text-xs"></i>
                    </a>
                    <form method="POST" action="{{ route('admin.tags.destroy', $tag) }}"
                          onsubmit="return confirm('ລົບ tag «{{ addslashes($tag->name_lo) }}» ແທ້ບໍ?\nTag ຈະຖືກລົບອອກຈາກຂ່າວ ແລະ ກິດຈະກໍາທັງໝົດ.')">
                      @csrf @method('DELETE')
                      <button type="submit"
                              class="p-1.5 text-outline hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors" title="ລົບ">
                        <i class="fas fa-trash text-xs"></i>
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="px-5 py-14 text-center text-sm text-outline">
                  <i class="fas fa-tags text-4xl mb-3 block opacity-20"></i>
                  ຍັງບໍ່ມີ tag — ສ້າງ tag ທໍາອິດທາງຂວາ
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  {{-- ═══ RIGHT: Quick-add form ═══ --}}
  <div class="hidden xl:block">
    <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] overflow-hidden sticky top-24">
      <div class="px-5 py-4 border-b border-surface-container-high">
        <h3 class="text-sm font-bold text-on-surface">ເພີ່ມ Tag ໃໝ່</h3>
      </div>
      <form method="POST" action="{{ route('admin.tags.store') }}" class="p-5 space-y-4">
        @csrf

        @if($errors->any())
          <div class="flex items-start gap-2 px-3 py-2.5 bg-red-50 border border-red-100 text-red-600 rounded-lg text-xs">
            <i class="fas fa-exclamation-circle mt-0.5 flex-shrink-0"></i>
            <span>{{ $errors->first() }}</span>
          </div>
        @endif

        <div>
          <label class="block text-xs font-semibold text-on-surface-variant mb-1.5">
            ຊື່ (ລາວ) <span class="text-red-500">*</span>
          </label>
          <input name="name_lo" type="text" value="{{ old('name_lo') }}" required
                 autofocus placeholder="ຊື່ tag ພາສາລາວ"
                 class="w-full px-3 py-2.5 text-sm bg-surface-container-low border border-surface-container-high rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/30 transition-all @error('name_lo') border-red-300 @enderror" />
        </div>

        <div>
          <label class="block text-xs font-semibold text-on-surface-variant mb-1.5">
            ຊື່ (English)
            <span class="text-[10px] font-normal text-outline ml-1">ໃຊ້ສ້າງ slug</span>
          </label>
          <input name="name_en" type="text" value="{{ old('name_en') }}"
                 placeholder="Tag name in English"
                 class="w-full px-3 py-2.5 text-sm bg-surface-container-low border border-surface-container-high rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/30 transition-all" />
        </div>

        <div>
          <label class="block text-xs font-semibold text-on-surface-variant mb-1.5">ຊື່ (中文)</label>
          <input name="name_zh" type="text" value="{{ old('name_zh') }}"
                 placeholder="标签名称"
                 class="w-full px-3 py-2.5 text-sm bg-surface-container-low border border-surface-container-high rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/30 transition-all" />
        </div>

        <button type="submit"
                class="w-full flex items-center justify-center gap-2 text-sm font-semibold text-white primary-gradient px-4 py-2.5 rounded-lg hover:opacity-90 active:scale-[0.98] transition-all">
          <i class="fas fa-plus text-xs"></i> ສ້າງ Tag
        </button>
      </form>
    </div>
  </div>

</div>

@endsection
