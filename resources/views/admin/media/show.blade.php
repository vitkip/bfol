@extends('admin.layouts.app')

@section('page_title', 'ລາຍລະອຽດສື່ທຳ')

@section('content')
@php
  $typeMap = [
    'image'    => ['label'=>'ຮູບພາບ',  'icon'=>'fa-image',    'badge'=>'bg-blue-100 text-blue-700'],
    'video'    => ['label'=>'ວິດີໂອ',  'icon'=>'fa-video',    'badge'=>'bg-purple-100 text-purple-700'],
    'audio'    => ['label'=>'ສຽງ',      'icon'=>'fa-music',    'badge'=>'bg-green-100 text-green-700'],
    'document' => ['label'=>'ເອກະສານ', 'icon'=>'fa-file-alt', 'badge'=>'bg-orange-100 text-orange-700'],
  ];
  $platIcon = ['local'=>'fa-server','youtube'=>'fa-youtube','facebook'=>'fa-facebook','soundcloud'=>'fa-soundcloud','other'=>'fa-link'];
  $platColor= ['local'=>'text-gray-500','youtube'=>'text-red-500','facebook'=>'text-blue-600','soundcloud'=>'text-orange-500','other'=>'text-outline'];
  $ti = $typeMap[$medium->type] ?? ['label'=>$medium->type,'icon'=>'fa-file','badge'=>'bg-gray-100 text-gray-600'];

  $thumb = $medium->thumbnail_url
      ? (Str::startsWith($medium->thumbnail_url,'http') ? $medium->thumbnail_url : asset($medium->thumbnail_url))
      : null;
  $fileUrl = $medium->file_url
      ? (Str::startsWith($medium->file_url,'http') ? $medium->file_url : asset($medium->file_url))
      : $medium->external_url;
@endphp

<div class="max-w-4xl mx-auto">

  {{-- Breadcrumb --}}
  <div class="flex items-center gap-2 text-xs text-outline mb-4">
    <a href="{{ route('admin.media.index') }}" class="hover:text-primary">ສື່ທຳ</a>
    <i class="fas fa-chevron-right text-[10px]"></i>
    <span class="truncate max-w-[240px]">{{ $medium->title_lo }}</span>
  </div>

  {{-- Action bar --}}
  <div class="flex items-center justify-between mb-5">
    <div class="flex items-center gap-2 flex-wrap">
      <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-full {{ $ti['badge'] }}">
        <i class="fas {{ $ti['icon'] }} text-[10px]"></i> {{ $ti['label'] }}
      </span>
      <span class="inline-flex items-center gap-1 text-xs {{ $platColor[$medium->platform] ?? 'text-outline' }}">
        <i class="fab {{ $platIcon[$medium->platform] ?? 'fa-link' }} text-[11px]"></i>
        {{ ucfirst($medium->platform) }}
      </span>
      @if($medium->is_featured)
        <span class="inline-flex items-center gap-1 text-xs text-yellow-600 font-semibold">
          <i class="fas fa-star text-[10px]"></i> ແນະນຳ
        </span>
      @endif
      @if($medium->published_at && $medium->published_at <= now())
        <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-semibold rounded-full bg-green-100 text-green-700">
          <i class="fas fa-circle text-[6px]"></i> ເຜີຍແຜ່
        </span>
      @elseif($medium->published_at)
        <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-semibold rounded-full bg-blue-100 text-blue-600">
          <i class="fas fa-clock text-[9px]"></i> ກຳນົດ
        </span>
      @else
        <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-semibold rounded-full bg-gray-100 text-gray-500">
          <i class="fas fa-circle text-[6px]"></i> ຮ່າງ
        </span>
      @endif
    </div>
    <div class="flex gap-2">
      <a href="{{ route('admin.media.edit', $medium) }}"
         class="inline-flex items-center gap-2 primary-gradient text-white font-semibold px-4 py-2 rounded-lg hover:opacity-90 transition text-sm">
        <i class="fas fa-edit text-xs"></i> ແກ້ໄຂ
      </a>
      <form action="{{ route('admin.media.destroy', $medium) }}" method="POST"
            onsubmit="return confirm('ທ່ານຕ້ອງການລຶບ «{{ $medium->title_lo }}» ແທ້ບໍ?')">
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

      {{-- Media preview --}}
      <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] overflow-hidden">
        @if($medium->type === 'image' && $fileUrl)
          <img src="{{ $fileUrl }}" class="w-full max-h-72 object-cover" alt="{{ $medium->title_lo }}"
               onerror="this.parentElement.innerHTML='<div class=\'h-40 flex items-center justify-center text-outline\'><i class=\'fas fa-image text-4xl opacity-30\'></i></div>'">

        @elseif($medium->type === 'video')
          @if($medium->platform === 'youtube' && $medium->external_url)
            @php
              preg_match('/(?:v=|youtu\.be\/)([A-Za-z0-9_\-]+)/', $medium->external_url, $m);
              $ytId = $m[1] ?? '';
            @endphp
            @if($ytId)
              <div class="relative" style="padding-bottom:56.25%;height:0;overflow:hidden;">
                <iframe src="https://www.youtube.com/embed/{{ $ytId }}"
                        class="absolute inset-0 w-full h-full" frameborder="0" allowfullscreen></iframe>
              </div>
            @endif
          @elseif($medium->platform === 'facebook' && $medium->external_url)
            <div class="p-5 text-center text-outline">
              <i class="fab fa-facebook text-4xl text-blue-600 mb-2 block"></i>
              <a href="{{ $medium->external_url }}" target="_blank" class="text-sm text-primary hover:underline">
                ເບິ່ງວິດີໂອໃນ Facebook <i class="fas fa-external-link-alt text-xs ml-1"></i>
              </a>
            </div>
          @elseif($fileUrl)
            <video controls class="w-full max-h-72 bg-black">
              <source src="{{ $fileUrl }}" type="{{ $medium->mime_type ?? 'video/mp4' }}">
            </video>
          @endif

        @elseif($medium->type === 'audio')
          @if($medium->platform === 'soundcloud' && $medium->external_url)
            <div class="p-5 text-center text-outline">
              <i class="fab fa-soundcloud text-4xl text-orange-500 mb-2 block"></i>
              <a href="{{ $medium->external_url }}" target="_blank" class="text-sm text-primary hover:underline">
                ຟັງໃນ SoundCloud <i class="fas fa-external-link-alt text-xs ml-1"></i>
              </a>
            </div>
          @elseif($fileUrl)
            <div class="p-5">
              <audio controls class="w-full">
                <source src="{{ $fileUrl }}" type="{{ $medium->mime_type ?? 'audio/mpeg' }}">
              </audio>
            </div>
          @endif

        @elseif($medium->type === 'document')
          <div class="p-6 flex items-center gap-4">
            @if($thumb)
              <img src="{{ $thumb }}" class="w-20 h-24 object-cover rounded border border-surface-container-high flex-shrink-0" alt="">
            @else
              <div class="w-16 h-20 bg-surface-container rounded flex items-center justify-center flex-shrink-0">
                <i class="fas fa-file-pdf text-3xl text-orange-400"></i>
              </div>
            @endif
            <div>
              <p class="font-semibold text-on-surface">{{ $medium->title_lo }}</p>
              @if($medium->file_size_kb)
                <p class="text-xs text-outline mt-1">
                  {{ $medium->file_size_kb >= 1024 ? round($medium->file_size_kb/1024,1).' MB' : $medium->file_size_kb.' KB' }}
                </p>
              @endif
              @if($fileUrl)
                <a href="{{ $fileUrl }}" target="_blank" download
                   class="inline-flex items-center gap-2 mt-3 px-4 py-2 text-sm font-semibold primary-gradient text-white rounded-lg hover:opacity-90 transition">
                  <i class="fas fa-download text-xs"></i> ດາວໂຫຼດ
                </a>
              @endif
            </div>
          </div>
        @endif
      </div>

      {{-- ຊື່ + ລາຍລະອຽດ --}}
      <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5"
           x-data="{ tab: 'lo' }">
        <h3 class="font-bold text-sm text-on-surface mb-3 flex items-center gap-2">
          <i class="fas fa-heading text-primary text-xs"></i> ຊື່ ແລະ ລາຍລະອຽດ
        </h3>
        <div class="flex gap-1 border-b border-surface-container-high mb-3">
          @foreach(['lo'=>'ລາວ','en'=>'English','zh'=>'中文'] as $k=>$lbl)
          <button @click="tab='{{ $k }}'"
                  :class="tab==='{{ $k }}' ? 'border-primary text-primary font-semibold' : 'border-transparent text-outline'"
                  class="px-3 py-1.5 text-xs border-b-2 transition-colors -mb-px">{{ $lbl }}</button>
          @endforeach
        </div>
        @foreach(['lo','en','zh'] as $k)
        <div x-show="tab==='{{ $k }}'" class="space-y-2">
          <p class="font-semibold text-on-surface">{{ $medium->{'title_'.$k} ?: '—' }}</p>
          <p class="text-sm text-on-surface-variant">{{ $medium->{'description_'.$k} ?: '—' }}</p>
        </div>
        @endforeach
      </div>

    </div>

    {{-- ── ຖັນຂວາ ── --}}
    <div class="space-y-5">

      {{-- ສະຖິຕິ --}}
      <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
        <h3 class="font-bold text-sm text-on-surface mb-3 flex items-center gap-2">
          <i class="fas fa-chart-bar text-primary text-xs"></i> ສະຖິຕິ
        </h3>
        <div class="grid grid-cols-2 gap-3">
          <div class="text-center p-3 rounded-lg bg-surface-container-low">
            <p class="text-xl font-bold text-on-surface">{{ number_format($medium->view_count) }}</p>
            <p class="text-xs text-outline">ການເບິ່ງ</p>
          </div>
          <div class="text-center p-3 rounded-lg bg-surface-container-low">
            <p class="text-xl font-bold text-on-surface">{{ number_format($medium->download_count) }}</p>
            <p class="text-xs text-outline">ດາວໂຫຼດ</p>
          </div>
        </div>
      </div>

      {{-- ຂໍ້ມູນລະບົບ --}}
      <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
        <h3 class="font-bold text-sm text-on-surface mb-3 flex items-center gap-2">
          <i class="fas fa-info-circle text-primary text-xs"></i> ຂໍ້ມູນລະບົບ
        </h3>
        <div class="space-y-3 text-xs">
          <div><p class="text-outline mb-0.5">ລະຫັດ</p><p class="font-semibold">{{ $medium->id }}</p></div>
          @if($medium->category)
          <div><p class="text-outline mb-0.5">ໝວດໝູ່</p><p class="font-semibold">{{ $medium->category->name_lo }}</p></div>
          @endif
          @if($medium->event)
          <div><p class="text-outline mb-0.5">ກິດຈະກຳ</p><p class="font-semibold">{{ $medium->event->title_lo }}</p></div>
          @endif
          @if($medium->duration_sec)
          <div>
            <p class="text-outline mb-0.5">ໄລຍະເວລາ</p>
            <p class="font-semibold">
              @php $d = $medium->duration_sec; @endphp
              {{ floor($d/3600) > 0 ? floor($d/3600).'h ' : '' }}{{ floor(($d%3600)/60) }}m {{ $d%60 }}s
            </p>
          </div>
          @endif
          @if($medium->file_size_kb)
          <div>
            <p class="text-outline mb-0.5">ຂະໜາດໄຟລ໌</p>
            <p class="font-semibold">{{ $medium->file_size_kb >= 1024 ? round($medium->file_size_kb/1024,1).' MB' : $medium->file_size_kb.' KB' }}</p>
          </div>
          @endif
          @if($medium->mime_type)
          <div><p class="text-outline mb-0.5">MIME Type</p><code class="font-mono text-[10px] bg-surface-container px-1.5 py-0.5 rounded">{{ $medium->mime_type }}</code></div>
          @endif
          @if($medium->author)
          <div><p class="text-outline mb-0.5">ຜູ້ອັບໂຫຼດ</p><p class="font-semibold">{{ $medium->author->full_name_lo ?? $medium->author->name }}</p></div>
          @endif
          @if($medium->published_at)
          <div><p class="text-outline mb-0.5">ເຜີຍແຜ່ເມື່ອ</p><p class="text-on-surface-variant">{{ $medium->published_at->format('d/m/Y H:i') }}</p></div>
          @endif
          <div><p class="text-outline mb-0.5">ສ້າງເມື່ອ</p><p class="text-on-surface-variant">{{ $medium->created_at->format('d/m/Y H:i') }}</p></div>
          <div><p class="text-outline mb-0.5">ແກ້ໄຂລ່າສຸດ</p><p class="text-on-surface-variant">{{ $medium->updated_at->format('d/m/Y H:i') }}</p></div>
        </div>
      </div>

      @if($fileUrl)
      {{-- Link --}}
      <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
        <h3 class="font-bold text-sm text-on-surface mb-3 flex items-center gap-2">
          <i class="fas fa-link text-primary text-xs"></i> URL
        </h3>
        <a href="{{ $fileUrl }}" target="_blank"
           class="text-xs text-primary hover:underline break-all">
          {{ $fileUrl }} <i class="fas fa-external-link-alt text-[9px] ml-0.5"></i>
        </a>
      </div>
      @endif

      {{-- ກັບຄືນ --}}
      <a href="{{ route('admin.media.index') }}"
         class="flex items-center justify-center gap-2 w-full px-4 py-2.5 rounded-lg border border-surface-container-high text-on-surface-variant hover:bg-surface-container transition text-sm font-semibold">
        <i class="fas fa-arrow-left text-xs"></i> ກັບຄືນລາຍການ
      </a>

    </div>
  </div>
</div>
@endsection
