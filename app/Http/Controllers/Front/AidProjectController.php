<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\AidProject;
use Illuminate\Http\Request;

class AidProjectController extends Controller
{
    public function index(Request $request)
    {
        $query = AidProject::with('partnerOrganization')->latest('start_date');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $projects = $query->paginate(15)->withQueryString();

        $counts = [
            'all'       => AidProject::count(),
            'active'    => AidProject::where('status', 'active')->count(),
            'completed' => AidProject::where('status', 'completed')->count(),
            'planned'   => AidProject::where('status', 'planned')->count(),
        ];

        return view('front.aid-projects.index', compact('projects', 'counts'));
    }
}
