<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PartnerOrganization;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PartnerController extends Controller
{
    public function index(Request $request)
    {
        $query = PartnerOrganization::latest();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name_lo', 'like', "%$s%")
                  ->orWhere('name_en', 'like', "%$s%")
                  ->orWhere('acronym', 'like', "%$s%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $partners = $query->paginate($request->get('per_page', 15));

        return response()->json($partners->through(fn($p) => $this->format($p)));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name_lo'          => ['required', 'string', 'max:255'],
            'name_en'          => ['nullable', 'string', 'max:255'],
            'name_zh'          => ['nullable', 'string', 'max:255'],
            'acronym'          => ['nullable', 'string', 'max:50'],
            'country_code'     => ['nullable', 'string', 'max:10'],
            'country_name_lo'  => ['nullable', 'string'],
            'country_name_en'  => ['nullable', 'string'],
            'logo_url'         => ['nullable', 'url'],
            'website_url'      => ['nullable', 'url'],
            'description_lo'   => ['nullable', 'string'],
            'description_en'   => ['nullable', 'string'],
            'contact_person'   => ['nullable', 'string'],
            'contact_email'    => ['nullable', 'email'],
            'contact_phone'    => ['nullable', 'string'],
            'type'             => ['nullable', Rule::in(['government', 'ngo', 'academic', 'private', 'international'])],
            'partnership_since'=> ['nullable', 'digits:4'],
            'status'           => ['required', Rule::in(['active', 'inactive', 'pending'])],
            'sort_order'       => ['nullable', 'integer'],
        ]);

        $partner = PartnerOrganization::create($data);

        return response()->json($this->format($partner), 201);
    }

    public function show(PartnerOrganization $partner)
    {
        return response()->json($this->format($partner->load('mouAgreements')));
    }

    public function update(Request $request, PartnerOrganization $partner)
    {
        $data = $request->validate([
            'name_lo'          => ['required', 'string', 'max:255'],
            'name_en'          => ['nullable', 'string', 'max:255'],
            'acronym'          => ['nullable', 'string', 'max:50'],
            'country_code'     => ['nullable', 'string', 'max:10'],
            'country_name_lo'  => ['nullable', 'string'],
            'country_name_en'  => ['nullable', 'string'],
            'logo_url'         => ['nullable', 'url'],
            'website_url'      => ['nullable', 'url'],
            'description_lo'   => ['nullable', 'string'],
            'description_en'   => ['nullable', 'string'],
            'contact_person'   => ['nullable', 'string'],
            'contact_email'    => ['nullable', 'email'],
            'contact_phone'    => ['nullable', 'string'],
            'type'             => ['nullable', Rule::in(['government', 'ngo', 'academic', 'private', 'international'])],
            'partnership_since'=> ['nullable', 'digits:4'],
            'status'           => ['required', Rule::in(['active', 'inactive', 'pending'])],
            'sort_order'       => ['nullable', 'integer'],
        ]);

        $partner->update($data);

        return response()->json($this->format($partner));
    }

    public function destroy(PartnerOrganization $partner)
    {
        $partner->delete();

        return response()->json(['message' => 'ລຶບຄູ່ຮ່ວມມືສຳເລັດ']);
    }

    private function format(PartnerOrganization $p): array
    {
        return [
            'id'               => $p->id,
            'name_lo'          => $p->name_lo,
            'name_en'          => $p->name_en,
            'acronym'          => $p->acronym,
            'country_code'     => $p->country_code,
            'country_name_lo'  => $p->country_name_lo,
            'country_name_en'  => $p->country_name_en,
            'logo_url'         => $p->logo_url,
            'website_url'      => $p->website_url,
            'description_lo'   => $p->description_lo,
            'description_en'   => $p->description_en,
            'contact_person'   => $p->contact_person,
            'contact_email'    => $p->contact_email,
            'contact_phone'    => $p->contact_phone,
            'type'             => $p->type,
            'partnership_since'=> $p->partnership_since,
            'status'           => $p->status,
            'sort_order'       => $p->sort_order,
            'created_at'       => $p->created_at?->toDateString(),
        ];
    }
}
