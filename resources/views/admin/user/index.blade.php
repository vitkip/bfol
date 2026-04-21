@extends('admin.layouts.app')

@section('page_title', 'ຜູ້ໃຊ້ລະບົບ')

@section('content')

@php
  $roles = [
    'superadmin' => ['label' => 'Super Admin', 'class' => 'bg-red-100 text-red-700'],
    'admin'      => ['label' => 'Admin',        'class' => 'bg-blue-100 text-blue-700'],
    'editor'     => ['label' => 'Editor',       'class' => 'bg-green-100 text-green-700'],
    'viewer'     => ['label' => 'Viewer',       'class' => 'bg-surface-container-high text-outline'],
  ];
@endphp

{{-- Header --}}
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
  <div>
    <h2 class="text-base font-bold text-on-surface">ຜູ້ໃຊ້ທັງໝົດ</h2>
    <p class="text-xs text-outline mt-0.5">{{ $users->total() }} ຄົນ</p>
  </div>
  <a href="{{ route('admin.users.create') }}"
     class="inline-flex items-center gap-2 text-sm font-semibold text-white primary-gradient px-4 py-2 rounded-lg hover:opacity-90 transition-opacity whitespace-nowrap">
    <i class="fas fa-user-plus text-xs"></i> ເພີ່ມຜູ້ໃຊ້ໃໝ່
  </a>
</div>

{{-- Filters --}}
<form method="GET" class="flex flex-col sm:flex-row gap-2 mb-5">
  <div class="relative flex-1">
    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-outline text-xs"></i>
    <input name="search" value="{{ request('search') }}" placeholder="ຄົ້ນຫາຊື່, email, username…"
           class="w-full pl-8 pr-3 py-2 text-sm bg-surface-container-lowest border border-surface-container-high rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/30" />
  </div>
  <select name="role" class="text-sm bg-surface-container-lowest border border-surface-container-high rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary/30">
    <option value="">ທຸກ Role</option>
    @foreach($roles as $val => $r)
      <option value="{{ $val }}" @selected(request('role') === $val)>{{ $r['label'] }}</option>
    @endforeach
  </select>
  <select name="status" class="text-sm bg-surface-container-lowest border border-surface-container-high rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary/30">
    <option value="">ທຸກສະຖານະ</option>
    <option value="active"   @selected(request('status') === 'active')>ໃຊ້ງານ</option>
    <option value="inactive" @selected(request('status') === 'inactive')>ປິດໃຊ້ງານ</option>
  </select>
  <button type="submit" class="px-4 py-2 text-sm font-semibold primary-gradient text-white rounded-lg hover:opacity-90 transition-opacity whitespace-nowrap">
    ຄົ້ນຫາ
  </button>
  @if(request()->hasAny(['search','role','status']))
    <a href="{{ route('admin.users.index') }}" class="px-4 py-2 text-sm text-outline bg-surface-container-low border border-surface-container-high rounded-lg hover:bg-surface-container transition-colors whitespace-nowrap">
      ລ້າງ
    </a>
  @endif
</form>

{{-- Table --}}
<div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] overflow-hidden">
  <div class="overflow-x-auto">
    <table class="w-full text-sm">
      <thead>
        <tr class="border-b border-surface-container-high bg-surface-container-low text-left">
          <th class="px-4 sm:px-5 py-3 font-semibold text-on-surface-variant text-xs uppercase tracking-wide whitespace-nowrap">ຜູ້ໃຊ້</th>
          <th class="px-4 py-3 font-semibold text-on-surface-variant text-xs uppercase tracking-wide whitespace-nowrap hidden sm:table-cell">Username</th>
          <th class="px-4 py-3 font-semibold text-on-surface-variant text-xs uppercase tracking-wide whitespace-nowrap">Role</th>
          <th class="px-4 py-3 font-semibold text-on-surface-variant text-xs uppercase tracking-wide whitespace-nowrap hidden md:table-cell">ສະຖານະ</th>
          <th class="px-4 py-3 font-semibold text-on-surface-variant text-xs uppercase tracking-wide whitespace-nowrap hidden lg:table-cell">ເຂົ້າລະບົບລ້າສຸດ</th>
          <th class="px-4 sm:px-5 py-3 text-right font-semibold text-on-surface-variant text-xs uppercase tracking-wide whitespace-nowrap">ຈັດການ</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-surface-container-high">
        @forelse($users as $user)
          <tr class="hover:bg-surface-container-low transition-colors">
            {{-- Avatar + Name --}}
            <td class="px-4 sm:px-5 py-3">
              <div class="flex items-center gap-3">
                <div class="w-8 h-8 primary-gradient rounded-full flex items-center justify-center flex-shrink-0">
                  <span class="text-white text-xs font-bold">{{ mb_substr($user->full_name_lo, 0, 1) }}</span>
                </div>
                <div class="min-w-0">
                  <p class="font-semibold text-on-surface truncate">{{ $user->full_name_lo }}</p>
                  <p class="text-xs text-outline truncate">{{ $user->email }}</p>
                </div>
              </div>
            </td>
            {{-- Username --}}
            <td class="px-4 py-3 hidden sm:table-cell">
              <span class="text-xs font-mono text-on-surface-variant bg-surface-container-low px-2 py-0.5 rounded">{{ $user->username }}</span>
            </td>
            {{-- Role --}}
            <td class="px-4 py-3">
              <span class="text-[11px] font-semibold px-2.5 py-0.5 rounded-full {{ $roles[$user->role]['class'] ?? 'bg-surface-container-high text-outline' }}">
                {{ $roles[$user->role]['label'] ?? $user->role }}
              </span>
            </td>
            {{-- Status --}}
            <td class="px-4 py-3 hidden md:table-cell">
              @if($user->is_active)
                <span class="inline-flex items-center gap-1 text-[11px] font-semibold px-2.5 py-0.5 rounded-full bg-green-100 text-green-700">
                  <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> ໃຊ້ງານ
                </span>
              @else
                <span class="inline-flex items-center gap-1 text-[11px] font-semibold px-2.5 py-0.5 rounded-full bg-surface-container-high text-outline">
                  <span class="w-1.5 h-1.5 rounded-full bg-outline/40"></span> ປິດ
                </span>
              @endif
            </td>
            {{-- Last login --}}
            <td class="px-4 py-3 hidden lg:table-cell text-xs text-outline whitespace-nowrap">
              {{ $user->last_login?->diffForHumans() ?? '—' }}
            </td>
            {{-- Actions --}}
            <td class="px-4 sm:px-5 py-3 text-right">
              <div class="inline-flex items-center gap-1">
                <a href="{{ route('admin.users.edit', $user) }}"
                   class="p-1.5 text-outline hover:text-primary hover:bg-surface-container rounded-lg transition-colors" title="ແກ້ໄຂ">
                  <i class="fas fa-pen text-xs"></i>
                </a>
                @if($user->id !== auth()->id())
                  <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                        onsubmit="return confirm('ລົບຜູ້ໃຊ້ {{ addslashes($user->full_name_lo) }} ແທ້ບໍ?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="p-1.5 text-outline hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors" title="ລົບ">
                      <i class="fas fa-trash text-xs"></i>
                    </button>
                  </form>
                @else
                  <span class="p-1.5 text-surface-container-high cursor-not-allowed" title="ບໍ່ສາມາດລົບຕົນເອງ">
                    <i class="fas fa-trash text-xs"></i>
                  </span>
                @endif
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" class="px-5 py-16 text-center text-sm text-outline">
              <i class="fas fa-users text-3xl mb-3 block opacity-20"></i>
              ບໍ່ພົບຜູ້ໃຊ້
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{-- Pagination --}}
  @if($users->hasPages())
    <div class="px-5 py-4 border-t border-surface-container-high flex flex-col sm:flex-row items-center justify-between gap-3 text-sm">
      <p class="text-xs text-outline">
        ສະແດງ {{ $users->firstItem() }}–{{ $users->lastItem() }} ຈາກ {{ $users->total() }} ລາຍການ
      </p>
      <div class="flex items-center gap-1">
        @if($users->onFirstPage())
          <span class="px-3 py-1.5 rounded-lg text-outline bg-surface-container-low cursor-not-allowed text-xs">← ກ່ອນ</span>
        @else
          <a href="{{ $users->previousPageUrl() }}" class="px-3 py-1.5 rounded-lg text-on-surface-variant bg-surface-container-low hover:bg-surface-container transition-colors text-xs">← ກ່ອນ</a>
        @endif

        @foreach($users->getUrlRange(max(1, $users->currentPage()-2), min($users->lastPage(), $users->currentPage()+2)) as $page => $url)
          <a href="{{ $url }}"
             class="px-3 py-1.5 rounded-lg text-xs transition-colors {{ $page === $users->currentPage() ? 'primary-gradient text-white' : 'text-on-surface-variant bg-surface-container-low hover:bg-surface-container' }}">
            {{ $page }}
          </a>
        @endforeach

        @if($users->hasMorePages())
          <a href="{{ $users->nextPageUrl() }}" class="px-3 py-1.5 rounded-lg text-on-surface-variant bg-surface-container-low hover:bg-surface-container transition-colors text-xs">ຕໍ່ →</a>
        @else
          <span class="px-3 py-1.5 rounded-lg text-outline bg-surface-container-low cursor-not-allowed text-xs">ຕໍ່ →</span>
        @endif
      </div>
    </div>
  @endif
</div>

@endsection
