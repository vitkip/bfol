@extends('admin.layouts.app')

@section('page_title', $news->exists ? 'ແກ້ໄຂຂ່າວ' : 'ສ້າງຂ່າວໃໝ່')

@push('styles')
<link rel="stylesheet" href="https://cdn.quilljs.com/1.3.7/quill.snow.css" />
<style>
  .ql-container { font-family: 'Phetsarath OT', Inter, sans-serif; font-size: 14px; }
  .ql-editor { min-height: 280px; }
  .ql-toolbar { border-top-left-radius: 0.5rem; border-top-right-radius: 0.5rem; background: #f3f3f4; border-color: #e8e8e9 !important; }
  .ql-container.ql-snow { border-bottom-left-radius: 0.5rem; border-bottom-right-radius: 0.5rem; border-color: #e8e8e9 !important; }
  .ql-editor.ql-blank::before { font-style: normal; color: #727783; }
</style>
@endpush

@section('content')

<form method="POST"
      action="{{ $news->exists ? route('admin.news.update', $news) : route('admin.news.store') }}"
      enctype="multipart/form-data"
      x-data="newsForm()"
      @submit="syncEditors">
  @csrf
  @if($news->exists) @method('PUT') @endif

  {{-- Breadcrumb --}}
  <div class="flex items-center gap-2 text-xs text-outline mb-5">
    <a href="{{ route('admin.news.index') }}" class="hover:text-primary transition-colors">ຂ່າວສານ</a>
    <i class="fas fa-chevron-right text-[9px]"></i>
    <span class="text-on-surface-variant">{{ $news->exists ? Str::limit($news->title_lo, 40) : 'ສ້າງໃໝ່' }}</span>
  </div>

  {{-- Validation errors --}}
  @if($errors->any())
    <div class="flex items-start gap-3 px-4 py-3 bg-red-50 border border-red-100 text-red-700 rounded-lg text-sm mb-5">
      <i class="fas fa-exclamation-circle mt-0.5 flex-shrink-0"></i>
      <ul class="space-y-0.5 list-disc list-inside">
        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
      </ul>
    </div>
  @endif

  <div class="grid grid-cols-1 xl:grid-cols-3 gap-5">

    {{-- ═══ LEFT: Content ═══ --}}
    <div class="xl:col-span-2 space-y-5">

      {{-- Language tabs --}}
      <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] overflow-hidden">

        {{-- Tab header --}}
        <div class="flex items-center gap-0 border-b border-surface-container-high px-5 pt-4">
          @foreach(['lo'=>'ລາວ *','en'=>'English','zh'=>'中文'] as $lang => $lbl)
            <button type="button" @click="changeTab('{{ $lang }}')"
                    :class="tab === '{{ $lang }}' ? 'border-primary text-primary font-semibold' : 'border-transparent text-on-surface-variant hover:text-on-surface'"
                    class="px-4 py-2.5 text-sm border-b-2 transition-colors -mb-px mr-1 whitespace-nowrap">
              {{ $lbl }}
            </button>
          @endforeach
        </div>

        <div class="p-5 space-y-4">

          {{-- ── LO ── --}}
          <div x-show="tab === 'lo'" x-cloak>
            <div class="space-y-4">
              <div>
                <label class="block text-xs font-semibold text-on-surface-variant mb-1.5">ຫົວຂໍ້ (ລາວ) <span class="text-red-500">*</span></label>
                <input name="title_lo" type="text" value="{{ old('title_lo', $news->title_lo) }}" required
                       placeholder="ຫົວຂໍ້ຂ່າວ..."
                       class="w-full px-3 py-2.5 text-sm bg-surface-container-low border border-surface-container-high rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/30 transition-all @error('title_lo') border-red-300 @enderror" />
                @error('title_lo')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
              </div>
              <div>
                <label class="block text-xs font-semibold text-on-surface-variant mb-1.5">ຫຍໍ້ຄວາມ (ລາວ)</label>
                <textarea name="excerpt_lo" rows="2" placeholder="ຫຍໍ້ຄວາມສັ້ນ..."
                          class="w-full px-3 py-2.5 text-sm bg-surface-container-low border border-surface-container-high rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/30 transition-all resize-none">{{ old('excerpt_lo', $news->excerpt_lo) }}</textarea>
              </div>
              <div>
                <label class="block text-xs font-semibold text-on-surface-variant mb-1.5">ເນື້ອຫາ (ລາວ) <span class="text-red-500">*</span></label>
                <div id="editor-lo" class="rounded-lg">{{ old('content_lo', $news->content_lo) }}</div>
                <input type="hidden" name="content_lo" id="content_lo" value="{{ old('content_lo', $news->content_lo) }}" />
                @error('content_lo')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
              </div>
            </div>
          </div>

          {{-- ── EN ── --}}
          <div x-show="tab === 'en'" x-cloak>
            <div class="space-y-4">
              <div>
                <label class="block text-xs font-semibold text-on-surface-variant mb-1.5">Title (English)</label>
                <input name="title_en" type="text" value="{{ old('title_en', $news->title_en) }}"
                       placeholder="News title in English..."
                       class="w-full px-3 py-2.5 text-sm bg-surface-container-low border border-surface-container-high rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/30 transition-all" />
              </div>
              <div>
                <label class="block text-xs font-semibold text-on-surface-variant mb-1.5">Excerpt (English)</label>
                <textarea name="excerpt_en" rows="2" placeholder="Short excerpt..."
                          class="w-full px-3 py-2.5 text-sm bg-surface-container-low border border-surface-container-high rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/30 transition-all resize-none">{{ old('excerpt_en', $news->excerpt_en) }}</textarea>
              </div>
              <div>
                <label class="block text-xs font-semibold text-on-surface-variant mb-1.5">Content (English)</label>
                <div id="editor-en" class="rounded-lg">{{ old('content_en', $news->content_en) }}</div>
                <input type="hidden" name="content_en" id="content_en" value="{{ old('content_en', $news->content_en) }}" />
              </div>
            </div>
          </div>

          {{-- ── ZH ── --}}
          <div x-show="tab === 'zh'" x-cloak>
            <div class="space-y-4">
              <div>
                <label class="block text-xs font-semibold text-on-surface-variant mb-1.5">標題 (中文)</label>
                <input name="title_zh" type="text" value="{{ old('title_zh', $news->title_zh) }}"
                       placeholder="新闻标题..."
                       class="w-full px-3 py-2.5 text-sm bg-surface-container-low border border-surface-container-high rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/30 transition-all" />
              </div>
              <div>
                <label class="block text-xs font-semibold text-on-surface-variant mb-1.5">摘要 (中文)</label>
                <textarea name="excerpt_zh" rows="2" placeholder="简短摘要..."
                          class="w-full px-3 py-2.5 text-sm bg-surface-container-low border border-surface-container-high rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/30 transition-all resize-none">{{ old('excerpt_zh', $news->excerpt_zh) }}</textarea>
              </div>
              <div>
                <label class="block text-xs font-semibold text-on-surface-variant mb-1.5">内容 (中文)</label>
                <div id="editor-zh" class="rounded-lg">{{ old('content_zh', $news->content_zh) }}</div>
                <input type="hidden" name="content_zh" id="content_zh" value="{{ old('content_zh', $news->content_zh) }}" />
              </div>
            </div>
          </div>

        </div>
      </div>

      {{-- Slug (edit only) --}}
      @if($news->exists)
        <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] px-5 py-4">
          <label class="block text-xs font-semibold text-on-surface-variant mb-1.5">Slug</label>
          <p class="text-sm font-mono text-on-surface-variant bg-surface-container-low px-3 py-2 rounded-lg border border-surface-container-high select-all break-all">
            {{ $news->slug }}
          </p>
        </div>
      @endif

    </div>

    {{-- ═══ RIGHT: Sidebar ═══ --}}
    <div class="space-y-5">

      {{-- Actions --}}
      <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
        <div class="flex flex-col gap-2.5">
          <button type="submit"
                  class="w-full flex items-center justify-center gap-2 text-sm font-semibold text-white primary-gradient px-4 py-2.5 rounded-lg hover:opacity-90 active:scale-[0.98] transition-all">
            <i class="fas fa-save text-xs"></i>
            {{ $news->exists ? 'ບັນທຶກ' : 'ສ້າງຂ່າວ' }}
          </button>
          <a href="{{ route('admin.news.index') }}"
             class="w-full flex items-center justify-center text-sm text-on-surface-variant bg-surface-container-low border border-surface-container-high px-4 py-2.5 rounded-lg hover:bg-surface-container transition-colors">
            ຍົກເລີກ
          </a>
        </div>
      </div>

      {{-- Thumbnail --}}
      <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] overflow-hidden"
           x-data="{ preview: '{{ $news->thumbnail ? Storage::url($news->thumbnail) : '' }}' }">
        <div class="px-5 py-4 border-b border-surface-container-high">
          <h3 class="text-sm font-bold text-on-surface">ຮູບໜ້າປົກ</h3>
        </div>
        <div class="p-5 space-y-3">
          {{-- Preview --}}
          <div class="relative w-full aspect-video bg-surface-container-high rounded-lg overflow-hidden flex items-center justify-center">
            <template x-if="preview">
              <img :src="preview" class="w-full h-full object-cover" alt="preview" />
            </template>
            <template x-if="!preview">
              <div class="flex flex-col items-center gap-2 text-outline">
                <i class="fas fa-image text-3xl opacity-30"></i>
                <span class="text-xs">ຍັງບໍ່ມີຮູບ</span>
              </div>
            </template>
          </div>
          {{-- Upload --}}
          <label class="flex items-center justify-center gap-2 w-full px-3 py-2.5 text-sm text-on-surface-variant bg-surface-container-low border border-dashed border-surface-container-highest rounded-lg cursor-pointer hover:bg-surface-container transition-colors">
            <i class="fas fa-upload text-xs"></i>
            <span>ເລືອກຮູບ (JPG, PNG, WebP)</span>
            <input type="file" name="thumbnail" accept="image/jpeg,image/png,image/webp" class="hidden"
                   @change="preview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : preview" />
          </label>
          @error('thumbnail')<p class="text-xs text-red-500">{{ $message }}</p>@enderror
          <p class="text-[10px] text-outline text-center">ຂະໜາດສູງສຸດ 3MB</p>
        </div>
      </div>

      {{-- Status & Publish --}}
      <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] overflow-hidden">
        <div class="px-5 py-4 border-b border-surface-container-high">
          <h3 class="text-sm font-bold text-on-surface">ການເຜີຍແຜ່</h3>
        </div>
        <div class="p-5 space-y-4">
          <div>
            <label class="block text-xs font-semibold text-on-surface-variant mb-1.5">ສະຖານະ <span class="text-red-500">*</span></label>
            <select name="status"
                    class="w-full px-3 py-2.5 text-sm bg-surface-container-low border border-surface-container-high rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/30 transition-all">
              <option value="draft"     @selected(old('status', $news->status ?? 'draft') === 'draft')>ຮ່າງ (Draft)</option>
              <option value="published" @selected(old('status', $news->status) === 'published')>ເຜີຍແຜ່ (Published)</option>
              <option value="archived"  @selected(old('status', $news->status) === 'archived')>ເກັບ (Archived)</option>
            </select>
          </div>
          <div>
            <label class="block text-xs font-semibold text-on-surface-variant mb-1.5">ວັນທີ/ເວລາເຜີຍແຜ່</label>
            <input type="datetime-local" name="published_at"
                   value="{{ old('published_at', $news->published_at?->format('Y-m-d\TH:i')) }}"
                   class="w-full px-3 py-2.5 text-sm bg-surface-container-low border border-surface-container-high rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/30 transition-all" />
            <p class="mt-1 text-[10px] text-outline">ປ່ອຍວ່າງ = ໃຊ້ເວລາປັດຈຸບັນ</p>
          </div>
          {{-- Featured / Urgent --}}
          <div class="space-y-2.5 pt-1 border-t border-surface-container-high">
            <label class="flex items-center gap-3 cursor-pointer">
              <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $news->is_featured))
                     class="w-4 h-4 rounded border-outline-variant text-primary focus:ring-primary/20 cursor-pointer" />
              <div>
                <span class="text-sm text-on-surface flex items-center gap-1.5">
                  <i class="fas fa-star text-amber-500 text-xs"></i> ຂ່າວເດັ່ນ
                </span>
                <p class="text-[10px] text-outline">ສະແດງໃນ section ເດັ່ນ</p>
              </div>
            </label>
            <label class="flex items-center gap-3 cursor-pointer">
              <input type="checkbox" name="is_urgent" value="1" @checked(old('is_urgent', $news->is_urgent))
                     class="w-4 h-4 rounded border-outline-variant text-primary focus:ring-primary/20 cursor-pointer" />
              <div>
                <span class="text-sm text-on-surface flex items-center gap-1.5">
                  <i class="fas fa-bolt text-red-500 text-xs"></i> ຂ່າວດ່ວນ
                </span>
                <p class="text-[10px] text-outline">ສະແດງ badge ດ່ວນ</p>
              </div>
            </label>
          </div>
        </div>
      </div>

      {{-- Category --}}
      <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] overflow-hidden">
        <div class="px-5 py-4 border-b border-surface-container-high">
          <h3 class="text-sm font-bold text-on-surface">ໝວດໝູ່</h3>
        </div>
        <div class="p-5">
          <select name="category_id"
                  class="w-full px-3 py-2.5 text-sm bg-surface-container-low border border-surface-container-high rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/30 transition-all">
            <option value="">— ບໍ່ມີໝວດ —</option>
            @foreach($categories as $cat)
              <option value="{{ $cat->id }}" @selected(old('category_id', $news->category_id) == $cat->id)>
                {{ $cat->name_lo }}
              </option>
            @endforeach
          </select>
          @if($categories->isEmpty())
            <p class="mt-2 text-[11px] text-outline">ຍັງບໍ່ມີໝວດ — ເພີ່ມໃນ <a href="#" class="text-primary hover:underline">ຈັດການໝວດ</a></p>
          @endif
        </div>
      </div>

      {{-- Tags --}}
      @if($tags->isNotEmpty())
        <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] overflow-hidden">
          <div class="px-5 py-4 border-b border-surface-container-high">
            <h3 class="text-sm font-bold text-on-surface">Tags</h3>
          </div>
          <div class="p-5 max-h-52 overflow-y-auto space-y-2">
            @php $selectedTags = old('tag_ids', $news->exists ? $news->tags->pluck('id')->toArray() : []); @endphp
            @foreach($tags as $tag)
              <label class="flex items-center gap-2.5 cursor-pointer group">
                <input type="checkbox" name="tag_ids[]" value="{{ $tag->id }}"
                       @checked(in_array($tag->id, $selectedTags))
                       class="w-3.5 h-3.5 rounded border-outline-variant text-primary focus:ring-primary/20 cursor-pointer" />
                <span class="text-sm text-on-surface-variant group-hover:text-on-surface transition-colors">
                  {{ $tag->name_lo }}
                  @if($tag->name_en)<span class="text-[10px] text-outline"> · {{ $tag->name_en }}</span>@endif
                </span>
              </label>
            @endforeach
          </div>
        </div>
      @endif

    </div>
  </div>
</form>

@endsection

@push('scripts')
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
<script>
  const toolbarOptions = [
    [{ header: [2, 3, false] }],
    ['bold', 'italic', 'underline'],
    [{ list: 'ordered' }, { list: 'bullet' }],
    ['blockquote', 'link', 'image'],
    ['clean'],
  ];

  const editors = {};
  ['lo', 'en', 'zh'].forEach(lang => {
    editors[lang] = new Quill('#editor-' + lang, {
      theme: 'snow',
      modules: { toolbar: toolbarOptions },
      placeholder: lang === 'lo' ? 'ຂຽນເນື້ອຫາທີ່ນີ້…' : (lang === 'en' ? 'Write content here…' : '在此写内容…'),
    });
    // Set initial HTML
    const initial = document.getElementById('content_' + lang).value;
    if (initial) editors[lang].root.innerHTML = initial;
  });

  function newsForm() {
    return {
      tab: 'lo',
      changeTab(lang) {
        this.tab = lang;
        this.$nextTick(() => editors[lang]?.update());
      },
      syncEditors() {
        ['lo', 'en', 'zh'].forEach(lang => {
          document.getElementById('content_' + lang).value = editors[lang].root.innerHTML;
        });
      }
    };
  }
</script>
@endpush
