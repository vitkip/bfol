<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\PartnerOrganization;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    public function show(PartnerOrganization $partner)
    {
        $partner->load([
            'mouAgreements' => fn($q) => $q->orderByDesc('signed_date')->limit(10),
            'monkPrograms'  => fn($q) => $q->orderByDesc('program_start')->limit(10),
            'aidProjects'   => fn($q) => $q->orderByDesc('start_date')->limit(10),
        ]);

        return view('front.partners.show', compact('partner'));
    }

    public function index(Request $request)
    {
        $query = PartnerOrganization::active()->orderBy('sort_order')->orderBy('name_lo');

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('country')) {
            $query->where('country_code', $request->country);
        }

        $partners = $query->paginate(24)->withQueryString();

        $countries = PartnerOrganization::active()
            ->select('country_code', 'country_name_lo', 'country_name_en', 'country_name_zh')
            ->distinct()
            ->orderBy('country_name_lo')
            ->get();

        $totalCount = PartnerOrganization::active()->count();

        return view('front.partners.index', compact('partners', 'countries', 'totalCount'));
    }
}
