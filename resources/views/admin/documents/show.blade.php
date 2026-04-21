@extends('admin.layouts.app')

@section('page_title', 'ລາຍລະອຽດເອກະສານ')

@section('content')
@php
  $typeIcon = ['PDF'=>['fa-file-pdf','text-red-500','bg-red-50','border-red-100'],'Word'=>['fa-file-word','text-blue-600','bg-blue-50','border-blue-100'],'Excel'=>['fa-file-excel','text-green-600','bg-green-50','border-green-100'],'PPT'=>['fa-file-powerpoint','text-orange-500','bg-orange-50','border-orange-100'],'Text'=>['fa-file-alt','text-gray-500','bg-gray-50','border-gray-100'],'ZIP'=>['fa-file-archive','text-yellow-600','bg-yellow-50','border-yellow-100'],'RAR'=>['fa-file-archive','text-yellow-700','bg-yellow-50','border-yellow-100']];
  [$ico,$col,$bg,$bdr] = $typeIcon[$document->file_type] ?? ['fa-file','text-outline','bg-surface-container','border-surface-container-high'];
@endphp

<div class="max-w-4xl mx-auto">

  {{-- Breadcrumb --}}
  <div class="flex items-center gap-2 text-xs text-outline mb-4">
    <a href="{{ route('admin.documents.index') }}" class="hover:text-primary">ເອກະສານ</a>
    <i class="fas fa-chevron-right text-[10px]"></i>
    <span class="truncate max-w-[240px]">{{ $document->title_lo }}</span>
  </div>

  {{-- Action bar --}}
  <div class="flex items-center justify-between mb-5 flex-wrap gap-3">
    <div class="flex items-center gap-2 flex-wrap">
      {{-- ປະເພດ --}}
      @if($document->file_type)
        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-bold rounded-full {{ $bg }} {{ $col }} border {{ $bdr }}">
          <i class="fas {{ $ico }} text-[10px]"></i> {{ $document->file_type }}
        </span>
      @endif
      {{-- ການເຂົ້າເຖິງ --}}
      @if($document->is_public)
        <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-700">
          <i class="fas fa-globe text-[9px]"></i> ສາທາລະນະ
        </span>
      @else
        <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-500">
          <i class="fas fa-lock text-[9px]"></i> ສ່ວນຕົວ
        </span>
      @endif
      {{-- ສະຖານະ --}}
      @if($document->published_at && $document->published_at <= now())
        <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">
          <i class="fas fa-circle text-[6px]"></i> ເຜີຍແຜ່
        </span>
      @elseif($document->published_at)
        <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-full bg-amber-100 text-amber-700">
          <i class="fas fa-clock text-[9px]"></i> ກຳນົດ {{ $document->published_at->format('d/m/Y H:i') }}
        </span>
      @else
        <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-500">
          <i class="fas fa-circle text-[6px]"></i> ຮ່າງ
        </span>
      @endif
    </div>
    <div class="flex gap-2">
      <a href="{{ route('admin.documents.download', $document) }}"
         class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
        <i class="fas fa-download text-xs"></i> ດາວໂຫຼດ
      </a>
      <a href="{{ route('admin.documents.edit', $document) }}"
         class="inline-flex items-center gap-2 primary-gradient text-white font-semibold px-4 py-2 rounded-lg hover:opacity-90 transition text-sm">
        <i class="fas fa-edit text-xs"></i> ແກ້ໄຂ
      </a>
      <form action="{{ route('admin.documents.destroy', $document) }}" method="POST"
            onsubmit="return confirm('ທ່ານຕ້ອງການລຶບ «{{ $document->title_lo }}» ແທ້ບໍ?\n\nໄຟລ໌ຈະຖືກລຶບຖາວອນ!')">
        @csrf @method('DELETE')
        <button class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-red-200 text-red-600 hover:bg-red-50 transition text-sm font-semibold">
          <i class="fas fa-trash text-xs"></i> ລຶບ
        </button>
      </form>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

    {{-- ── ຖັນຊ້າຍ ── --}}
    <div class="lg:col-span-2 space-y-5">

      {{-- ບັດເອກະສານ --}}
      <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-6">
        <div class="flex items-start gap-5">
          <div class="w-20 h-24 rounded-xl {{ $bg }} border {{ $bdr }} flex items-center justify-center flex-shrink-0">
            <i class="fas {{ $ico }} {{ $col }} text-4xl"></i>
          </div>
          <div class="flex-1 min-w-0">
            <h2 class="text-lg font-bold text-on-surface leading-tight">{{ $document->title_lo }}</h2>
            @if($document->title_en)
              <p class="text-sm text-outline mt-0.5">{{ $document->title_en }}</p>
            @endif
            @if($document->title_zh)
              <p class="text-sm text-outline">{{ $document->title_zh }}</p>
            @endif
            <div class="flex flex-wrap items-center gap-3 mt-3 text-xs text-outline">
              @if($document->file_type)
                <span class="font-bold {{ $col }} uppercase">{{ $document->file_type }}</span>
              @endif
              @if($document->file_size_kb)
                <span>·</span>
                <span>{{ $document->file_size_kb >= 1024 ? round($document->file_size_kb/1024,1).' MB' : $document->file_size_kb.' KB' }}</span>
              @endif
              @if($document->category)
                <span>·</span>
                <span>{{ $document->category->name_lo }}</span>
              @endif
            </div>
          </div>
        </div>
      </div>

      {{-- ລາຍລະອຽດ --}}
      @if($document->description_lo || $document->description_en || $document->description_zh)
      <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5"
           x-data="{ tab: 'lo' }">
        <h3 class="font-bold text-sm text-on-surface mb-3 flex items-center gap-2">
          <i class="fas fa-align-left text-primary text-xs"></i> ລາຍລະອຽດ
        </h3>
        <div class="flex gap-1 border-b border-surface-container-high mb-3">
          @foreach(['lo'=>'ລາວ','en'=>'English','zh'=>'中文'] as $k=>$lbl)
            @if($document->{'description_'.$k})
            <button @click="tab='{{ $k }}'"
                    :class="tab==='{{ $k }}' ? 'border-primary text-primary font-semibold' : 'border-transparent text-outline'"
                    class="px-3 py-1.5 text-xs border-b-2 transition-colors -mb-px">{{ $lbl }}</button>
            @endif
          @endforeach
        </div>
        @foreach(['lo','en','zh'] as $k)
          @if($document->{'description_'.$k})
          <div x-show="tab==='{{ $k }}'">
            <p class="text-sm text-on-surface-variant leading-relaxed">{{ $document->{'description_'.$k} }}</p>
          </div>
          @endif
        @endforeach
      </div>
      @endif

      {{-- PDF Preview (inline) --}}
      @if($document->file_type === 'PDF')
      <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] overflow-hidden">
        <div class="flex items-center justify-between px-5 py-3 border-b border-surface-container-high">
          <h3 class="font-bold text-sm text-on-surface flex items-center gap-2">
            <i class="fas fa-eye text-primary text-xs"></i> ດູຕົວຢ່າງ PDF
          </h3>
          <a href="{{ asset($document->file_url) }}" target="_blank"
             class="text-xs text-primary hover:underline flex items-center gap-1">
            ເປີດໃໝ່ <i class="fas fa-external-link-alt text-[9px]"></i>
          </a>
        </div>
        <iframe src="{{ asset($document->file_url) }}"
                class="w-full" style="height:600px;" frameborder="0">
          <p class="p-4 text-sm text-outline">Browser ບໍ່ຮອງຮັບ PDF viewer</p>
        </iframe>
      </div>
      @endif

    </div>

    {{-- ── ຖັນຂວາ ── --}}
    <div class="space-y-5">

      {{-- ດາວໂຫຼດ --}}
      <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
        <div class="text-center">
          <div class="w-16 h-16 mx-auto rounded-full {{ $bg }} flex items-center justify-center mb-3">
            <i class="fas {{ $ico }} {{ $col }} text-2xl"></i>
          </div>
          <p class="text-2xl font-bold text-on-surface">{{ number_format($document->download_count) }}</p>
          <p class="text-xs text-outline mb-4">ຈຳນວນການດາວໂຫຼດ</p>
          <a href="{{ route('admin.documents.download', $document) }}"
             class="flex items-center justify-center gap-2 w-full py-2.5 text-sm font-semibold bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
            <i class="fas fa-download text-xs"></i> ດາວໂຫຼດໄຟລ໌
          </a>
          @if($document->file_url)
            <a href="{{ asset($document->file_url) }}" target="_blank"
               class="flex items-center justify-center gap-1 w-full mt-2 text-xs text-outline hover:text-primary transition-colors">
              <i class="fas fa-external-link-alt text-[9px]"></i> ເປີດໃນ Browser
            </a>
          @endif
        </div>
      </div>

      {{-- ຂໍ້ມູນລະບົບ --}}
      <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
        <h3 class="font-bold text-sm text-on-surface mb-3 flex items-center gap-2">
          <i class="fas fa-info-circle text-primary text-xs"></i> ຂໍ້ມູນລະບົບ
        </h3>
        <div class="space-y-3 text-xs">
          <div>
            <p class="text-outline mb-0.5">ລະຫັດ</p>
            <p class="font-semibold text-on-surface">{{ $document->id }}</p>
          </div>
          @if($document->file_type)
          <div>
            <p class="text-outline mb-0.5">ປະເພດໄຟລ໌</p>
            <span class="inline-flex items-center gap-1 font-bold {{ $col }}">
              <i class="fas {{ $ico }} text-[10px]"></i> {{ $document->file_type }}
            </span>
          </div>
          @endif
          @if($document->file_size_kb)
          <div>
            <p class="text-outline mb-0.5">ຂະໜາດ</p>
            <p class="font-semibold">{{ $document->file_size_kb >= 1024 ? round($document->file_size_kb/1024,1).' MB' : $document->file_size_kb.' KB' }}</p>
          </div>
          @endif
          @if($document->category)
          <div>
            <p class="text-outline mb-0.5">ໝວດໝູ່</p>
            <p class="font-semibold">{{ $document->category->name_lo }}</p>
          </div>
          @endif
          @if($document->author)
          <div>
            <p class="text-outline mb-0.5">ຜູ້ອັບໂຫຼດ</p>
            <p class="font-semibold">{{ $document->author->full_name_lo ?? $document->author->name }}</p>
          </div>
          @endif
          @if($document->published_at)
          <div>
            <p class="text-outline mb-0.5">ເຜີຍແຜ່ເມື່ອ</p>
            <p class="text-on-surface-variant">{{ $document->published_at->format('d/m/Y H:i') }}</p>
          </div>
          @endif
          <div>
            <p class="text-outline mb-0.5">ສ້າງເມື່ອ</p>
            <p class="text-on-surface-variant">{{ $document->created_at->format('d/m/Y H:i') }}</p>
          </div>
          <div>
            <p class="text-outline mb-0.5">ແກ້ໄຂລ່າສຸດ</p>
            <p class="text-on-surface-variant">{{ $document->updated_at->format('d/m/Y H:i') }}</p>
          </div>
          <div>
            <p class="text-outline mb-0.5">URL ໄຟລ໌</p>
            <p class="font-mono text-[10px] break-all bg-surface-container px-2 py-1 rounded text-on-surface-variant">{{ $document->file_url }}</p>
          </div>
        </div>
      </div>

      {{-- ກັບຄືນ --}}
      <a href="{{ route('admin.documents.index') }}"
         class="flex items-center justify-center gap-2 w-full px-4 py-2.5 rounded-lg border border-surface-container-high text-on-surface-variant hover:bg-surface-container transition text-sm font-semibold">
        <i class="fas fa-arrow-left text-xs"></i> ກັບຄືນລາຍການ
      </a>
    </div>
  </div>
</div>
@endsection
