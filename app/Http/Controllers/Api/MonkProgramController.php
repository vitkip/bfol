<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MonkExchangeProgram;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MonkProgramController extends Controller
{
    public function index(Request $request)
    {
        $query = MonkExchangeProgram::with(['partnerOrganization'])->latest();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) => $q->where('title_lo', 'like', "%$s%")
                                      ->orWhere('title_en', 'like', "%$s%"));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $programs = $query->paginate($request->get('per_page', 15));

        return response()->json($programs->through(fn($p) => $this->format($p)));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title_lo'             => ['required', 'string', 'max:300'],
            'title_en'             => ['nullable', 'string', 'max:300'],
            'destination_country'  => ['nullable', 'string'],
            'partner_org_id'       => ['nullable', 'exists:partner_organizations,id'],
            'year'                 => ['nullable', 'digits:4'],
            'application_open'     => ['nullable', 'date'],
            'application_deadline' => ['nullable', 'date'],
            'program_start'        => ['nullable', 'date'],
            'program_end'          => ['nullable', 'date'],
            'monks_quota'          => ['nullable', 'integer'],
            'monks_selected'       => ['nullable', 'integer'],
            'description_lo'       => ['nullable', 'string'],
            'description_en'       => ['nullable', 'string'],
            'requirements_lo'      => ['nullable', 'string'],
            'requirements_en'      => ['nullable', 'string'],
            'application_url'      => ['nullable', 'url'],
            'contact_email'        => ['nullable', 'email'],
            'status'               => ['required', Rule::in(['open', 'closed', 'completed'])],
            'is_featured'          => ['boolean'],
        ]);

        $data['author_id'] = $request->user()->id;

        $program = MonkExchangeProgram::create($data);

        return response()->json($this->format($program->fresh('partnerOrganization')), 201);
    }

    public function show(MonkExchangeProgram $monkProgram)
    {
        return response()->json($this->format($monkProgram->load('partnerOrganization')));
    }

    public function update(Request $request, MonkExchangeProgram $monkProgram)
    {
        $data = $request->validate([
            'title_lo'             => ['required', 'string', 'max:300'],
            'title_en'             => ['nullable', 'string', 'max:300'],
            'destination_country'  => ['nullable', 'string'],
            'partner_org_id'       => ['nullable', 'exists:partner_organizations,id'],
            'year'                 => ['nullable', 'digits:4'],
            'application_open'     => ['nullable', 'date'],
            'application_deadline' => ['nullable', 'date'],
            'program_start'        => ['nullable', 'date'],
            'program_end'          => ['nullable', 'date'],
            'monks_quota'          => ['nullable', 'integer'],
            'monks_selected'       => ['nullable', 'integer'],
            'description_lo'       => ['nullable', 'string'],
            'description_en'       => ['nullable', 'string'],
            'requirements_lo'      => ['nullable', 'string'],
            'requirements_en'      => ['nullable', 'string'],
            'application_url'      => ['nullable', 'url'],
            'contact_email'        => ['nullable', 'email'],
            'status'               => ['required', Rule::in(['open', 'closed', 'completed'])],
            'is_featured'          => ['boolean'],
        ]);

        $monkProgram->update($data);

        return response()->json($this->format($monkProgram->fresh('partnerOrganization')));
    }

    public function destroy(MonkExchangeProgram $monkProgram)
    {
        $monkProgram->delete();

        return response()->json(['message' => 'ລຶບໂຄງການສຳເລັດ']);
    }

    private function format(MonkExchangeProgram $p): array
    {
        return [
            'id'                   => $p->id,
            'title_lo'             => $p->title_lo,
            'title_en'             => $p->title_en,
            'destination_country'  => $p->destination_country,
            'partner_org_id'       => $p->partner_org_id,
            'partner'              => $p->partnerOrganization ? [
                'id'   => $p->partnerOrganization->id,
                'name' => $p->partnerOrganization->name_lo ?: $p->partnerOrganization->name_en,
            ] : null,
            'year'                 => $p->year,
            'application_open'     => $p->application_open?->toDateString(),
            'application_deadline' => $p->application_deadline?->toDateString(),
            'program_start'        => $p->program_start?->toDateString(),
            'program_end'          => $p->program_end?->toDateString(),
            'monks_quota'          => $p->monks_quota,
            'monks_selected'       => $p->monks_selected,
            'description_lo'       => $p->description_lo,
            'description_en'       => $p->description_en,
            'requirements_lo'      => $p->requirements_lo,
            'requirements_en'      => $p->requirements_en,
            'application_url'      => $p->application_url,
            'contact_email'        => $p->contact_email,
            'status'               => $p->status,
            'is_featured'          => $p->is_featured,
            'created_at'           => $p->created_at?->toDateString(),
        ];
    }
}
