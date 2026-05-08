@foreach($menus as $menu)
<tr class="hover:bg-surface-container-low transition-colors {{ $depth === 0 ? 'bg-blue-50/30' : '' }}">
  <td class="px-4 py-{{ $depth === 0 ? '3' : '2' }} text-outline text-xs">{{ $menu->id }}</td>
  <td class="px-4 py-{{ $depth === 0 ? '3' : '2' }}">
    <div class="flex items-center gap-2" style="padding-left:{{ $depth * 20 }}px">
      @if($depth > 0)
        <i class="fas fa-level-up-alt fa-rotate-90 text-outline text-[10px] shrink-0"></i>
      @endif
      @if($menu->icon)
        <i class="{{ $menu->icon }} {{ $depth === 0 ? 'text-blue-600' : 'text-slate-400' }} w-4 text-center text-xs shrink-0"></i>
      @else
        <i class="fas fa-bars {{ $depth === 0 ? 'text-blue-300' : 'text-slate-200' }} w-4 text-center text-xs shrink-0"></i>
      @endif
      <div>
        <p class="{{ $depth === 0 ? 'font-semibold text-on-surface' : 'text-on-surface text-sm' }}">
          {{ $menu->label_lo }}
        </p>
        @if($menu->label_en)
          <p class="text-xs text-outline">{{ $menu->label_en }}</p>
        @endif
      </div>
    </div>
  </td>
  <td class="px-4 py-{{ $depth === 0 ? '3' : '2' }} hidden md:table-cell">
    @if($menu->url)
      <code class="text-xs bg-surface-container px-1.5 py-0.5 rounded text-on-surface-variant">{{ $menu->url }}</code>
    @else
      <span class="text-xs text-outline italic">dropdown</span>
    @endif
  </td>
  <td class="px-4 py-{{ $depth === 0 ? '3' : '2' }} text-center hidden lg:table-cell text-xs text-outline">
    {{ $menu->sort_order }}
  </td>
  <td class="px-4 py-{{ $depth === 0 ? '3' : '2' }} text-center">
    @if($menu->is_active)
      <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-semibold rounded-full bg-green-100 text-green-700">
        <i class="fas fa-circle text-[6px]"></i> ເປີດ
      </span>
    @else
      <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-semibold rounded-full bg-gray-100 text-gray-500">
        <i class="fas fa-circle text-[6px]"></i> ປິດ
      </span>
    @endif
  </td>
  <td class="px-4 py-{{ $depth === 0 ? '3' : '2' }} text-right">
    <div class="flex items-center justify-end gap-3">
      <a href="{{ route('admin.navigation.edit', $menu) }}"
         class="inline-flex items-center gap-1 text-xs font-semibold text-yellow-600 hover:underline">
        <i class="fas fa-edit"></i><span class="hidden sm:inline">ແກ້ໄຂ</span>
      </a>
      <form action="{{ route('admin.navigation.destroy', $menu) }}" method="POST" class="inline-block"
            onsubmit="return confirm('ລຶບ «{{ $menu->label_lo }}» ແທ້ບໍ?')">
        @csrf @method('DELETE')
        <button type="submit" class="inline-flex items-center gap-1 text-xs font-semibold text-red-600 hover:underline">
          <i class="fas fa-trash"></i><span class="hidden sm:inline">ລຶບ</span>
        </button>
      </form>
    </div>
  </td>
</tr>
@if($menu->children->isNotEmpty())
  @include('admin.navigation._menu_tree', ['menus' => $menu->children, 'depth' => $depth + 1])
@endif
@endforeach
