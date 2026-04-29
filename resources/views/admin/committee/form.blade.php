@extends('admin.layouts.app')

@section('page_title', $member->exists ? 'ແກ້ໄຂສະມາຊິກ' : 'ເພີ່ມສະມາຊິກໃໝ່')

@section('content')

<form method="POST"
      action="{{ $member->exists ? route('admin.committee.update', $member) : route('admin.committee.store') }}"
      enctype="multipart/form-data"
      x-data="{ tab: 'lo' }">
  @csrf
  @if($member->exists) @method('PUT') @endif

  {{-- Breadcrumb --}}
  <div class="flex items-center gap-2 text-xs text-outline mb-5">
    <a href="{{ route('admin.committee.index') }}" class="hover:text-primary transition-colors">ສະມາຊິກກຳມະການ</a>
    <i class="fas fa-chevron-right text-[9px]"></i>
    <span class="text-on-surface-variant">{{ $member->exists ? ($member->name_lo ?? 'ແກ້ໄຂ') : 'ເພີ່ມໃໝ່' }}</span>
  </div>

  @if($errors->any())
    <div class="flex items-start gap-3 px-4 py-3 bg-red-50 border border-red-100 text-red-700 rounded-lg text-sm mb-5">
      <i class="fas fa-exclamation-circle mt-0.5 flex-shrink-0"></i>
      <ul class="space-y-0.5 list-disc list-inside">
        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
      </ul>
    </div>
  @endif

  <div class="grid grid-cols-1 xl:grid-cols-3 gap-5">

    {{-- ═══ LEFT ═══ --}}
    <div class="xl:col-span-2 space-y-5">

      {{-- ── ຂໍ້ມູນພື້ນຖານ ── --}}
      <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] overflow-hidden">
        <div class="px-5 py-4 border-b border-surface-container-high flex items-center gap-2">
          <i class="fas fa-id-card text-primary text-sm"></i>
          <h3 class="font-bold text-sm text-on-surface">ຂໍ້ມູນພື້ນຖານ</h3>
        </div>
        <div class="p-5 space-y-4">

          {{-- Gender --}}
          <div>
            <label class="block text-xs font-semibold text-on-surface-variant mb-1.5">ເພດ</label>
            <div class="flex gap-5 flex-wrap">
              @foreach(['monk' => 'ພຣະ/ສາມະເນນ', 'male' => 'ຊາຍ', 'female' => 'ຍິງ'] as $val => $lbl)
                <label class="flex items-center gap-2 cursor-pointer">
                  <input type="radio" name="gender" value="{{ $val }}"
                         @checked(old('gender', $member->gender) === $val)
                         class="text-primary focus:ring-primary/20">
                  <span class="text-sm">{{ $lbl }}</span>
                </label>
              @endforeach
              <label class="flex items-center gap-2 cursor-pointer">
                <input type="radio" name="gender" value=""
                       @checked(!old('gender', $member->gender))
                       class="text-primary focus:ring-primary/20">
                <span class="text-sm text-outline">ບໍ່ລະບຸ</span>
              </label>
            </div>
          </div>

          {{-- Language tabs --}}
          <div class="border border-surface-container-high rounded-xl overflow-hidden">
            <div class="flex items-center border-b border-surface-container-high px-4 pt-3 bg-surface-container-low">
              @foreach(['lo' => 'ລາວ *', 'en' => 'English', 'zh' => '中文'] as $lang => $lbl)
                <button type="button" @click="tab='{{ $lang }}'"
                        :class="tab==='{{ $lang }}' ? 'border-primary text-primary font-semibold' : 'border-transparent text-on-surface-variant hover:text-on-surface'"
                        class="px-4 py-2.5 text-sm border-b-2 transition-colors -mb-px mr-1 whitespace-nowrap">
                  {{ $lbl }}
                </button>
              @endforeach
            </div>

            <div class="p-5 space-y-3">

              {{-- ── LO ── --}}
              <div x-show="tab==='lo'" x-cloak>
                <div class="grid grid-cols-3 gap-3">
                  <div>
                    <label class="block text-xs font-semibold text-on-surface-variant mb-1">ຄຳນຳໜ້າ</label>
                    <input name="title_lo" type="text" value="{{ old('title_lo', $member->title_lo) }}"
                           placeholder="ພຣະ, ທ່ານ..."
                           class="w-full px-3 py-2 text-sm bg-surface-container-low border border-surface-container-high rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/30" />
                  </div>
                  <div>
                    <label class="block text-xs font-semibold text-on-surface-variant mb-1">ຊື່</label>
                    <input name="first_name_lo" type="text" value="{{ old('first_name_lo', $member->first_name_lo) }}"
                           placeholder="ຊື່..."
                           class="w-full px-3 py-2 text-sm bg-surface-container-low border border-surface-container-high rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/30" />
                  </div>
                  <div>
                    <label class="block text-xs font-semibold text-on-surface-variant mb-1">ນາມສະກຸນ</label>
                    <input name="last_name_lo" type="text" value="{{ old('last_name_lo', $member->last_name_lo) }}"
                           placeholder="ນາມສະກຸນ..."
                           class="w-full px-3 py-2 text-sm bg-surface-container-low border border-surface-container-high rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/30" />
                  </div>
                </div>
                <div class="mt-3">
                  <label class="block text-xs font-semibold text-on-surface-variant mb-1">ຊື່ເຕັມ (ສຳລັບສະແດງ) <span class="text-red-500">*</span></label>
                  <input name="name_lo" type="text" value="{{ old('name_lo', $member->name_lo) }}" required
                         placeholder="ຊື່ເຕັມ..."
                         class="w-full px-3 py-2 text-sm bg-surface-container-low border border-surface-container-high rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/30 @error('name_lo') border-red-300 @enderror" />
                  @error('name_lo')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
                <div class="mt-3">
                  <label class="block text-xs font-semibold text-on-surface-variant mb-1">ຕໍາແໜ່ງ <span class="text-red-500">*</span></label>
                  <input name="position_lo" type="text" value="{{ old('position_lo', $member->position_lo) }}" required
                         placeholder="ຕໍາແໜ່ງ..."
                         class="w-full px-3 py-2 text-sm bg-surface-container-low border border-surface-container-high rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/30 @error('position_lo') border-red-300 @enderror" />
                  @error('position_lo')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
                <div class="mt-3">
                  <label class="block text-xs font-semibold text-on-surface-variant mb-1">ວຸດທິການສຶກສາ</label>
                  <input name="education_lo" type="text" value="{{ old('education_lo', $member->education_lo) }}"
                         placeholder="ລະດັບການສຶກສາ..."
                         class="w-full px-3 py-2 text-sm bg-surface-container-low border border-surface-container-high rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/30" />
                </div>
                <div class="mt-3">
                  <label class="block text-xs font-semibold text-on-surface-variant mb-1">ຄຳອະທິບາຍ</label>
                  <textarea name="bio_lo" rows="3" placeholder="ປະຫວັດຫຍໍ້..."
                            class="w-full px-3 py-2 text-sm bg-surface-container-low border border-surface-container-high rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/30 resize-y">{{ old('bio_lo', $member->bio_lo) }}</textarea>
                </div>
              </div>

              {{-- ── EN ── --}}
              <div x-show="tab==='en'" x-cloak>
                <div class="grid grid-cols-3 gap-3">
                  <div>
                    <label class="block text-xs font-semibold text-on-surface-variant mb-1">Title</label>
                    <input name="title_en" type="text" value="{{ old('title_en', $member->title_en) }}"
                           placeholder="Ven., Mr...."
                           class="w-full px-3 py-2 text-sm bg-surface-container-low border border-surface-container-high rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/30" />
                  </div>
                  <div>
                    <label class="block text-xs font-semibold text-on-surface-variant mb-1">First Name</label>
                    <input name="first_name_en" type="text" value="{{ old('first_name_en', $member->first_name_en) }}"
                           placeholder="First name..."
                           class="w-full px-3 py-2 text-sm bg-surface-container-low border border-surface-container-high rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/30" />
                  </div>
                  <div>
                    <label class="block text-xs font-semibold text-on-surface-variant mb-1">Last Name</label>
                    <input name="last_name_en" type="text" value="{{ old('last_name_en', $member->last_name_en) }}"
                           placeholder="Last name..."
                           class="w-full px-3 py-2 text-sm bg-surface-container-low border border-surface-container-high rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/30" />
                  </div>
                </div>
                <div class="mt-3">
                  <label class="block text-xs font-semibold text-on-surface-variant mb-1">Full Name (EN)</label>
                  <input name="name_en" type="text" value="{{ old('name_en', $member->name_en) }}"
                         placeholder="Full display name..."
                         class="w-full px-3 py-2 text-sm bg-surface-container-low border border-surface-container-high rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/30" />
                </div>
                <div class="mt-3">
                  <label class="block text-xs font-semibold text-on-surface-variant mb-1">Position (EN)</label>
                  <input name="position_en" type="text" value="{{ old('position_en', $member->position_en) }}"
                         placeholder="Position..."
                         class="w-full px-3 py-2 text-sm bg-surface-container-low border border-surface-container-high rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/30" />
                </div>
                <div class="mt-3">
                  <label class="block text-xs font-semibold text-on-surface-variant mb-1">Education (EN)</label>
                  <input name="education_en" type="text" value="{{ old('education_en', $member->education_en) }}"
                         placeholder="Education level..."
                         class="w-full px-3 py-2 text-sm bg-surface-container-low border border-surface-container-high rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/30" />
                </div>
                <div class="mt-3">
                  <label class="block text-xs font-semibold text-on-surface-variant mb-1">Biography (EN)</label>
                  <textarea name="bio_en" rows="3" placeholder="Short biography..."
                            class="w-full px-3 py-2 text-sm bg-surface-container-low border border-surface-container-high rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/30 resize-y">{{ old('bio_en', $member->bio_en) }}</textarea>
                </div>
              </div>

              {{-- ── ZH ── --}}
              <div x-show="tab==='zh'" x-cloak>
                <div class="grid grid-cols-3 gap-3">
                  <div>
                    <label class="block text-xs font-semibold text-on-surface-variant mb-1">称谓</label>
                    <input name="title_zh" type="text" value="{{ old('title_zh', $member->title_zh) }}"
                           placeholder="法师、先生..."
                           class="w-full px-3 py-2 text-sm bg-surface-container-low border border-surface-container-high rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/30" />
                  </div>
                  <div>
                    <label class="block text-xs font-semibold text-on-surface-variant mb-1">名</label>
                    <input name="first_name_zh" type="text" value="{{ old('first_name_zh', $member->first_name_zh) }}"
                           placeholder="名..."
                           class="w-full px-3 py-2 text-sm bg-surface-container-low border border-surface-container-high rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/30" />
                  </div>
                  <div>
                    <label class="block text-xs font-semibold text-on-surface-variant mb-1">姓</label>
                    <input name="last_name_zh" type="text" value="{{ old('last_name_zh', $member->last_name_zh) }}"
                           placeholder="姓..."
                           class="w-full px-3 py-2 text-sm bg-surface-container-low border border-surface-container-high rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/30" />
                  </div>
                </div>
                <div class="mt-3">
                  <label class="block text-xs font-semibold text-on-surface-variant mb-1">全名 (中文)</label>
                  <input name="name_zh" type="text" value="{{ old('name_zh', $member->name_zh) }}"
                         placeholder="显示全名..."
                         class="w-full px-3 py-2 text-sm bg-surface-container-low border border-surface-container-high rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/30" />
                </div>
                <div class="mt-3">
                  <label class="block text-xs font-semibold text-on-surface-variant mb-1">职位 (中文)</label>
                  <input name="position_zh" type="text" value="{{ old('position_zh', $member->position_zh) }}"
                         placeholder="职位..."
                         class="w-full px-3 py-2 text-sm bg-surface-container-low border border-surface-container-high rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/30" />
                </div>
                <div class="mt-3">
                  <label class="block text-xs font-semibold text-on-surface-variant mb-1">学历 (中文)</label>
                  <input name="education_zh" type="text" value="{{ old('education_zh', $member->education_zh) }}"
                         placeholder="学历..."
                         class="w-full px-3 py-2 text-sm bg-surface-container-low border border-surface-container-high rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/30" />
                </div>
                <div class="mt-3">
                  <label class="block text-xs font-semibold text-on-surface-variant mb-1">简介 (中文)</label>
                  <textarea name="bio_zh" rows="3" placeholder="简短介绍..."
                            class="w-full px-3 py-2 text-sm bg-surface-container-low border border-surface-container-high rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/30 resize-y">{{ old('bio_zh', $member->bio_zh) }}</textarea>
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>

      {{-- ── ຂໍ້ມູນສ່ວນຕົວ ── --}}
      <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] overflow-hidden">
        <div class="px-5 py-4 border-b border-surface-container-high flex items-center gap-2">
          <i class="fas fa-calendar text-primary text-sm"></i>
          <h3 class="font-bold text-sm text-on-surface">ຂໍ້ມູນສ່ວນຕົວ</h3>
        </div>
        <div class="p-5">
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-semibold text-on-surface-variant mb-1.5">ວັນເດືອນປີເກີດ</label>
              <input type="date" name="date_of_birth"
                     value="{{ old('date_of_birth', $member->date_of_birth?->format('Y-m-d')) }}"
                     class="w-full px-3 py-2.5 text-sm bg-surface-container-low border border-surface-container-high rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/30" />
              @if($member->date_of_birth)
                <p class="mt-1 text-[11px] text-outline">ອາຍຸ: {{ $member->date_of_birth->age }} ປີ</p>
              @endif
            </div>
            <div>
              <label class="block text-xs font-semibold text-on-surface-variant mb-1.5">ວັນເດືອນປີບວດ</label>
              <input type="date" name="date_of_ordination"
                     value="{{ old('date_of_ordination', $member->date_of_ordination?->format('Y-m-d')) }}"
                     class="w-full px-3 py-2.5 text-sm bg-surface-container-low border border-surface-container-high rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/30" />
            </div>
            <div>
              <label class="block text-xs font-semibold text-on-surface-variant mb-1.5">ພັນສາ</label>
              <input type="number" name="pansa" min="0" max="200"
                     value="{{ old('pansa', $member->pansa) }}" placeholder="0"
                     class="w-full px-3 py-2.5 text-sm bg-surface-container-low border border-surface-container-high rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/30" />
            </div>
          </div>
        </div>
      </div>

      {{-- ── ທີ່ຢູ່ ── --}}
      <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] overflow-hidden">
        <div class="px-5 py-4 border-b border-surface-container-high flex items-center gap-2">
          <i class="fas fa-map-marker-alt text-primary text-sm"></i>
          <h3 class="font-bold text-sm text-on-surface">ທີ່ຢູ່ & ສະຖານທີ່</h3>
        </div>
        <div class="p-5 space-y-4">
          @foreach([
            ['birth_village', 'ບ້ານເກີດ',       'Birth village', '出生村'],
            ['district',      'ເມືອງ',           'District',      '县/市'],
            ['province',      'ແຂວງ',            'Province',      '省份'],
            ['current_temple','ວັດຢູ່ປັດຈຸບັນ',  'Current temple','所在寺庙'],
          ] as [$field, $lo, $en, $zh])
            <div>
              <label class="block text-xs font-semibold text-on-surface-variant mb-1.5">
                {{ $lo }} / {{ $en }} / {{ $zh }}
              </label>
              <div class="grid grid-cols-3 gap-2">
                <input type="text" name="{{ $field }}_lo"
                       value="{{ old($field.'_lo', $member->{$field.'_lo'}) }}"
                       placeholder="{{ $lo }}..."
                       class="px-3 py-2 text-sm bg-surface-container-low border border-surface-container-high rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/30" />
                <input type="text" name="{{ $field }}_en"
                       value="{{ old($field.'_en', $member->{$field.'_en'}) }}"
                       placeholder="{{ $en }}..."
                       class="px-3 py-2 text-sm bg-surface-container-low border border-surface-container-high rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/30" />
                <input type="text" name="{{ $field }}_zh"
                       value="{{ old($field.'_zh', $member->{$field.'_zh'}) }}"
                       placeholder="{{ $zh }}..."
                       class="px-3 py-2 text-sm bg-surface-container-low border border-surface-container-high rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/30" />
              </div>
            </div>
          @endforeach
        </div>
      </div>

      {{-- ── ຕິດຕໍ່ ── --}}
      <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] overflow-hidden">
        <div class="px-5 py-4 border-b border-surface-container-high flex items-center gap-2">
          <i class="fas fa-address-card text-primary text-sm"></i>
          <h3 class="font-bold text-sm text-on-surface">ຂໍ້ມູນຕິດຕໍ່</h3>
        </div>
        <div class="p-5">
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-semibold text-on-surface-variant mb-1.5">
                <i class="fas fa-phone text-outline text-[10px] mr-1"></i> ເບີໂທ
              </label>
              <input type="tel" name="phone" value="{{ old('phone', $member->phone) }}"
                     placeholder="+856 20..."
                     class="w-full px-3 py-2.5 text-sm bg-surface-container-low border border-surface-container-high rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/30" />
            </div>
            <div>
              <label class="block text-xs font-semibold text-on-surface-variant mb-1.5">
                <i class="fas fa-envelope text-outline text-[10px] mr-1"></i> ອີເມລ
              </label>
              <input type="email" name="email" value="{{ old('email', $member->email) }}"
                     placeholder="name@example.com"
                     class="w-full px-3 py-2.5 text-sm bg-surface-container-low border border-surface-container-high rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/30" />
            </div>
            <div class="sm:col-span-2">
              <label class="block text-xs font-semibold text-on-surface-variant mb-1.5">
                <i class="fab fa-facebook text-outline text-[10px] mr-1"></i> ເຟສບຸກ
              </label>
              <input type="text" name="facebook" value="{{ old('facebook', $member->facebook) }}"
                     placeholder="https://facebook.com/..."
                     class="w-full px-3 py-2.5 text-sm bg-surface-container-low border border-surface-container-high rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/30" />
            </div>
          </div>
        </div>
      </div>

    </div>

    {{-- ═══ RIGHT Sidebar ═══ --}}
    <div class="space-y-5">

      {{-- Actions --}}
      <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
        <div class="flex flex-col gap-2.5">
          <button type="submit"
                  class="w-full flex items-center justify-center gap-2 text-sm font-semibold text-white primary-gradient px-4 py-2.5 rounded-lg hover:opacity-90 active:scale-[0.98] transition-all">
            <i class="fas fa-save text-xs"></i>
            {{ $member->exists ? 'ບັນທຶກ' : 'ເພີ່ມສະມາຊິກ' }}
          </button>
          <a href="{{ route('admin.committee.index') }}"
             class="w-full flex items-center justify-center text-sm text-on-surface-variant bg-surface-container-low border border-surface-container-high px-4 py-2.5 rounded-lg hover:bg-surface-container transition-colors">
            ຍົກເລີກ
          </a>
        </div>
      </div>

      {{-- Photo --}}
      <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] overflow-hidden"
           x-data="{ preview: '{{ $member->photo_url ?? '' }}' }">
        <div class="px-5 py-4 border-b border-surface-container-high">
          <h3 class="text-sm font-bold text-on-surface">ຮູບໂປຣໄຟລ</h3>
        </div>
        <div class="p-5 space-y-3">
          <div class="relative w-32 h-32 mx-auto rounded-full overflow-hidden bg-surface-container-high flex items-center justify-center">
            <template x-if="preview">
              <img :src="preview" class="w-full h-full object-cover" alt="preview" />
            </template>
            <template x-if="!preview">
              <div class="flex flex-col items-center gap-1 text-outline">
                <i class="fas fa-user text-4xl opacity-30"></i>
              </div>
            </template>
          </div>
          <label class="flex items-center justify-center gap-2 w-full px-3 py-2.5 text-sm text-on-surface-variant bg-surface-container-low border border-dashed border-surface-container-highest rounded-lg cursor-pointer hover:bg-surface-container transition-colors">
            <i class="fas fa-upload text-xs"></i> ອັບໂຫຼດຮູບ
            <input type="file" name="photo" accept="image/jpeg,image/png,image/webp" class="hidden"
                   @change="preview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : preview" />
          </label>
          @error('photo')<p class="text-xs text-red-500 text-center">{{ $message }}</p>@enderror
          <p class="text-[10px] text-outline text-center">JPG, PNG, WebP · ສູງສຸດ 3MB</p>
        </div>
      </div>

      {{-- Department --}}
      <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] overflow-hidden">
        <div class="px-5 py-4 border-b border-surface-container-high flex items-center justify-between">
          <div class="flex items-center gap-2">
            <i class="fas fa-building text-primary text-sm"></i>
            <h3 class="text-sm font-bold text-on-surface">ພະແນກ</h3>
          </div>
          <a href="{{ route('admin.departments.create') }}" target="_blank"
             class="text-xs text-primary hover:underline flex items-center gap-1">
            <i class="fas fa-plus text-[10px]"></i> ເພີ່ມໃໝ່
          </a>
        </div>
        <div class="p-5">
          <select name="department_id"
                  class="w-full px-3 py-2.5 text-sm bg-surface-container-low border border-surface-container-high rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/30">
            <option value="">— ບໍ່ມີພະແນກ —</option>
            @foreach($departments as $dept)
              <option value="{{ $dept['id'] }}"
                      @selected(old('department_id', $member->department_id) == $dept['id'])
                      style="{{ $dept['depth'] > 0 ? 'padding-left:1.25rem;color:#64748b;' : 'font-weight:600;' }}">
                {{ $dept['depth'] > 0 ? '↳ ' : '' }}{{ $dept['label'] }}
              </option>
            @endforeach
          </select>
          @if($departments->isEmpty())
            <p class="mt-2 text-[11px] text-outline">
              ຍັງບໍ່ມີພະແນກ —
              <a href="{{ route('admin.departments.create') }}" class="text-primary hover:underline">ສ້າງພະແນກກ່ອນ</a>
            </p>
          @endif
        </div>
      </div>

      {{-- Settings --}}
      <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] overflow-hidden">
        <div class="px-5 py-4 border-b border-surface-container-high">
          <h3 class="text-sm font-bold text-on-surface">ການຕັ້ງຄ່າ</h3>
        </div>
        <div class="p-5 space-y-4">

          <label class="flex items-center gap-3 cursor-pointer"
                 x-data="{ on: {{ old('is_active', $member->is_active ?? true) ? 'true' : 'false' }} }">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1" x-model="on" class="sr-only">
            <button type="button" @click="on=!on"
                    :class="on ? 'bg-primary' : 'bg-surface-container-high'"
                    class="relative inline-flex h-6 w-11 rounded-full transition-colors focus:outline-none flex-shrink-0">
              <span :class="on ? 'translate-x-5' : 'translate-x-0.5'"
                    class="inline-block h-5 w-5 transform rounded-full bg-white shadow transition-transform mt-0.5"></span>
            </button>
            <div>
              <span class="text-sm font-semibold" x-text="on ? 'ເປີດໃຊ້ງານ' : 'ປິດໃຊ້ງານ'"></span>
              <p class="text-[11px] text-outline">ສະແດງໃນໜ້າ frontend</p>
            </div>
          </label>

          <div>
            <label class="block text-xs font-semibold text-on-surface-variant mb-1.5">ລຳດັບ</label>
            <input type="number" name="sort_order" value="{{ old('sort_order', $member->sort_order ?? 0) }}"
                   min="0" max="9999"
                   class="w-full px-3 py-2 text-sm bg-surface-container-low border border-surface-container-high rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/30" />
            <p class="mt-1 text-[10px] text-outline">ຕ່ຳສຸດ = ສະແດງກ່ອນ</p>
          </div>

          <div class="grid grid-cols-2 gap-3 pt-2 border-t border-surface-container-high">
            <div>
              <label class="block text-xs font-semibold text-on-surface-variant mb-1.5">ປີເລີ່ມຕົ້ນ</label>
              <input type="number" name="term_start" value="{{ old('term_start', $member->term_start) }}"
                     min="2000" max="2100" placeholder="{{ date('Y') }}"
                     class="w-full px-3 py-2 text-sm bg-surface-container-low border border-surface-container-high rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/30" />
            </div>
            <div>
              <label class="block text-xs font-semibold text-on-surface-variant mb-1.5">ປີສິ້ນສຸດ</label>
              <input type="number" name="term_end" value="{{ old('term_end', $member->term_end) }}"
                     min="2000" max="2100" placeholder="–"
                     class="w-full px-3 py-2 text-sm bg-surface-container-low border border-surface-container-high rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/30" />
            </div>
          </div>

        </div>
      </div>

    </div>
  </div>
</form>

@endsection
