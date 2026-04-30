<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\MouAgreement;
use Illuminate\Http\Request;

class MouController extends Controller
{
    public function index(Request $request)
    {
        $query = MouAgreement::with('partnerOrganization')->latest('signed_date');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $mous = $query->paginate(15)->withQueryString();

        $counts = [
            'all'        => MouAgreement::count(),
            'active'     => MouAgreement::where('status', 'active')->count(),
            'expired'    => MouAgreement::where('status', 'expired')->count(),
            'pending'    => MouAgreement::where('status', 'pending')->count(),
        ];

        return view('front.mou.index', compact('mous', 'counts'));
    }
}
