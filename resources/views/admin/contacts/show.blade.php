@extends('admin.layouts.app')

@section('page_title', 'ຂໍ້ຄວາມຈາກ ' . $contact->name)

@section('content')
<div class="max-w-3xl mx-auto space-y-5">

  {{-- Breadcrumb --}}
  <div class="flex items-center gap-2 text-xs text-outline">
    <a href="{{ route('admin.contacts.index') }}" class="hover:text-primary">ຂໍ້ຄວາມຕິດຕໍ່</a>
    <i class="fas fa-chevron-right text-[10px]"></i>
    <span class="truncate max-w-[220px]">{{ $contact->name }}</span>
  </div>

  @if(session('success'))
    <div class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-700 rounded-xl px-4 py-3 text-sm">
      <i class="fas fa-check-circle text-green-500"></i> {{ session('success') }}
    </div>
  @endif

  {{-- Header card --}}
  <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
    <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">
      <div class="flex items-start gap-4">
        {{-- Avatar --}}
        <div class="w-12 h-12 primary-gradient rounded-full flex items-center justify-center shrink-0">
          <span class="text-white text-lg font-bold">{{ mb_strtoupper(mb_substr($contact->name, 0, 1)) }}</span>
        </div>
        <div>
          <h1 class="text-base font-bold text-on-surface">{{ $contact->name }}</h1>
          <a href="mailto:{{ $contact->email }}" class="text-sm text-primary hover:underline">{{ $contact->email }}</a>
          @if($contact->phone)
            <p class="text-xs text-outline mt-0.5">
              <i class="fas fa-phone text-[10px]"></i> {{ $contact->phone }}
            </p>
          @endif
          <div class="flex flex-wrap items-center gap-2 mt-2">
            @php $lang = $languages[$contact->language] ?? $languages['lo']; @endphp
            <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-bold rounded-full {{ $lang['class'] }}">
              {{ $lang['label'] }}
            </span>
            @if($contact->is_read)
              <span class="inline-flex items-center gap-1 px-2 py-0.5 text-[10px] font-semibold rounded-full bg-green-100 text-green-700">
                <i class="fas fa-check text-[8px]"></i> ອ່ານແລ້ວ
              </span>
            @else
              <span class="inline-flex items-center gap-1 px-2 py-0.5 text-[10px] font-semibold rounded-full bg-blue-100 text-blue-700">
                <i class="fas fa-circle text-[7px]"></i> ໃໝ່
              </span>
            @endif
            <span class="text-[11px] text-outline">
              <i class="fas fa-clock text-[9px]"></i> {{ $contact->created_at->format('d/m/Y H:i') }}
            </span>
          </div>
        </div>
      </div>

      {{-- Actions --}}
      <div class="flex flex-wrap items-center gap-2 shrink-0">
        {{-- Reply via email --}}
        <a href="mailto:{{ $contact->email }}?subject=Re: {{ rawurlencode($contact->subject ?? 'ຕອບກັບຂໍ້ຄວາມຂອງທ່ານ') }}"
           class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg primary-gradient text-white text-sm font-semibold hover:opacity-90 transition">
          <i class="fas fa-reply text-xs"></i> ຕອບ (Email)
        </a>
        {{-- Toggle read --}}
        <form action="{{ route('admin.contacts.read', $contact) }}" method="POST">
          @csrf @method('PATCH')
          <button type="submit"
                  class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg border border-surface-container-high text-sm font-semibold hover:bg-surface-container transition-colors">
            @if($contact->is_read)
              <i class="fas fa-envelope text-xs text-blue-500"></i> ໝາຍວ່າຍັງບໍ່ໄດ້ອ່ານ
            @else
              <i class="fas fa-envelope-open text-xs text-green-500"></i> ໝາຍວ່າອ່ານແລ້ວ
            @endif
          </button>
        </form>
        {{-- Delete --}}
        <form action="{{ route('admin.contacts.destroy', $contact) }}" method="POST"
              onsubmit="return confirm('ລຶບຂໍ້ຄວາມຈາກ «{{ $contact->name }}» ແທ້ບໍ?')">
          @csrf @method('DELETE')
          <button type="submit"
                  class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg border border-red-200 text-sm font-semibold text-red-600 hover:bg-red-50 transition-colors">
            <i class="fas fa-trash text-xs"></i> ລຶບ
          </button>
        </form>
      </div>
    </div>
  </div>

  {{-- Message body --}}
  <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
    @if($contact->subject)
      <h2 class="text-base font-bold text-on-surface mb-4 pb-3 border-b border-surface-container-high">
        {{ $contact->subject }}
      </h2>
    @endif
    <div class="text-sm text-on-surface leading-relaxed whitespace-pre-wrap">{{ $contact->message }}</div>
  </div>

  {{-- Meta info --}}
  <div class="bg-surface-container-lowest rounded-xl shadow-[0px_2px_8px_rgba(26,28,29,0.06)] p-5">
    <h3 class="font-bold text-sm text-on-surface mb-3 flex items-center gap-2">
      <i class="fas fa-info-circle text-primary text-xs"></i> ຂໍ້ມູນເພີ່ມເຕີມ
    </h3>
    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-3">
      <div>
        <dt class="text-[10px] text-outline uppercase tracking-wide">ວັນ-ເວລາສົ່ງ</dt>
        <dd class="text-sm text-on-surface-variant mt-0.5">{{ $contact->created_at->format('d/m/Y H:i:s') }}</dd>
      </div>
      <div>
        <dt class="text-[10px] text-outline uppercase tracking-wide">ຜ່ານໄປ</dt>
        <dd class="text-sm text-on-surface-variant mt-0.5">{{ $contact->created_at->diffForHumans() }}</dd>
      </div>
      <div>
        <dt class="text-[10px] text-outline uppercase tracking-wide">ພາສາທີ່ໃຊ້</dt>
        <dd class="text-sm text-on-surface-variant mt-0.5">{{ $lang['label'] }}</dd>
      </div>
      @if($contact->ip_address)
      <div>
        <dt class="text-[10px] text-outline uppercase tracking-wide">IP Address</dt>
        <dd class="text-sm text-on-surface-variant mt-0.5 font-mono">{{ $contact->ip_address }}</dd>
      </div>
      @endif
    </dl>
  </div>

  {{-- Navigation --}}
  <div class="flex items-center justify-between">
    <a href="{{ route('admin.contacts.index') }}"
       class="flex items-center gap-2 text-sm text-on-surface-variant hover:text-primary transition-colors">
      <i class="fas fa-arrow-left text-xs"></i> ກັບໄປລາຍການ
    </a>
  </div>

</div>
@endsection
