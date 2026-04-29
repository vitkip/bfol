@extends('admin.layouts.app')

@section('page_title', 'ສະມາຊິກກຳມະການ')

@section('content')

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
  <div>
    <h2 class="text-base font-bold text-on-surface">ສະມາຊິກກຳມະການ</h2>
    <p class="text-xs text-outline mt-0.5">{{ $counts['all'] }} ລາຍການ</p>
  </div>
  <div class="flex items-center gap-2">
    <a href="{{ route('admin.departments.index') }}"
       class="inline-flex items-center gap-2 text-sm font-semibold text-on-surface-variant bg-surface-container-low border border-surface-container-high px-4 py-2 rounded-lg hover:bg-surface-container transition-colors whitespace-nowrap">
      <i class="fas fa-building text-xs"></i> ຈັດການພະແນກ
    </a>
    <a href="{{ route('admin.committee.create') }}"
       class="inline-flex items-center gap-2 text-sm font-semibold text-white primary-gradient px-4 py-2 rounded-lg hover:opacity-90 transition-opacity whitespace-nowrap">
      <i class="fas fa-plus text-xs"></i> ເພີ່ມສະມາຊິກ
    </a>
  </div>
</div>

@if(session('success'))
  <div class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-700 rounded-xl px-4 py-3 mb-5 text-sm">
    <i class="fas fa-check-circle text-green-500"></i> {{ session('success') }}
  </div>
@endif

{{-- Status tabs --}}
<div class="flex items-center gap-1 mb-4 flex-wrap">
  @foreach([''=> ['ທັງໝົດ', $counts['all']], 'active'=> ['ເປີດໃຊ້', $counts['active']], 'inactive'=> ['ປິດໃຊ້', $counts['inactive']]] as $val => [$lbl, $cnt])
    @php
      $isActive = request('status', '') === $val;
      $params   = request()->except('status', 'page');
      if ($val) $params['status'] = $val;
    @endphp
    <a href="{{ route('admin.committee.index', $params) }}"
       class="flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-semibold whitespace-nowrap transition-colors
              {{ $isActive ? 'primary-gradient text-white' : 'bg-surface-container-low text-on-surface-variant hover:bg-surface-container border border-surface-container-high' }}">
      {{ $lbl }}
      <span class="px-1.5 py-0.5 rounded-full text-[10px] font-bold {{ $isActive ? 'bg-white/20 text-white' : 'bg-surface-container-high text-outline' }}">
        {{ $cnt }}
      </span>
    </a>
  @endforeach
</div>

{{-- Search & Department filter --}}
<form method="GET" class="flex flex-col sm:flex-row gap-2 mb-5">
  @if(request('status'))
    <input type="hidden" name="status" value="{{ request('status') }}">
  @endif
  <div class="relative flex-1">
    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-outline text-xs pointer-events-none"></i>
    <input name="search" value="{{ request('search') }}" placeholder="ຄົ້ນຫາຊື່, ຕໍາແໜ່ງ…"
           class="w-full pl-8 pr-3 py-2 text-sm bg-surface-container-lowest border border-surface-container-high rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/30" />
  </div>
  <select name="department" class="text-sm bg-surface-container-lowest border border-surface-container-high rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary/30 min-w-[160px]">
    <option value="">ທຸກພະແນກ</option>
    @foreach($departments as $dept)
      <option value="{{ $dept['id'] }}"
              @selected(request('department') == $dept['id'])
              style="{{ $dept['depth'] > 0 ? 'padding-left:1.25rem;color:#64748b;' : 'font-weight:600;' }}">
        {{ $dept['depth'] > 0 ? '↳ ' : '' }}{{ $dept['label'] }}
      </option>
    @endforeach
  </select>
  <button type="submit" class="px-4 py-2 text-sm font-semibold primary-gradient text-white rounded-lg hover:opacity-90 transition-opacity whitespace-nowrap">
    ຄົ້ນຫາ
  </button>
  @if(request()->hasAny(['search','department']))
    <a href="{{ route('admin.committee.index', request()->only('status')) }}"
       class="px-4 py-2 text-sm text-outline bg-surface-container-low border border-surface-container-high rounded-lg hover:bg-surface-container transition-colors whitespace-nowrap">
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
          <th class="px-5 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide">ສະມາຊິກ</th>
          <th class="px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide hidden md:table-cell">ຕໍາແໜ່ງ</th>
          <th class="px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide hidden lg:table-cell">ພະແນກ</th>
          <th class="px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide hidden sm:table-cell">ຕິດຕໍ່</th>
          <th class="px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide">ສະຖານະ</th>
          <th class="px-5 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide text-right">ຈັດການ</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-surface-container-high">
        @forelse($members as $member)
          <tr class="hover:bg-surface-container-low transition-colors group">

            {{-- Photo + Name --}}
            <td class="px-5 py-3">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full overflow-hidden flex-shrink-0 bg-surface-container-high">
                  @if($member->photo_url)
                    <img src="{{ $member->photo_url }}" alt=""
                         class="w-full h-full object-cover" loading="lazy"
                         onerror="this.parentElement.innerHTML='<div class=\'w-full h-full flex items-center justify-center\'><i class=\'fas fa-user text-outline/30\'></i></div>'" />
                  @else
                    <div class="w-full h-full flex items-center justify-center">
                      <i class="fas fa-user text-outline/30 text-base"></i>
                    </div>
                  @endif
                </div>
                <div class="min-w-0">
                  <p class="font-semibold text-on-surface leading-snug">
                    {{ implode(' ', array_filter([$member->title_lo, $member->first_name_lo ?? $member->name_lo, $member->last_name_lo])) }}
                  </p>
                  @if($member->gender)
                    @php $gMap = ['monk'=>'ພຣະ/ສາມະເນນ','male'=>'ຊາຍ','female'=>'ຍິງ']; @endphp
                    <span class="text-[10px] text-outline">{{ $gMap[$member->gender] ?? '' }}</span>
                  @endif
                </div>
              </div>
            </td>

            {{-- Position --}}
            <td class="px-4 py-3 hidden md:table-cell">
              <p class="text-sm text-on-surface-variant">{{ Str::limit($member->position_lo, 50) }}</p>
            </td>

            {{-- Department --}}
            <td class="px-4 py-3 hidden lg:table-cell">
              @if($member->department)
                <a href="{{ route('admin.departments.edit', $member->department) }}"
                   class="inline-flex items-center gap-1 text-xs px-2 py-0.5 rounded-full bg-primary/10 text-primary hover:bg-primary/20 transition-colors">
                  <i class="fas fa-building text-[10px]"></i>
                  {{ $member->department->name_lo }}
                </a>
              @else
                <span class="text-xs text-outline">—</span>
              @endif
            </td>

            {{-- Contact --}}
            <td class="px-4 py-3 hidden sm:table-cell">
              <div class="space-y-0.5">
                @if($member->phone)
                  <p class="text-xs text-outline"><i class="fas fa-phone text-[10px] mr-1"></i>{{ $member->phone }}</p>
                @endif
                @if($member->email)
                  <p class="text-xs text-outline truncate max-w-[140px]"><i class="fas fa-envelope text-[10px] mr-1"></i>{{ $member->email }}</p>
                @endif
              </div>
            </td>

            {{-- Status --}}
            <td class="px-4 py-3">
              <span class="text-[11px] font-semibold px-2.5 py-0.5 rounded-full
                {{ $member->is_active ? 'bg-green-100 text-green-700' : 'bg-surface-container-high text-outline' }}">
                {{ $member->is_active ? 'ເປີດໃຊ້' : 'ປິດໃຊ້' }}
              </span>
            </td>

            {{-- Actions --}}
            <td class="px-5 py-3 text-right">
              <div class="inline-flex items-center gap-1">
                <a href="{{ route('admin.committee.edit', $member) }}"
                   class="p-1.5 text-outline hover:text-primary hover:bg-surface-container rounded-lg transition-colors" title="ແກ້ໄຂ">
                  <i class="fas fa-pen text-xs"></i>
                </a>
                <form method="POST" action="{{ route('admin.committee.destroy', $member) }}"
                      onsubmit="return confirm('ລົບສະມາຊິກນີ້ແທ້ບໍ?')">
                  @csrf @method('DELETE')
                  <button type="submit" class="p-1.5 text-outline hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors" title="ລົບ">
                    <i class="fas fa-trash text-xs"></i>
                  </button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" class="px-5 py-16 text-center text-sm text-outline">
              <i class="fas fa-users text-4xl mb-3 block opacity-20"></i>
              ບໍ່ພົບສະມາຊິກ
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if($members->hasPages())
    <div class="px-5 py-4 border-t border-surface-container-high flex flex-col sm:flex-row items-center justify-between gap-3">
      <p class="text-xs text-outline">
        ສະແດງ {{ $members->firstItem() }}–{{ $members->lastItem() }} ຈາກ {{ $members->total() }} ລາຍການ
      </p>
      <div class="flex items-center gap-1">
        @if($members->onFirstPage())
          <span class="px-3 py-1.5 rounded-lg text-outline bg-surface-container-low cursor-not-allowed text-xs">←</span>
        @else
          <a href="{{ $members->previousPageUrl() }}" class="px-3 py-1.5 rounded-lg text-on-surface-variant bg-surface-container-low hover:bg-surface-container text-xs transition-colors">←</a>
        @endif
        @foreach($members->getUrlRange(max(1,$members->currentPage()-2), min($members->lastPage(),$members->currentPage()+2)) as $page => $url)
          <a href="{{ $url }}"
             class="px-3 py-1.5 rounded-lg text-xs transition-colors {{ $page === $members->currentPage() ? 'primary-gradient text-white' : 'text-on-surface-variant bg-surface-container-low hover:bg-surface-container' }}">
            {{ $page }}
          </a>
        @endforeach
        @if($members->hasMorePages())
          <a href="{{ $members->nextPageUrl() }}" class="px-3 py-1.5 rounded-lg text-on-surface-variant bg-surface-container-low hover:bg-surface-container text-xs transition-colors">→</a>
        @else
          <span class="px-3 py-1.5 rounded-lg text-outline bg-surface-container-low cursor-not-allowed text-xs">→</span>
        @endif
      </div>
    </div>
  @endif
</div>

@endsection
