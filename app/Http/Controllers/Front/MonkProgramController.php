<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\MonkExchangeProgram;
use Illuminate\Http\Request;

class MonkProgramController extends Controller
{
    public function index(Request $request)
    {
        $query = MonkExchangeProgram::with('partnerOrganization')->latest('year');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $programs = $query->paginate(12)->withQueryString();

        $counts = [
            'all'      => MonkExchangeProgram::count(),
            'open'     => MonkExchangeProgram::where('status', 'open')->count(),
            'ongoing'  => MonkExchangeProgram::where('status', 'ongoing')->count(),
            'closed'   => MonkExchangeProgram::where('status', 'closed')->count(),
        ];

        return view('front.monk-programs.index', compact('programs', 'counts'));
    }
}
