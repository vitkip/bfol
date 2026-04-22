<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AidProject;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AidProjectController extends Controller
{
    public function index(Request $request)
    {
        $query = AidProject::with(['partnerOrganization'])->latest();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) => $q->where('title_lo', 'like', "%$s%")
                                      ->orWhere('title_en', 'like', "%$s%"));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $projects = $query->paginate($request->get('per_page', 15));

        return response()->json($projects->through(fn($p) => $this->format($p)));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title_lo'       => ['required', 'string', 'max:400'],
            'title_en'       => ['nullable', 'string', 'max:400'],
            'country'        => ['nullable', 'string'],
            'partner_org_id' => ['nullable', 'exists:partner_organizations,id'],
            'type'           => ['nullable', 'string'],
            'description_lo' => ['nullable', 'string'],
            'description_en' => ['nullable', 'string'],
            'budget_usd'     => ['nullable', 'numeric', 'min:0'],
            'start_date'     => ['nullable', 'date'],
            'end_date'       => ['nullable', 'date'],
            'status'         => ['required', Rule::in(['active', 'completed', 'suspended', 'planned'])],
            'report_url'     => ['nullable', 'url'],
        ]);

        $data['author_id'] = $request->user()->id;

        $project = AidProject::create($data);

        return response()->json($this->format($project->fresh('partnerOrganization')), 201);
    }

    public function show(AidProject $aidProject)
    {
        return response()->json($this->format($aidProject->load('partnerOrganization')));
    }

    public function update(Request $request, AidProject $aidProject)
    {
        $data = $request->validate([
            'title_lo'       => ['required', 'string', 'max:400'],
            'title_en'       => ['nullable', 'string', 'max:400'],
            'country'        => ['nullable', 'string'],
            'partner_org_id' => ['nullable', 'exists:partner_organizations,id'],
            'type'           => ['nullable', 'string'],
            'description_lo' => ['nullable', 'string'],
            'description_en' => ['nullable', 'string'],
            'budget_usd'     => ['nullable', 'numeric', 'min:0'],
            'start_date'     => ['nullable', 'date'],
            'end_date'       => ['nullable', 'date'],
            'status'         => ['required', Rule::in(['active', 'completed', 'suspended', 'planned'])],
            'report_url'     => ['nullable', 'url'],
        ]);

        $aidProject->update($data);

        return response()->json($this->format($aidProject->fresh('partnerOrganization')));
    }

    public function destroy(AidProject $aidProject)
    {
        $aidProject->delete();

        return response()->json(['message' => 'ລຶບໂຄງການສຳເລັດ']);
    }

    private function format(AidProject $p): array
    {
        return [
            'id'             => $p->id,
            'title_lo'       => $p->title_lo,
            'title_en'       => $p->title_en,
            'country'        => $p->country,
            'partner_org_id' => $p->partner_org_id,
            'partner'        => $p->partnerOrganization ? [
                'id'   => $p->partnerOrganization->id,
                'name' => $p->partnerOrganization->name_lo ?: $p->partnerOrganization->name_en,
            ] : null,
            'type'           => $p->type,
            'description_lo' => $p->description_lo,
            'description_en' => $p->description_en,
            'budget_usd'     => $p->budget_usd,
            'start_date'     => $p->start_date?->toDateString(),
            'end_date'       => $p->end_date?->toDateString(),
            'status'         => $p->status,
            'report_url'     => $p->report_url,
            'created_at'     => $p->created_at?->toDateString(),
        ];
    }
}
