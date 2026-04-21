@extends('admin.layouts.app')

@section('page_title', $user->exists ? 'ແກ້ໄຂຜູ້ໃຊ້' : 'ເພີ່ມຜູ້ໃຊ້ໃໝ່')

@section('content')

<div class="max-w-2xl">

  {{-- Breadcrumb --}}
  <div class="flex items-center gap-2 text-xs text-outline mb-5">
    <a href="{{ route('admin.users.index') }}" class="hover:text-primary transition-colors">ຜູ້ໃຊ້</a>
    <i class="fas fa-chevron-right text-[9px]"></i>
    <span class="text-on-surface-variant">{{ $user->exists ? $user->full_name_lo : 'ສ້າງໃໝ່' }}</span>
  </div>

  <form method="POST"
        action="{{ $user->exists ? route('admin.users.update', $user) : route('admin.users.store') }}"
        class="space-y-5"
        x-data="{ showPass: false, showConfirm: false }">
    @csrf
    @if($user->exists) @method('PUT') @endif

    {{-- Validation errors --}}
    @if($errors->any())
      <div class="flex items-start gap-3 px-4 py-3 bg-red-50 border border-red-100 text-red-700 rounded-lg text-sm">
        <i class="fas fa-exclamation-circle mt-0.5 flex-shrink-0"></i>
        <ul class="space-y-0.5">
          @foreach($errors->all() as $e)
            <li>{{ $e }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    {{-- Card: ຂໍ້ມູນຫຼັກ --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] overflow-hidden">
      <div class="px-5 py-4 border-b border-surface-container-high">
        <h3 class="text-sm font-bold text-on-surface">ຂໍ້ມູນຫຼັກ</h3>
      </div>
      <div class="p-5 space-y-4">

        {{-- Full name LO --}}
        <div>
          <label class="block text-xs font-semibold text-on-surface-variant mb-1.5">
            ຊື່ເຕັມ (ລາວ) <span class="text-red-500">*</span>
          </label>
          <input name="full_name_lo" type="text" value="{{ old('full_name_lo', $user->full_name_lo) }}"
                 required placeholder="ຊື່ ແລະ ນາມສະກຸນ"
                 class="w-full px-3 py-2.5 text-sm bg-surface-container-low border border-surface-container-high rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary/50 transition-all @error('full_name_lo') border-red-300 @enderror" />
          @error('full_name_lo') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
        </div>

        {{-- Names EN + ZH --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="block text-xs font-semibold text-on-surface-variant mb-1.5">ຊື່ເຕັມ (English)</label>
            <input name="full_name_en" type="text" value="{{ old('full_name_en', $user->full_name_en) }}"
                   placeholder="Full Name"
                   class="w-full px-3 py-2.5 text-sm bg-surface-container-low border border-surface-container-high rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary/50 transition-all" />
          </div>
          <div>
            <label class="block text-xs font-semibold text-on-surface-variant mb-1.5">ຊື່ເຕັມ (中文)</label>
            <input name="full_name_zh" type="text" value="{{ old('full_name_zh', $user->full_name_zh) }}"
                   placeholder="全名"
                   class="w-full px-3 py-2.5 text-sm bg-surface-container-low border border-surface-container-high rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary/50 transition-all" />
          </div>
        </div>

        {{-- Username + Email --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="block text-xs font-semibold text-on-surface-variant mb-1.5">
              Username <span class="text-red-500">*</span>
            </label>
            <input name="username" type="text" value="{{ old('username', $user->username) }}"
                   required placeholder="admin_user"
                   class="w-full px-3 py-2.5 text-sm font-mono bg-surface-container-low border border-surface-container-high rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary/50 transition-all @error('username') border-red-300 @enderror" />
            @error('username') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
          </div>
          <div>
            <label class="block text-xs font-semibold text-on-surface-variant mb-1.5">
              Email <span class="text-red-500">*</span>
            </label>
            <input name="email" type="email" value="{{ old('email', $user->email) }}"
                   required placeholder="user@bfol.la"
                   class="w-full px-3 py-2.5 text-sm bg-surface-container-low border border-surface-container-high rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary/50 transition-all @error('email') border-red-300 @enderror" />
            @error('email') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
          </div>
        </div>

      </div>
    </div>

    {{-- Card: ສິດ ແລະ ສະຖານະ --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] overflow-hidden">
      <div class="px-5 py-4 border-b border-surface-container-high">
        <h3 class="text-sm font-bold text-on-surface">ສິດ ແລະ ສະຖານະ</h3>
      </div>
      <div class="p-5 space-y-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="block text-xs font-semibold text-on-surface-variant mb-1.5">
              Role <span class="text-red-500">*</span>
            </label>
            <select name="role"
                    class="w-full px-3 py-2.5 text-sm bg-surface-container-low border border-surface-container-high rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary/50 transition-all @error('role') border-red-300 @enderror">
              @foreach(['superadmin'=>'Super Admin','admin'=>'Admin','editor'=>'Editor','viewer'=>'Viewer'] as $val => $label)
                <option value="{{ $val }}" @selected(old('role', $user->role) === $val)>{{ $label }}</option>
              @endforeach
            </select>
            @error('role') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
          </div>
          <div class="flex flex-col justify-end">
            <label class="flex items-center gap-3 cursor-pointer py-2.5">
              <input name="is_active" type="checkbox" value="1"
                     @checked(old('is_active', $user->exists ? $user->is_active : true))
                     class="w-4 h-4 rounded border-outline-variant text-primary focus:ring-primary/20 cursor-pointer" />
              <span class="text-sm text-on-surface">ເປີດໃຊ້ງານ</span>
            </label>
          </div>
        </div>
      </div>
    </div>

    {{-- Card: ລະຫັດຜ່ານ --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] overflow-hidden">
      <div class="px-5 py-4 border-b border-surface-container-high">
        <h3 class="text-sm font-bold text-on-surface">ລະຫັດຜ່ານ</h3>
        @if($user->exists)
          <p class="text-xs text-outline mt-0.5">ປ່ອຍວ່າງຖ້າບໍ່ຕ້ອງການປ່ຽນ</p>
        @endif
      </div>
      <div class="p-5 space-y-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="block text-xs font-semibold text-on-surface-variant mb-1.5">
              ລະຫັດຜ່ານ @if(!$user->exists)<span class="text-red-500">*</span>@endif
            </label>
            <div class="relative">
              <input name="password" :type="showPass ? 'text' : 'password'"
                     placeholder="{{ $user->exists ? '••••••••' : 'ຢ່າງໜ້ອຍ 8 ໂຕ' }}"
                     @if(!$user->exists) required @endif
                     class="w-full px-3 py-2.5 pr-10 text-sm bg-surface-container-low border border-surface-container-high rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary/50 transition-all @error('password') border-red-300 @enderror" />
              <button type="button" @click="showPass = !showPass"
                      class="absolute right-3 top-1/2 -translate-y-1/2 text-outline hover:text-on-surface transition-colors">
                <i :class="showPass ? 'fas fa-eye-slash' : 'fas fa-eye'" class="text-xs"></i>
              </button>
            </div>
            @error('password') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
          </div>
          <div>
            <label class="block text-xs font-semibold text-on-surface-variant mb-1.5">
              ຢືນຍັນລະຫັດຜ່ານ @if(!$user->exists)<span class="text-red-500">*</span>@endif
            </label>
            <div class="relative">
              <input name="password_confirmation" :type="showConfirm ? 'text' : 'password'"
                     placeholder="••••••••"
                     @if(!$user->exists) required @endif
                     class="w-full px-3 py-2.5 pr-10 text-sm bg-surface-container-low border border-surface-container-high rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary/50 transition-all" />
              <button type="button" @click="showConfirm = !showConfirm"
                      class="absolute right-3 top-1/2 -translate-y-1/2 text-outline hover:text-on-surface transition-colors">
                <i :class="showConfirm ? 'fas fa-eye-slash' : 'fas fa-eye'" class="text-xs"></i>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Actions --}}
    <div class="flex items-center justify-between gap-3">
      <a href="{{ route('admin.users.index') }}"
         class="px-5 py-2.5 text-sm text-on-surface-variant bg-surface-container-low border border-surface-container-high rounded-lg hover:bg-surface-container transition-colors">
        ຍົກເລີກ
      </a>
      <button type="submit"
              class="px-6 py-2.5 text-sm font-semibold text-white primary-gradient rounded-lg hover:opacity-90 active:scale-[0.98] transition-all">
        <i class="fas fa-save mr-1.5 text-xs"></i>
        {{ $user->exists ? 'ບັນທຶກການປ່ຽນແປງ' : 'ສ້າງຜູ້ໃຊ້' }}
      </button>
    </div>

  </form>
</div>

@endsection
