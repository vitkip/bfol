<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\TranslationProject;
use Illuminate\Http\Request;

class TranslationProjectController extends Controller
{
    public function index(Request $request)
    {
        $query = TranslationProject::published()->latest();

        if ($request->filled('lang')) {
            $query->where(fn($q) => $q->where('source_language', $request->lang)
                                      ->orWhere('target_language', $request->lang));
        }

        $projects = $query->paginate(16)->withQueryString();

        $counts = [
            'total'     => TranslationProject::published()->count(),
            'completed' => TranslationProject::published()->where('status','completed')->count(),
        ];

        $languages = TranslationProject::published()
            ->selectRaw('DISTINCT source_language as lang')
            ->whereNotNull('source_language')
            ->unionAll(
                TranslationProject::published()
                    ->selectRaw('DISTINCT target_language as lang')
                    ->whereNotNull('target_language')
            )
            ->pluck('lang')
            ->unique()
            ->sort()
            ->values();

        return view('front.translations.index', compact('projects', 'counts', 'languages'));
    }
}
