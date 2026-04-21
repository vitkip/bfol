<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MonkExchangeProgram;
use App\Models\PartnerOrganization;
use Illuminate\Http\Request;

class MonkProgramController extends Controller
{
    private const STATUSES = [
        'draft'     => ['lo' => 'ຮ່າງ',          'class' => 'bg-gray-100 text-gray-500',   'icon' => 'fa-circle'],
        'open'      => ['lo' => 'ເປີດສະໝັກ',    'class' => 'bg-green-100 text-green-700', 'icon' => 'fa-door-open'],
        'closed'    => ['lo' => 'ປິດສະໝັກ',     'class' => 'bg-amber-100 text-amber-700', 'icon' => 'fa-door-closed'],
        'ongoing'   => ['lo' => 'ກຳລັງດຳເນີນ', 'class' => 'bg-blue-100 text-blue-700',   'icon' => 'fa-spinner'],
        'completed' => ['lo' => 'ສຳເລັດແລ້ວ',   'class' => 'bg-teal-100 text-teal-700',   'icon' => 'fa-check-circle'],
        'cancelled' => ['lo' => 'ຍົກເລີກ',       'class' => 'bg-red-100 text-red-600',     'icon' => 'fa-ban'],
    ];

    public function index(Request $request)
    {
        $query = MonkExchangeProgram::with('partnerOrganization')
                                    ->latest('year')->latest('id');

        if ($s = $request->search) {
            $query->where(function ($q) use ($s) {
                $q->where('title_lo', 'like', "%{$s}%")
                  ->orWhere('title_en', 'like', "%{$s}%")
                  ->orWhere('destination_country', 'like', "%{$s}%");
            });
        }
        if ($request->status) { $query->where('status', $request->status); }
        if ($request->year)   { $query->where('year',   $request->year); }

        $programs = $query->paginate(15)->withQueryString();
        $years    = MonkExchangeProgram::distinct()->orderByDesc('year')->pluck('year');

        return view('admin.monk-programs.index', [
            'programs' => $programs,
            'statuses' => self::STATUSES,
            'years'    => $years,
        ]);
    }

    public function create()
    {
        $partners = PartnerOrganization::orderBy('name_lo')->get(['id', 'name_lo', 'acronym']);
        return view('admin.monk-programs.create', [
            'partners' => $partners,
            'statuses' => self::STATUSES,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title_lo'             => 'required|string|max:300',
            'title_en'             => 'nullable|string|max:300',
            'title_zh'             => 'nullable|string|max:300',
            'destination_country'  => 'required|string|max:100',
            'partner_org_id'       => 'nullable|exists:partner_organizations,id',
            'year'                 => 'required|integer|min:2000|max:' . (date('Y') + 5),
            'application_open'     => 'nullable|date',
            'application_deadline' => 'nullable|date|after_or_equal:application_open',
            'program_start'        => 'nullable|date',
            'program_end'          => 'nullable|date|after_or_equal:program_start',
            'monks_quota'          => 'nullable|integer|min:1|max:9999',
            'monks_selected'       => 'nullable|integer|min:0|max:9999',
            'description_lo'       => 'nullable|string',
            'description_en'       => 'nullable|string',
            'description_zh'       => 'nullable|string',
            'requirements_lo'      => 'nullable|string',
            'requirements_en'      => 'nullable|string',
            'requirements_zh'      => 'nullable|string',
            'application_url'      => 'nullable|url|max:500',
            'contact_email'        => 'nullable|email|max:120',
            'status'               => 'required|in:draft,open,closed,ongoing,completed,cancelled',
            'is_featured'          => 'boolean',
        ]);

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['author_id']   = auth()->id();

        MonkExchangeProgram::create($validated);

        return redirect()->route('admin.monk-programs.index')
                         ->with('success', 'ເພີ່ມໂຄງການສຳເລັດ');
    }

    public function show(MonkExchangeProgram $monkProgram)
    {
        $monkProgram->load('partnerOrganization', 'author');
        return view('admin.monk-programs.show', [
            'program'  => $monkProgram,
            'statuses' => self::STATUSES,
        ]);
    }

    public function edit(MonkExchangeProgram $monkProgram)
    {
        $partners = PartnerOrganization::orderBy('name_lo')->get(['id', 'name_lo', 'acronym']);
        return view('admin.monk-programs.edit', [
            'program'  => $monkProgram,
            'partners' => $partners,
            'statuses' => self::STATUSES,
        ]);
    }

    public function update(Request $request, MonkExchangeProgram $monkProgram)
    {
        $validated = $request->validate([
            'title_lo'             => 'required|string|max:300',
            'title_en'             => 'nullable|string|max:300',
            'title_zh'             => 'nullable|string|max:300',
            'destination_country'  => 'required|string|max:100',
            'partner_org_id'       => 'nullable|exists:partner_organizations,id',
            'year'                 => 'required|integer|min:2000|max:' . (date('Y') + 5),
            'application_open'     => 'nullable|date',
            'application_deadline' => 'nullable|date|after_or_equal:application_open',
            'program_start'        => 'nullable|date',
            'program_end'          => 'nullable|date|after_or_equal:program_start',
            'monks_quota'          => 'nullable|integer|min:1|max:9999',
            'monks_selected'       => 'nullable|integer|min:0|max:9999',
            'description_lo'       => 'nullable|string',
            'description_en'       => 'nullable|string',
            'description_zh'       => 'nullable|string',
            'requirements_lo'      => 'nullable|string',
            'requirements_en'      => 'nullable|string',
            'requirements_zh'      => 'nullable|string',
            'application_url'      => 'nullable|url|max:500',
            'contact_email'        => 'nullable|email|max:120',
            'status'               => 'required|in:draft,open,closed,ongoing,completed,cancelled',
            'is_featured'          => 'boolean',
        ]);

        $validated['is_featured'] = $request->boolean('is_featured');

        $monkProgram->update($validated);

        return redirect()->route('admin.monk-programs.show', $monkProgram)
                         ->with('success', 'ອັບເດດໂຄງການສຳເລັດ');
    }

    public function destroy(MonkExchangeProgram $monkProgram)
    {
        $monkProgram->delete();

        return redirect()->route('admin.monk-programs.index')
                         ->with('success', 'ລຶບໂຄງການສຳເລັດ');
    }
}
