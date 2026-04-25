@extends('admin.layouts.app')

@section('page_title', 'ຕັ້ງຄ່າລະບົບ')

@section('content')
<div class="max-w-4xl mx-auto"
     x-data="{ tab: '{{ array_key_first($groupLabels) }}' }">

  {{-- Header --}}
  <div class="flex items-center justify-between mb-5">
    <div>
      <h2 class="text-base font-bold text-on-surface">ຕັ້ງຄ່າລະບົບ</h2>
      <p class="text-xs text-outline mt-0.5">ຈັດການການຕັ້ງຄ່າທົ່ວໄປຂອງເວັບໄຊ</p>
    </div>
  </div>

  @if(session('success'))
    <div class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-700 rounded-xl px-4 py-3 mb-5 text-sm">
      <i class="fas fa-check-circle text-green-500"></i>
      {{ session('success') }}
    </div>
  @endif

  <form action="{{ route('admin.settings.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="flex gap-5">

      {{-- ── ແຖບຊ້າຍ (Group nav) ── --}}
      <div class="w-44 flex-shrink-0">
        <nav class="space-y-1 sticky top-4">
          @foreach($groupLabels as $group => $meta)
            <button type="button" @click="tab='{{ $group }}'"
                    :class="tab==='{{ $group }}'
                      ? 'primary-gradient text-white shadow-sm'
                      : 'text-on-surface-variant hover:bg-surface-container-low'"
                    class="w-full flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all text-left">
              <i class="fas {{ $meta['icon'] }} text-xs w-4 text-center"></i>
              {{ $meta['lo'] }}
            </button>
          @endforeach
        </nav>
      </div>

      {{-- ── ເນື້ອຫາຂວາ ── --}}
      <div class="flex-1 min-w-0">

        @foreach($groupLabels as $group => $meta)
        <div x-show="tab==='{{ $group }}'" x-transition>
          <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] divide-y divide-surface-container-high">

            {{-- Group header --}}
            <div class="px-5 py-4 flex items-center gap-2">
              <i class="fas {{ $meta['icon'] }} text-primary text-sm"></i>
              <h3 class="font-bold text-sm text-on-surface">{{ $meta['lo'] }}</h3>
            </div>

            @if(isset($settings[$group]) && $settings[$group]->isNotEmpty())
              @foreach($settings[$group] as $setting)
              <div class="px-5 py-4" x-data="{ preview: '{{ $setting->value }}' }">
                <div class="flex items-start gap-4">

                  {{-- Label --}}
                  <div class="w-44 flex-shrink-0 pt-0.5">
                    <p class="text-sm font-semibold text-on-surface">{{ $setting->label_lo }}</p>
                    @if($setting->label_en && $setting->label_en !== $setting->label_lo)
                      <p class="text-xs text-outline">{{ $setting->label_en }}</p>
                    @endif
                    <span class="inline-block mt-1 text-[10px] font-mono text-outline bg-surface-container px-1.5 py-0.5 rounded">{{ $setting->key }}</span>
                  </div>

                  {{-- Input --}}
                  <div class="flex-1 min-w-0">

                    @if($setting->type === 'boolean')
                      {{-- Boolean toggle --}}
                      <label class="flex items-center gap-3 cursor-pointer select-none"
                             x-data="{ on: {{ $setting->value ? 'true' : 'false' }} }">
                        <input type="hidden"   name="settings[{{ $setting->key }}]" value="0">
                        <input type="checkbox" name="settings[{{ $setting->key }}]" value="1"
                               x-model="on" class="sr-only">
                        <button type="button" @click="on=!on"
                                :class="on ? 'bg-primary' : 'bg-surface-container-high'"
                                class="relative inline-flex h-6 w-11 rounded-full transition-colors focus:outline-none flex-shrink-0">
                          <span :class="on ? 'translate-x-5' : 'translate-x-0.5'"
                                class="inline-block h-5 w-5 transform rounded-full bg-white shadow transition-transform mt-0.5"></span>
                        </button>
                        <span class="text-sm font-semibold" x-text="on ? 'ເປີດໃຊ້' : 'ປິດໃຊ້'"></span>
                      </label>
                      @if($setting->key === 'maintenance_mode')
                        <p class="text-xs text-amber-600 mt-1.5 flex items-center gap-1">
                          <i class="fas fa-exclamation-triangle"></i>
                          ເມື່ອເປີດ: ໜ້າເວັບສາທາລະນະຈະສະແດງໜ້າບໍລຸງຮັກສາ
                        </p>
                      @endif

                    @elseif($setting->type === 'number')
                      {{-- Number input --}}
                      <input type="number" name="settings[{{ $setting->key }}]"
                             value="{{ $setting->value }}" min="1" max="999"
                             class="w-28 rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">

                    @elseif($setting->type === 'textarea')
                      {{-- Textarea --}}
                      <textarea name="settings[{{ $setting->key }}]" rows="3"
                                class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 resize-y">{{ $setting->value }}</textarea>

                    @elseif($setting->type === 'color')
                      {{-- Color picker --}}
                      <div class="flex items-center gap-2">
                        <input type="color" name="settings[{{ $setting->key }}]"
                               value="{{ $setting->value ?: '#000000' }}"
                               class="h-9 w-16 rounded border border-surface-container-high cursor-pointer">
                        <span class="text-xs text-outline font-mono">{{ $setting->value }}</span>
                      </div>

                    @elseif($setting->type === 'image')
                      {{-- Image upload --}}
                      <div class="space-y-3">
                        {{-- Current image --}}
                        @if($setting->value)
                          <div class="flex items-center gap-3 p-3 rounded-lg bg-surface-container-low border border-surface-container-high">
                            <img :src="preview" src="{{ $setting->value }}"
                                 class="h-12 w-12 object-contain rounded border border-surface-container-high bg-white"
                                 onerror="this.style.display='none'">
                            <div class="flex-1 min-w-0">
                              <p class="text-xs font-semibold text-on-surface">ຮູບປັດຈຸບັນ</p>
                              <p class="text-[10px] font-mono text-outline truncate">{{ $setting->value }}</p>
                            </div>
                          </div>
                        @endif
                        {{-- File upload --}}
                        <div class="relative border-2 border-dashed border-surface-container-high rounded-xl hover:border-primary/40 transition-colors cursor-pointer">
                          <label class="flex flex-col items-center justify-center gap-1.5 py-5 cursor-pointer" for="file_{{ $setting->key }}">
                            <i class="fas fa-cloud-upload-alt text-xl text-outline"></i>
                            <p class="text-xs text-on-surface-variant">ກົດເພື່ອອັບໂຫຼດໄຟລ໌ໃໝ່</p>
                            <p class="text-[10px] text-outline">PNG, JPG, SVG, ICO · ສູງສຸດ 5MB</p>
                            <input type="file" id="file_{{ $setting->key }}" name="settings_files[{{ $setting->key }}]"
                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                   accept="image/*"
                                   @change="const f=$event.target.files[0]; if(f){ preview=URL.createObjectURL(f) }">
                          </label>
                        </div>
                        {{-- Or URL --}}
                        <div class="flex items-center gap-2">
                          <div class="h-px flex-1 bg-surface-container-high"></div>
                          <span class="text-xs text-outline">ຫຼື URL</span>
                          <div class="h-px flex-1 bg-surface-container-high"></div>
                        </div>
                        <input type="text" name="settings[{{ $setting->key }}]"
                               value="{{ $setting->value }}"
                               placeholder="/assets/images/logo.png ຫຼື https://..."
                               @input="preview=$event.target.value"
                               class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 font-mono">
                      </div>

                    @elseif($setting->type === 'json')
                      {{-- JSON textarea --}}
                      <textarea name="settings[{{ $setting->key }}]" rows="4"
                                class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-primary/30 resize-y">{{ $setting->value }}</textarea>

                    @else
                      {{-- Default: text input --}}
                      @php
                        $inputType = match(true) {
                          str_contains($setting->key, 'email')     => 'email',
                          str_contains($setting->key, 'phone')     => 'tel',
                          str_contains($setting->key, 'whatsapp')  => 'tel',
                          str_contains($setting->key, 'facebook')  => 'url',
                          str_contains($setting->key, 'youtube')   => 'url',
                          str_contains($setting->key, 'line')      => 'url',
                          str_contains($setting->key, 'wechat')    => 'url',
                          default => 'text',
                        };
                      @endphp
                      <input type="{{ $inputType }}" name="settings[{{ $setting->key }}]"
                             value="{{ $setting->value }}"
                             class="w-full rounded-lg border border-surface-container-high px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
                    @endif

                  </div>
                </div>
              </div>
              @endforeach
            @else
              <div class="px-5 py-8 text-center text-outline text-sm">
                <i class="fas fa-inbox text-2xl mb-2 block opacity-30"></i>
                ຍັງບໍ່ມີການຕັ້ງຄ່າໃນກຸ່ມນີ້
              </div>
            @endif

          </div>
        </div>
        @endforeach

        {{-- Submit bar --}}
        <div class="mt-4 flex gap-3">
          <button type="submit"
                  class="inline-flex items-center gap-2 primary-gradient text-white font-semibold px-6 py-2.5 rounded-lg hover:opacity-90 transition text-sm">
            <i class="fas fa-save text-xs"></i> ບັນທຶກການຕັ້ງຄ່າ
          </button>
          <a href="{{ route('admin.settings.index') }}"
             class="inline-flex items-center gap-2 px-6 py-2.5 rounded-lg border border-surface-container-high text-on-surface-variant hover:bg-surface-container transition text-sm">
            <i class="fas fa-times text-xs"></i> ຍົກເລີກ
          </a>
        </div>

      </div>
    </div>
  </form>
</div>
@endsection
