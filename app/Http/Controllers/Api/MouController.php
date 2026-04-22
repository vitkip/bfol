<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MouAgreement;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MouController extends Controller
{
    public function index(Request $request)
    {
        $query = MouAgreement::with('partnerOrganization')->latest();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('title_lo', 'like', "%$s%")
                  ->orWhere('title_en', 'like', "%$s%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $mous = $query->paginate($request->get('per_page', 15));

        return response()->json($mous->through(fn($m) => $this->format($m)));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title_lo'        => ['required', 'string', 'max:500'],
            'title_en'        => ['nullable', 'string', 'max:500'],
            'title_zh'        => ['nullable', 'string', 'max:500'],
            'partner_org_id'  => ['required', 'exists:partner_organizations,id'],
            'signed_date'     => ['nullable', 'date'],
            'expiry_date'     => ['nullable', 'date'],
            'document_url'    => ['nullable', 'url'],
            'status'          => ['required', Rule::in(['active', 'expired', 'pending', 'terminated'])],
            'description_lo'  => ['nullable', 'string'],
            'description_en'  => ['nullable', 'string'],
            'signers_lo'      => ['nullable', 'string'],
            'signers_en'      => ['nullable', 'string'],
            'scope_lo'        => ['nullable', 'string'],
            'scope_en'        => ['nullable', 'string'],
        ]);

        $mou = MouAgreement::create($data);

        return response()->json($this->format($mou->fresh('partnerOrganization')), 201);
    }

    public function show(MouAgreement $mou)
    {
        return response()->json($this->format($mou->load('partnerOrganization')));
    }

    public function update(Request $request, MouAgreement $mou)
    {
        $data = $request->validate([
            'title_lo'        => ['required', 'string', 'max:500'],
            'title_en'        => ['nullable', 'string', 'max:500'],
            'partner_org_id'  => ['required', 'exists:partner_organizations,id'],
            'signed_date'     => ['nullable', 'date'],
            'expiry_date'     => ['nullable', 'date'],
            'document_url'    => ['nullable', 'url'],
            'status'          => ['required', Rule::in(['active', 'expired', 'pending', 'terminated'])],
            'description_lo'  => ['nullable', 'string'],
            'description_en'  => ['nullable', 'string'],
            'signers_lo'      => ['nullable', 'string'],
            'signers_en'      => ['nullable', 'string'],
            'scope_lo'        => ['nullable', 'string'],
            'scope_en'        => ['nullable', 'string'],
        ]);

        $mou->update($data);

        return response()->json($this->format($mou->fresh('partnerOrganization')));
    }

    public function destroy(MouAgreement $mou)
    {
        $mou->delete();

        return response()->json(['message' => 'ລຶບ MOU ສຳເລັດ']);
    }

    private function format(MouAgreement $m): array
    {
        return [
            'id'             => $m->id,
            'title_lo'       => $m->title_lo,
            'title_en'       => $m->title_en,
            'partner_org_id' => $m->partner_org_id,
            'partner'        => $m->partnerOrganization ? [
                'id'     => $m->partnerOrganization->id,
                'name'   => $m->partnerOrganization->name_lo ?: $m->partnerOrganization->name_en,
                'acronym'=> $m->partnerOrganization->acronym,
            ] : null,
            'signed_date'    => $m->signed_date?->toDateString(),
            'expiry_date'    => $m->expiry_date?->toDateString(),
            'document_url'   => $m->document_url,
            'status'         => $m->status,
            'description_lo' => $m->description_lo,
            'description_en' => $m->description_en,
            'signers_lo'     => $m->signers_lo,
            'signers_en'     => $m->signers_en,
            'scope_lo'       => $m->scope_lo,
            'scope_en'       => $m->scope_en,
            'created_at'     => $m->created_at?->toDateString(),
        ];
    }
}
