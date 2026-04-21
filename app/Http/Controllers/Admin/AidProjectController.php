<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AidProject;
use App\Models\PartnerOrganization;
use Illuminate\Http\Request;

class AidProjectController extends Controller
{
    private const TYPES = [
        'religious'    => ['lo' => 'ສາດສະໜາ',    'icon' => 'fa-dharmachakra',  'class' => 'bg-amber-100 text-amber-700'],
        'humanitarian' => ['lo' => 'ມະນຸດສະທຳ', 'icon' => 'fa-hands-helping', 'class' => 'bg-red-100 text-red-600'],
        'educational'  => ['lo' => 'ການສຶກສາ',   'icon' => 'fa-graduation-cap', 'class' => 'bg-blue-100 text-blue-700'],
        'cultural'     => ['lo' => 'ວັດທະນະທຳ',  'icon' => 'fa-theater-masks',  'class' => 'bg-purple-100 text-purple-700'],
        'other'        => ['lo' => 'ອື່ນໆ',        'icon' => 'fa-ellipsis-h',    'class' => 'bg-gray-100 text-gray-500'],
    ];

    private const STATUSES = [
        'planning'  => ['lo' => 'ວາງແຜນ',     'class' => 'bg-gray-100 text-gray-500',   'icon' => 'fa-drafting-compass'],
        'active'    => ['lo' => 'ດຳເນີນຢູ່',  'class' => 'bg-blue-100 text-blue-700',   'icon' => 'fa-spinner'],
        'completed' => ['lo' => 'ສຳເລັດ',      'class' => 'bg-green-100 text-green-700', 'icon' => 'fa-check-circle'],
        'suspended' => ['lo' => 'ຢຸດຊົ່ວຄາວ', 'class' => 'bg-amber-100 text-amber-700', 'icon' => 'fa-pause-circle'],
        'cancelled' => ['lo' => 'ຍົກເລີກ',     'class' => 'bg-red-100 text-red-600',     'icon' => 'fa-ban'],
    ];

    public function index(Request $request)
    {
        $query = AidProject::with('partnerOrganization')->latest('start_date')->latest('id');

        if ($s = $request->search) {
            $query->where(function ($q) use ($s) {
                $q->where('title_lo', 'like', "%{$s}%")
                  ->orWhere('title_en', 'like', "%{$s}%")
                  ->orWhere('country',  'like', "%{$s}%");
            });
        }
        if ($request->status) { $query->where('status', $request->status); }
        if ($request->type)   { $query->where('type',   $request->type); }

        $projects = $query->paginate(15)->withQueryString();

        return view('admin.aid-projects.index', [
            'projects' => $projects,
            'types'    => self::TYPES,
            'statuses' => self::STATUSES,
        ]);
    }

    public function create()
    {
        $partners = PartnerOrganization::orderBy('name_lo')->get(['id', 'name_lo', 'acronym']);
        return view('admin.aid-projects.create', [
            'partners' => $partners,
            'types'    => self::TYPES,
            'statuses' => self::STATUSES,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title_lo'       => 'required|string|max:300',
            'title_en'       => 'nullable|string|max:300',
            'title_zh'       => 'nullable|string|max:300',
            'country'        => 'required|string|max:100',
            'partner_org_id' => 'nullable|exists:partner_organizations,id',
            'type'           => 'required|in:religious,humanitarian,educational,cultural,other',
            'description_lo' => 'nullable|string',
            'description_en' => 'nullable|string',
            'description_zh' => 'nullable|string',
            'budget_usd'     => 'nullable|numeric|min:0|max:999999999',
            'start_date'     => 'nullable|date',
            'end_date'       => 'nullable|date|after_or_equal:start_date',
            'status'         => 'required|in:planning,active,completed,suspended,cancelled',
            'report_url'     => 'nullable|url|max:500',
        ]);

        $validated['author_id'] = auth()->id();

        AidProject::create($validated);

        return redirect()->route('admin.aid-projects.index')
                         ->with('success', 'ເພີ່ມໂຄງການຊ່ວຍເຫຼືອສຳເລັດ');
    }

    public function show(AidProject $aidProject)
    {
        $aidProject->load('partnerOrganization', 'author');
        return view('admin.aid-projects.show', [
            'project'  => $aidProject,
            'types'    => self::TYPES,
            'statuses' => self::STATUSES,
        ]);
    }

    public function edit(AidProject $aidProject)
    {
        $partners = PartnerOrganization::orderBy('name_lo')->get(['id', 'name_lo', 'acronym']);
        return view('admin.aid-projects.edit', [
            'project'  => $aidProject,
            'partners' => $partners,
            'types'    => self::TYPES,
            'statuses' => self::STATUSES,
        ]);
    }

    public function update(Request $request, AidProject $aidProject)
    {
        $validated = $request->validate([
            'title_lo'       => 'required|string|max:300',
            'title_en'       => 'nullable|string|max:300',
            'title_zh'       => 'nullable|string|max:300',
            'country'        => 'required|string|max:100',
            'partner_org_id' => 'nullable|exists:partner_organizations,id',
            'type'           => 'required|in:religious,humanitarian,educational,cultural,other',
            'description_lo' => 'nullable|string',
            'description_en' => 'nullable|string',
            'description_zh' => 'nullable|string',
            'budget_usd'     => 'nullable|numeric|min:0|max:999999999',
            'start_date'     => 'nullable|date',
            'end_date'       => 'nullable|date|after_or_equal:start_date',
            'status'         => 'required|in:planning,active,completed,suspended,cancelled',
            'report_url'     => 'nullable|url|max:500',
        ]);

        $aidProject->update($validated);

        return redirect()->route('admin.aid-projects.show', $aidProject)
                         ->with('success', 'ອັບເດດໂຄງການສຳເລັດ');
    }

    public function destroy(AidProject $aidProject)
    {
        $aidProject->delete();

        return redirect()->route('admin.aid-projects.index')
                         ->with('success', 'ລຶບໂຄງການສຳເລັດ');
    }
}
