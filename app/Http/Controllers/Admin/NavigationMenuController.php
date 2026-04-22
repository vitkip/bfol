<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NavigationMenu;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class NavigationMenuController extends Controller
{
    public function index()
    {
        $menus = NavigationMenu::with('children')
            ->topLevel()
            ->orderBy('sort_order')
            ->get();

        return view('admin.navigation.index', compact('menus'));
    }

    public function create()
    {
        $parents = NavigationMenu::topLevel()->orderBy('sort_order')->get();
        $pages   = Page::published()->select('id', 'slug', 'title_lo', 'title_en')->orderBy('title_lo')->get();
        return view('admin.navigation.create', compact('parents', 'pages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'label_lo'   => 'required|string|max:150',
            'label_en'   => 'nullable|string|max:150',
            'label_zh'   => 'nullable|string|max:150',
            'url'        => 'nullable|string|max:300',
            'target'     => 'in:_self,_blank',
            'icon'       => 'nullable|string|max:100',
            'parent_id'  => 'nullable|exists:navigation_menus,id',
            'sort_order' => 'nullable|integer|min:0|max:9999',
            'is_active'  => 'nullable|boolean',
        ]);

        NavigationMenu::create([
            'label_lo'   => $request->label_lo,
            'label_en'   => $request->label_en,
            'label_zh'   => $request->label_zh,
            'url'        => $request->url,
            'target'     => $request->input('target', '_self'),
            'icon'       => $request->icon,
            'parent_id'  => $request->parent_id ?: null,
            'sort_order' => (int) $request->input('sort_order', 0),
            'is_active'  => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.navigation.index')
            ->with('success', 'ເພີ່ມລາຍການເມນູສຳເລັດ');
    }

    public function edit(NavigationMenu $navigation)
    {
        $parents = NavigationMenu::topLevel()
            ->where('id', '!=', $navigation->id)
            ->orderBy('sort_order')
            ->get();
        $pages = Page::published()->select('id', 'slug', 'title_lo', 'title_en')->orderBy('title_lo')->get();

        return view('admin.navigation.edit', compact('navigation', 'parents', 'pages'));
    }

    public function update(Request $request, NavigationMenu $navigation)
    {
        $request->validate([
            'label_lo'   => 'required|string|max:150',
            'label_en'   => 'nullable|string|max:150',
            'label_zh'   => 'nullable|string|max:150',
            'url'        => 'nullable|string|max:300',
            'target'     => 'in:_self,_blank',
            'icon'       => 'nullable|string|max:100',
            'parent_id'  => ['nullable', Rule::exists('navigation_menus', 'id'), Rule::notIn([$navigation->id])],
            'sort_order' => 'nullable|integer|min:0|max:9999',
            'is_active'  => 'nullable|boolean',
        ]);

        $navigation->update([
            'label_lo'   => $request->label_lo,
            'label_en'   => $request->label_en,
            'label_zh'   => $request->label_zh,
            'url'        => $request->url,
            'target'     => $request->input('target', '_self'),
            'icon'       => $request->icon,
            'parent_id'  => $request->parent_id ?: null,
            'sort_order' => (int) $request->input('sort_order', 0),
            'is_active'  => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.navigation.index')
            ->with('success', 'ແກ້ໄຂເມນູສຳເລັດ');
    }

    public function destroy(NavigationMenu $navigation)
    {
        if ($navigation->children()->count() > 0) {
            return back()->with('error', 'ບໍ່ສາມາດລຶບໄດ້: ມີເມນູຍ່ອຍຢູ່ (' . $navigation->children()->count() . ' ລາຍການ)');
        }

        $navigation->delete();

        return redirect()->route('admin.navigation.index')
            ->with('success', 'ລຶບເມນູສຳເລັດ');
    }
}
