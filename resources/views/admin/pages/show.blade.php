@extends('admin.layouts.app')

@section('page_title', 'ລາຍລະອຽດໜ້າຂໍ້ມູນ')

@section('content')
<div class="max-w-4xl mx-auto">

  {{-- Breadcrumb --}}
  <div class="flex items-center gap-2 text-xs text-outline mb-4">
    <a href="{{ route('admin.pages.index') }}" class="hover:text-primary transition-colors">ໜ້າຂໍ້ມູນ</a>
    <i class="fas fa-chevron-right text-[10px]"></i>
    <span class="truncate max-w-[240px]">{{ $page->title_lo }}</span>
  </div>

  {{-- Action bar --}}
  <div class="flex items-center justify-between mb-5">
    <div class="flex items-center gap-2">
      @if($page->is_published)
        <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">
          <i class="fas fa-circle text-[6px]"></i> ເຜີຍແຜ່
        </span>
      @else
        <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-500">
          <i class="fas fa-circle text-[6px]"></i> ຮ່າງ
        </span>
      @endif
    </div>
    <div class="flex gap-2">
      <a href="{{ route('admin.pages.edit', $page) }}"
         class="inline-flex items-center gap-2 primary-gradient text-white font-semibold px-4 py-2 rounded-lg hover:opacity-90 transition text-sm">
        <i class="fas fa-edit text-xs"></i> ແກ້ໄຂ
      </a>
      <form action="{{ route('admin.pages.destroy', $page) }}" method="POST"
            onsubmit="return confirm('ທ່ານຕ້ອງການລຶບໜ້າ «{{ $page->title_lo }}» ແທ້ບໍ?')">
        @csrf
        @method('DELETE')
        <button type="submit"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-red-200 text-red-600 hover:bg-red-50 transition text-sm font-semibold">
          <i class="fas fa-trash text-xs"></i> ລຶບ
        </button>
      </form>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

    {{-- ── ຖັນຊ້າຍ: ຂໍ້ມູນຫຼັກ ── --}}
    <div class="lg:col-span-2 space-y-5">

      {{-- ຮູບປົກ --}}
      @if($page->thumbnail)
        <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] overflow-hidden">
          <img src="{{ asset('storage/' . $page->thumbnail) }}"
               class="w-full h-48 object-cover" alt="ຮູບປົກ">
        </div>
      @endif

      {{-- ຊື່ --}}
      <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
        <h3 class="font-bold text-sm text-on-surface mb-3 flex items-center gap-2">
          <i class="fas fa-heading text-primary text-xs"></i> ຊື່ໜ້າ
        </h3>
        <div class="space-y-2">
          <div class="flex items-start gap-3">
            <span class="text-xs bg-surface-container px-1.5 py-0.5 rounded font-mono text-outline w-6 text-center flex-shrink-0 mt-0.5">LO</span>
            <p class="font-semibold text-on-surface">{{ $page->title_lo }}</p>
          </div>
          @if($page->title_en)
          <div class="flex items-start gap-3">
            <span class="text-xs bg-surface-container px-1.5 py-0.5 rounded font-mono text-outline w-6 text-center flex-shrink-0 mt-0.5">EN</span>
            <p class="text-on-surface-variant">{{ $page->title_en }}</p>
          </div>
          @endif
          @if($page->title_zh)
          <div class="flex items-start gap-3">
            <span class="text-xs bg-surface-container px-1.5 py-0.5 rounded font-mono text-outline w-6 text-center flex-shrink-0 mt-0.5">ZH</span>
            <p class="text-on-surface-variant">{{ $page->title_zh }}</p>
          </div>
          @endif
        </div>
      </div>

      {{-- ເນື້ອໃນ --}}
      @if($page->content_lo || $page->content_en || $page->content_zh)
      <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
        <h3 class="font-bold text-sm text-on-surface mb-3 flex items-center gap-2">
          <i class="fas fa-align-left text-primary text-xs"></i> ເນື້ອໃນ
        </h3>
        <div x-data="{ tab: 'lo' }" class="space-y-3">
          {{-- Tab bar --}}
          <div class="flex gap-1 border-b border-surface-container-high">
            @foreach(['lo' => 'ລາວ', 'en' => 'EN', 'zh' => 'ZH'] as $key => $label)
              @if($page->{'content_' . $key})
              <button type="button" @click="tab = '{{ $key }}'"
                      :class="tab === '{{ $key }}' ? 'border-primary text-primary font-semibold' : 'border-transparent text-outline hover:text-on-surface'"
                      class="px-3 py-1.5 text-xs border-b-2 transition-colors -mb-px">
                {{ $label }}
              </button>
              @endif
            @endforeach
          </div>
          @foreach(['lo' => $page->content_lo, 'en' => $page->content_en, 'zh' => $page->content_zh] as $key => $content)
            @if($content)
            <div x-show="tab === '{{ $key }}'" class="prose prose-sm max-w-none">
              <pre class="whitespace-pre-wrap text-sm text-on-surface-variant font-sans bg-surface-container-low rounded-lg p-4">{{ $content }}</pre>
            </div>
            @endif
          @endforeach
        </div>
      </div>
      @endif

      {{-- SEO --}}
      @if($page->meta_title_lo || $page->meta_description)
      <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
        <h3 class="font-bold text-sm text-on-surface mb-3 flex items-center gap-2">
          <i class="fas fa-search text-primary text-xs"></i> SEO / Meta
        </h3>
        <div class="space-y-2 text-sm">
          @foreach(['lo' => 'ລາວ', 'en' => 'EN', 'zh' => 'ZH'] as $key => $label)
            @if($page->{'meta_title_' . $key})
            <div class="flex gap-3">
              <span class="text-xs text-outline w-20 flex-shrink-0 pt-0.5">Meta Title {{ $label }}</span>
              <span class="text-on-surface-variant">{{ $page->{'meta_title_' . $key} }}</span>
            </div>
            @endif
          @endforeach
          @if($page->meta_description)
          <div class="flex gap-3">
            <span class="text-xs text-outline w-20 flex-shrink-0 pt-0.5">Description</span>
            <span class="text-on-surface-variant">{{ $page->meta_description }}</span>
          </div>
          @endif
        </div>
      </div>
      @endif

    </div>

    {{-- ── ຖັນຂວາ: ຂໍ້ມູນ Sidebar ── --}}
    <div class="space-y-5">

      {{-- ຂໍ້ມູນລະບົບ --}}
      <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
        <h3 class="font-bold text-sm text-on-surface mb-3 flex items-center gap-2">
          <i class="fas fa-info-circle text-primary text-xs"></i> ຂໍ້ມູນລະບົບ
        </h3>
        <div class="space-y-3 text-xs">
          <div>
            <p class="text-outline mb-0.5">ລະຫັດ</p>
            <p class="font-semibold text-on-surface">{{ $page->id }}</p>
          </div>
          <div>
            <p class="text-outline mb-0.5">Slug (URL)</p>
            <div class="flex items-center gap-1.5">
              <code class="bg-surface-container px-2 py-1 rounded text-xs font-mono text-on-surface-variant break-all">{{ $page->slug }}</code>
              <a href="{{ url('/lo/page/' . $page->slug) }}" target="_blank"
                 class="text-primary hover:underline flex-shrink-0" title="ເປີດໜ້ານີ້">
                <i class="fas fa-external-link-alt text-[10px]"></i>
              </a>
            </div>
          </div>
          @if($page->parent_slug)
          <div>
            <p class="text-outline mb-0.5">ໜ້າແມ່</p>
            <code class="bg-surface-container px-2 py-1 rounded text-xs font-mono text-on-surface-variant">{{ $page->parent_slug }}</code>
          </div>
          @endif
          <div>
            <p class="text-outline mb-0.5">ລຳດັບ</p>
            <p class="font-semibold text-on-surface">{{ $page->sort_order }}</p>
          </div>
          @if($page->author)
          <div>
            <p class="text-outline mb-0.5">ຜູ້ສ້າງ</p>
            <p class="font-semibold text-on-surface">{{ $page->author->full_name_lo ?? $page->author->name }}</p>
          </div>
          @endif
          <div>
            <p class="text-outline mb-0.5">ສ້າງເມື່ອ</p>
            <p class="text-on-surface-variant">{{ $page->created_at->format('d/m/Y H:i') }}</p>
          </div>
          <div>
            <p class="text-outline mb-0.5">ແກ້ໄຂລ່າສຸດ</p>
            <p class="text-on-surface-variant">{{ $page->updated_at->format('d/m/Y H:i') }}</p>
          </div>
        </div>
      </div>

      {{-- ລິ້ງສາທາລະນະ --}}
      <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
        <h3 class="font-bold text-sm text-on-surface mb-3 flex items-center gap-2">
          <i class="fas fa-globe text-primary text-xs"></i> URL ສາທາລະນະ
        </h3>
        <div class="space-y-2">
          @foreach(['lo' => 'ລາວ', 'en' => 'English', 'zh' => '中文'] as $locale => $label)
          <div>
            <p class="text-xs text-outline mb-0.5">{{ $label }}</p>
            <a href="{{ url("/{$locale}/page/{$page->slug}") }}" target="_blank"
               class="text-xs text-primary hover:underline break-all">
              /{{ $locale }}/page/{{ $page->slug }}
              <i class="fas fa-external-link-alt text-[9px] ml-0.5"></i>
            </a>
          </div>
          @endforeach
        </div>
      </div>

      {{-- ກັບຄືນ --}}
      <a href="{{ route('admin.pages.index') }}"
         class="flex items-center justify-center gap-2 w-full px-4 py-2.5 rounded-lg border border-surface-container-high text-on-surface-variant hover:bg-surface-container transition text-sm font-semibold">
        <i class="fas fa-arrow-left text-xs"></i> ກັບຄືນລາຍການ
      </a>

    </div>
  </div>
</div>
@endsection
